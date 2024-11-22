<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use App\Models\PurchaseOrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseRequestDetail;
use Carbon\Carbon;
use App\Exports\PurchaseOrderExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF; 

class PurchaseOrderController extends Controller
{
    public function Allpurchaseorder()
    {
        return view('purchase_order.all_purchaseorder');
    }


    public function AddPurchaseorderid($id)
    {
        // Ambil data Purchase Request berdasarkan ID beserta detail yang statusnya kosong
        $purchaseRequest = PurchaseRequest::with(['detailrequest' => function ($query) {
            $query->whereNull('status')->orWhere('status', '');
        }, 'detailrequest.item.unit', 'cbd.details'])->findOrFail($id);

        // Ambil order_no dan sample_code dari relasi CBD
        $purchaseRequest->order_no = $purchaseRequest->cbd->order_no ?? null;
        $purchaseRequest->sample_code = $purchaseRequest->cbd->sample_code ?? null;
        $purchaseRequest->item = $purchaseRequest->cbd->item ?? null;

        // Kirimkan data ke view untuk ditampilkan
        return view('purchase_order.add_purchaseorderid', compact('purchaseRequest'));
    } 

    public function Editpurchaseorder($id)
    {
        // Fetch the Purchase Order with details, supplier
        $purchaseOrder = PurchaseOrder::with(['detailorder.item.unit', 'supplier'])->findOrFail($id);



        $purchaseRequestId =  $purchaseOrder->purchase_request_id;
        $supplierId = $purchaseOrder->supplier_id;
    
        $purchaseRequest =  PurchaseRequestDetail::where('purchase_request_id', $purchaseRequestId)
            ->where('supplier_id', $supplierId)
            ->where('status', '')
            ->with('item.unit', 'supplier')
            ->get();
    
   

 

        return view('purchase_order.edit_purchaseorderid', compact('purchaseOrder', 'purchaseRequest'));
    }

 
 

    protected function generatePurchaseOrderNumber()
    {
        $latestOrder = PurchaseOrder::latest()->first();
        $number = $latestOrder ? (int) substr($latestOrder->purchase_order_no, 5, 4) + 1 : 1;
        $year = date('Y');
        $month = $this->convertToRoman(date('n'));
    
        return sprintf('TIMW/%04d/PO/%s/%d', $number, $month, $year);
    }
    
    protected function convertToRoman($month)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
    
        return $map[$month];
    }

    public function Storepurchaseorder(Request $request)
    {
        // Validasi input request
        $request->validate([
            'purchase_request_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'delivery_at' => 'required|string',
            'terms' => 'required|string',
            'payment' => 'required|string',
            'applicant' => 'required|string',
            'allocation' => 'required|string',
            'approval' => 'required|string',
            'rule' => 'nullable|string',
            'status' => 'nullable|string',
            'details' => 'required|array|min:1', // Minimal 1 detail
            'details.*.item_id' => 'required|integer',
            'details.*.qty' => 'required|numeric',
            'details.*.price' => 'required|numeric',
        ]);
    

    

            // Simpan data ke purchase_orders
            $purchaseOrder = PurchaseOrder::create([
                'purchase_order_no' => $this->generatePurchaseOrderNumber(), // Fungsi untuk generate purchase_order_no
                'purchase_request_id' => $request->purchase_request_id,
                'supplier_id' => $request->supplier_id,
                'date_in_house' => $request->request_in_house,
                'delivery_at' => $request->delivery_at,
                'terms' => $request->terms,
                'payment' => $request->payment,
                'ship_mode' => $request->shipment_mode,
                'applicant' => $request->applicant,
                'allocation' => $request->allocation,
                'approval' => $request->approval,
                'quotation_no' => $request->quotation_no,
                'quatition_file' => '',

                'subtotal' => $request->sub_total,
                'rounding' => $request->rounding ?? 0,
                'discount' => $request->discount ?? 0,
                'vat' => $request->tax,
                'vat_amount' => $request->tax_end,
                'grand_total' => $request->grand_total_end,
                'purchase_amount' => $request->purchase_amount_end,
                'note1' => $request->note1,
                'note2' => $request->note2,
                'rule' => $request->rule,
                'status' =>'po',
                'revision_no' => 0,
                'user_id' => Auth::id(), // Ambil user_id dari user yang login
            ]);
    
            // Simpan detail pembelian ke purchase_order_details
            foreach ($request->details as $detailData) {
                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'item_id' => $detailData['item_id'],
                    'color' => $detailData['color'] ?? null,
                    'size' => $detailData['size'] ?? null,
                    'qty' => $detailData['qty'],
                    'price' => $detailData['price'],
                    'total_price' => $detailData['total_price'] ?? ($detailData['qty'] * $detailData['price']), // Hitung total_price jika tidak disediakan
                    'status' =>'',
                    'remark' => $detailData['remark'] ?? null,
                ]);


                 // Update status pada PurchaseRequestDetail
        PurchaseRequestDetail::where('purchase_request_id', $request->purchase_request_id)
        ->where('item_id', $detailData['item_id'])
        ->where(function($query) use ($detailData) {
            if (isset($detailData['color'])) {
                $query->where('color', $detailData['color']);
            } else {
                $query->whereNull('color');
            }
            if (isset($detailData['size'])) {
                $query->where('size', $detailData['size']);
            } else {
                $query->whereNull('size');
            }
        })
        ->update(['status' => 'po']);


            }

          
            
   
    
            return redirect()->route('all.purchaseorder')->with('success', 'Purchase Request berhasil disimpan.');
       
    }

 

    public function Getpurchaseorder(Request $request)
    {
        if ($request->ajax()) {
            $query = PurchaseOrder::with(['purchaseRequest', 'detailorder.item.unit', 'supplier']);

            // Filter berdasarkan startDate dan endDate
            if ($request->has('startDate') && $request->has('endDate')) {
                $startDate = $request->startDate;
                $endDate = $request->endDate;
    
                // Filter tanggal pada kolom created_at (atau sesuaikan dengan kolom yang relevan)
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
    
            $data = $query->get();
                
    
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('supplier_name', function($row) {
                    return $row->supplier->supplier_name;
                })
                ->addColumn('item_details', function($row) {
                    $itemDetails = [];
                    foreach ($row->detailorder as $detail) {
                        $itemName = strlen($detail->item->item_name) > 25 ? 
                                    substr($detail->item->item_name, 0, 25) . '...' : 
                                    $detail->item->item_name;
    
                        $itemDetails[] = [
                            'item_code' => $detail->item->item_code,
                            'item_name' =>  $itemName,
                            'unit_code' => $detail->item->unit->unit_code,
                            'color' => $detail->color ?? '',
                            'size' => $detail->size ?? '',  
                            'qty' => $detail->qty,
                            'price' => $detail->price,
                            'status' => $detail->status,
                        ];
                    }
                    return $itemDetails;
                })
                ->addColumn('purchase_request_no', function($row) {
                    return $row->purchaseRequest->purchase_request_no;
                })
                ->addColumn('mo', function($row) {
                    return $row->purchaseRequest->mo.'|'. $row->purchaseRequest->style;
                })

                ->addColumn('action', function ($row) {
                    $hasStatus = false;
                    foreach ($row->detailorder as $detail) {
                        if (!empty($detail->status)) {
                            $hasStatus = true;
                            break;
                        }
                    }
    
                    $editButton = $hasStatus ? 
                        '<a href="javascript:void(0)" class="dropdown-item text-muted disabled"> &nbsp; Edit</a>' : 
                        '<a href="/edit/purchaseorder/'.$row->id.'" class="dropdown-item text-primary"> &nbsp; Edit</a>';
                    
                    $deleteButton = $hasStatus ? 
                        '<a href="javascript:void(0)" class="dropdown-item text-muted disabled"> &nbsp; Delete</a>' : 
                        '<a href="javascript:void(0)" class="dropdown-item text-danger deletePurchaseorder" data-id="' . $row->id . '"> &nbsp; Delete</a>';
    
                    return '<div class="d-flex align-items-center justify-content-between flex-wrap">
                              <div class="d-flex align-items-center">
                                  <div class="d-flex align-items-center">
                                      <div class="actions dropdown">
                                          <a href="#" data-bs-toggle="dropdown"> ••• </a>
                                          <div class="dropdown-menu" role="menu">
                                              ' . $editButton . '
                                              ' . $deleteButton . '
                                              <a href="/pdf/purchaseorder/'.$row->id.'" class="dropdown-item text-info" target="_blank"> &nbsp; View PDF</a>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>';
                })
                ->rawColumns(['purchase_request_no','action'])
                ->make(true);
        }
    } 

    public function GetpurchaseorderCount(){
        $purchaseCount = PurchaseOrder::count();

        return response()->json([
            'request' =>$purchaseCount,
         
        ]);
    }

    public function Updatepurchaseorder(Request $request, $id)
    {
        // Validasi input request
        $request->validate([
            'purchase_request_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'delivery_at' => 'required|string',
          
            'applicant' => 'required|string',
            'allocation' => 'required|string',
            'approval' => 'required|string',
            'rule' => 'nullable|string',
            'status' => 'nullable|string',
            'details' => 'required|array|min:1', // Minimal 1 detail
            'details.*.item_id' => 'required|integer',
            'details.*.qty' => 'required',
            'details.*.price' => 'required',
        ]);
    
        // Ambil purchase order yang akan diupdate
        $purchaseOrder = PurchaseOrder::findOrFail($id);
    
        // Ambil purchase request terkait
        $purchaseRequest = PurchaseRequest::findOrFail($request->purchase_request_id);
    
        // Ambil semua item_id yang ada dalam request details
        $detailItemIds = collect($request->details)->pluck('item_id')->toArray();
    
        // Inisialisasi array untuk menyimpan deleted detail IDs
        $deletedDetailIds = [];
    
        // Hapus semua detail terkait dari purchase order yang ada
        foreach ($purchaseOrder->detailorder as $detail) {
            $deletedDetailIds[] = $detail->id;
            $detail->delete();
    
            // Update status pada PurchaseRequestDetail yang terkait dengan item yang dihapus
            PurchaseRequestDetail::where('purchase_request_id', $purchaseRequest->id)
                ->where('item_id', $detail->item_id)
                ->where(function ($query) use ($detail) {
                    if ($detail->color) {
                        $query->where('color', $detail->color);
                    } else {
                        $query->whereNull('color');
                    }
                    if ($detail->size) {
                        $query->where('size', $detail->size);
                    } else {
                        $query->whereNull('size');
                    }
                })
                ->update(['status' => '']);
        }
    
        // Simpan detail pembelian ke purchase_order_details
        foreach ($request->details as $detailData) {
            $newDetail = PurchaseOrderDetail::create([
                'purchase_order_id' => $purchaseOrder->id,
                'item_id' => $detailData['item_id'],
                'color' => $detailData['color'] ?? null,
                'size' => $detailData['size'] ?? null,
                'qty' => $detailData['qty'],
                'price' => $detailData['price'],
                'total_price' => $detailData['total_price'],
                'status' => '',
                'remark' => $detailData['remark'] ?? null,
            ]);
    
            // Update status pada PurchaseRequestDetail yang sesuai
            PurchaseRequestDetail::where('purchase_request_id', $purchaseRequest->id)
                ->where('item_id', $detailData['item_id'])
                ->where(function ($query) use ($detailData) {
                    if (isset($detailData['color'])) {
                        $query->where('color', $detailData['color']);
                    } else {
                        $query->whereNull('color');
                    }
                    if (isset($detailData['size'])) {
                        $query->where('size', $detailData['size']);
                    } else {
                        $query->whereNull('size');
                    }
                })
                ->update(['status' => 'po']);
        }

     
    
        // Update data pada purchase order
        $purchaseOrder->update([
            'purchase_request_id' => $request->purchase_request_id,
            'supplier_id' => $request->supplier_id,
            'date_in_house' => $request->request_in_house,
            'delivery_at' => $request->delivery_at,
            'terms' => $request->terms,
            'payment' => $request->payment,
            'ship_mode' => $request->shipment_mode,
            'applicant' => $request->applicant,
            'allocation' => $request->allocation,
            'approval' => $request->approval,
            'quotation_no' => $request->quotation_no,
            'quatition_file' => '',

            'subtotal' => $request->sub_total,
            'rounding' => $request->rounding ?? 0,
            'discount' => $request->discount ?? 0,
            'vat' => $request->tax,
            'vat_amount' => $request->tax_end,
            'grand_total' => $request->grand_total_end,
            'purchase_amount' => $request->purchase_amount_end,
            'note1' => $request->note1,
            'note2' => $request->note2,
            'rule' => $request->rule,
            'status' =>'po',
            'remarksx' => $request->remarksx,
            'revision_no' => $purchaseOrder->revision_no + 1, 

            'user_id' => Auth::id(), // Ambil user_id dari user yang login
        ]);
    
        // Kembalikan ke halaman yang sesuai setelah selesai menyimpan
        return redirect()->route('all.purchaseorder')->with('success', 'Purchase Order berhasil diperbarui.');

          //return response
          return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Disimpan!',
            'data'    => $post,
            'alert-type' => 'success'  
        ]);
    }


    public function ExportPDF($id)
        {
            // $purchaseorder = PurchaseOrder::with(['detailorder.item.unit', 'supplier'])->findOrFail($id);
            // $pdf = Pdf::loadView('purchase_order.print', compact('purchaseorder'));
            // return $pdf->stream('purchase_order_' . $id . '.pdf');



                    // Ambil PurchaseOrder dengan relasi detailorder, item, unit, dan supplier
            $purchaseorder = PurchaseOrder::with(['purchaseRequest','detailorder.item.unit', 'supplier'])->findOrFail($id);

            // Pastikan PurchaseOrder dan Supplier ditemukan
            if (!$purchaseorder) {
                abort(404);
            }
 
            // Ambil remark dari supplier
            $supplierRemark = $purchaseorder->supplier->remark;

            // Pilih view berdasarkan remark supplier
            $viewName = ($supplierRemark == 'Lokal') ? 'purchase_order.print_local' : 'purchase_order.print_import';

            // Load view yang sesuai dengan remark
            $pdf = \PDF::loadView($viewName, compact('purchaseorder'));

            // Stream atau unduh PDF
            return $pdf->stream('purchase_order_' . $id . '.pdf');
        }



      



     
        public function Deletepurchaseorder(Request $request, $id)
        {
            if ($request->has('detail_id')) {
                // Hapus detail berdasarkan ID detail
                $detail = PurchaseOrderDetail::findOrFail($request->input('detail_id'));
        
                // Mengembalikan status item menjadi kosong di PurchaseRequestDetail
                PurchaseRequestDetail::where('purchase_request_id', $detail->purchase_order->purchase_request_id)
                    ->where('item_id', $detail->item_id)
                    ->where(function($query) use ($detail) {
                        if ($detail->color) {
                            $query->where('color', $detail->color);
                        } else {
                            $query->whereNull('color');
                        }
                        if ($detail->size) {
                            $query->where('size', $detail->size);
                        } else {
                            $query->whereNull('size');
                        }
                        $query->where('qty', $detail->qty);
                    })
                    ->update(['status' => '']);
        
                $detail->delete();
        
                return response()->json([
                    'success' => true,
                    'message' => 'Detail berhasil dihapus!',
                ]);
            } else {
                // Hapus Purchase Order beserta detailnya
                $purchaseOrder = PurchaseOrder::findOrFail($id);
                
                // Mengembalikan status item menjadi kosong di PurchaseRequestDetail untuk setiap detail
                foreach ($purchaseOrder->detailorder as $detail) {
                    PurchaseRequestDetail::where('purchase_request_id', $purchaseOrder->purchase_request_id)
                        ->where('item_id', $detail->item_id)
                        ->where(function($query) use ($detail) {
                            if ($detail->color) {
                                $query->where('color', $detail->color);
                            } else {
                                $query->whereNull('color');
                            }
                            if ($detail->size) {
                                $query->where('size', $detail->size);
                            } else {
                                $query->whereNull('size');
                            }
                            $query->where('qty', $detail->qty);
                        })
                        ->update(['status' => '']);
                }
        
                $purchaseOrder->detailorder()->delete();
                $purchaseOrder->delete();
        
                return response()->json([
                    'success' => true,
                    'message' => 'Purchase Order dan detail berhasil dihapus!',
                ]);
            }
        }

        public function Getpurchaseordersupplier()
        {
            $suppliersx = PurchaseOrder::with(['detailorder' => function ($query) {
                $query->whereNull('status');
            }, 'detailorder.item.unit', 'supplier' => function ($query) {
                $query->select('id', 'supplier_name', 'supplier_address', 'supplier_person', 'remark');
            }])
            ->whereHas('detailorder', function ($query) {
                $query->whereNull('status')
                ->orWhere('status', '');
            })
            
            ->get();

            $suppliers = $suppliersx->pluck('supplier')->unique('id')->values();
    
                return response()->json($suppliers);
        }

 
        public function Getpurchaseorderitem(Request $request)
            {
                $supplierId = $request->input('id2');

            $items = PurchaseOrder::with(['detailorder' => function($query) {
                    $query->whereNull('remark')
                    ->orWhere('remark', '');
                }, 'detailorder.item.unit', 'supplier'])
                ->where('supplier_id', $supplierId)
                ->get();

            return response()->json($items);
            }



        public function Getpurchaseorderid($original_no)
            {
               // Ambil data PurchaseOrderDetail berdasarkan supplier_id yang diberikan
                    $purchaseOrderItems = PurchaseOrder::with(['purchaseRequest', 'detailorder.item.unit', 'supplier'])
                    ->where('supplier_id', $original_no) // langsung mencari dengan supplier_id
                    ->get();

                // Jika tidak ditemukan data, kembalikan response kosong
                if ($purchaseOrderItems->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No items found for the given supplier.',
                        'data' => []
                    ], 404);
                }

                $formattedItems = $purchaseOrderItems->map(function($detail) {
                    return [
                        'item_code'      => $detail->item_code,
                        'item_name'      => $detail->detailorder->item_name,
                        'supplier_name'  => $detail->purchaseOrder->supplier->supplier_name,
                        'po'             => $detail->purchaseOrder->purchase_order_no, // Adjust field name as per your schema
                        'color_code'     => $detail->color,
                        'color_name'     => $detail->color_name ?? '', // Ensure these fields exist
                        'size'           => $detail->size ?? '',
                        'mo'             => $detail->purchaseOrder->purchaseRequest->mo ?? '', // Adjust as needed
                        'qty'            => $detail->qty,
                    ];
                });

                // Kembalikan data dalam bentuk JSON
                return response()->json([
                    'success' => true,
                    'message' => 'Items found successfully.',
                    'data' => $formattedItems
                ]);
            }


            public function getSupplierPOItems($supplier_id)
            {
                // Ambil data PurchaseOrderDetail berdasarkan supplier_id yang diberikan
                $purchaseOrderItems = PurchaseOrderDetail::with(['item.unit', 'purchaseOrder'])
                    ->whereHas('purchaseOrder', function($query) use ($supplier_id) {
                        $query->where('supplier_id', $supplier_id);
                    })
                    ->get();
        
                // Jika tidak ditemukan data, kembalikan response kosong
                if ($purchaseOrderItems->isEmpty()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No items found for the given supplier.',
                        'data' => []
                    ], 404);
                }
        
                // Kembalikan data dalam bentuk JSON
                return response()->json([
                    'success' => true,
                    'message' => 'Items found successfully.',
                    'data' => $purchaseOrderItems
                ]);
            }



            public function Exportpurchaseorder(Request $request){
                $startDate = $request->query('startDate');
                $endDate = $request->query('endDate');

                return Excel::download(new PurchaseOrderExport($startDate, $endDate), 'purchase_orders.xlsx');
            }
        

}
