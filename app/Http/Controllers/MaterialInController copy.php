<?php

namespace App\Http\Controllers;

use App\Models\MaterialIn;
use App\Models\MaterialInDetail;
use Illuminate\Http\Request;
use App\Imports\MaterialInImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use DataTables;
use App\Models\PurchaseRequest;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseOrder;
use App\Exports\MaterialInWithDetailsExport;

class MaterialInController extends Controller
{
    public function Allmaterialin()
    {
        return view('material_in.all_materialin
        ');
    }

    public function Allmaterialindetail()
    {
        return view('material_in.all_materialindetail
        ');
    }
 

    public function Addmaterialin()
    {
        return view('material_in.add_materialin
        ');
    }

    public function Addmaterialinsp()
    {
        return view('material_in.add_materialinsp
        ');
    }


    public function Importmaterialin(Request $request){

        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,csv'
        ]);

        $file = $request->file('import_file');

        $import = new MaterialInImport;
        Excel::import($import, $file);

        if (!$import->isHeaderValid()) {
            return response()->json(['error' => 'Invalid header.'], 400);
        }

        // Convert the data to array for JSON response
        $data = $import->getData()->toArray();

        return response()->json([
            'header' => $import->expectedHeader,
            'rows' => $data
        ]);
 
    }

    public function Getpurchaseorder(Request $request)
{
    if ($request->ajax()) {
        // Initialize the query builder with relationships
        $query = PurchaseOrder::with(['purchaseRequest', 'detailorder.item.unit', 'supplier']);

        // Apply date filters if present in the request
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            
            // Convert to Carbon instance for consistency or ensure correct format
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay(); // Set start of the day
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay(); // Set end of the day

            // Apply date filter to purchase_order_date (adjust column name if needed)
            $query->whereBetween('purchase_order_date', [$startDate, $endDate]);
        }

        // Retrieve data
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
                        'remark' => $detail->remark,
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
 

    public function Getmaterialin0(Request $request)
    {
        if ($request->ajax()) {
            $query = MaterialIn::with(['details', 'details.item.unit', 'details.purchaseOrder.purchaseRequest']);

            // Apply date range filter if both dates are provided
            if ($request->has('startDate') && $request->has('endDate')) {
                $startDate = $request->input('startDate');
                $endDate = $request->input('endDate');
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('item_details', function($row) {
                    $itemDetails = [];
                    foreach ($row->details as $detail) {
                        $itemName = strlen($detail->item->item_name) > 25 ? 
                                    substr($detail->item->item_name, 0, 25) . '...' : 
                                    $detail->item->item_name;

                        $itemDetails[] = [
                            'original_no' => $detail->original_no ?? '',
                            'item_code' => $detail->item->item_code,
                            'supplier_name' => $detail->supplier_name ?? '', 
                            'item_name' =>  $itemName,
                            'unit_code' => $detail->item->unit->unit_code ?? '',
                            'color_code' => $detail->color_code ?? '', 
                            'color_name' => $detail->color_name ?? '', 
                            'size' => $detail->size ?? '',
                            'batch' =>  $detail->batch?? '',
                            'roll' =>  $detail->roll?? '',
                            'mo' =>  $detail->mo?? '',
                            'qty' => $detail->qty,
                            'actual_weight' => $detail->actual_weight ?? '',
                            'rak' => $detail->rak,
                        ];
                    }
                    return $itemDetails;
                })
                ->addColumn('action', function ($row) {
                    $hasStatus = $row->details->contains(function ($detail) {
                        return !empty($detail->status);
                    });

                    $editButton = $hasStatus ? 
                        '<a href="javascript:void(0)" class="dropdown-item text-muted disabled"> &nbsp; Edit</a>' : 
                        '<a href="/edit/materialin/'.$row->id.'" class="dropdown-item text-primary"> &nbsp; Edit</a>';

                    $deleteButton = $hasStatus ? 
                        '<a href="javascript:void(0)" class="dropdown-item text-muted disabled"> &nbsp; Delete</a>' : 
                        '<a href="javascript:void(0)" class="dropdown-item text-danger deleteMaterialin" data-id="' . $row->id . '"> &nbsp; Delete</a>';

                    return '<div class="d-flex align-items-center justify-content-between flex-wrap">
                              <div class="d-flex align-items-center">
                                  <div class="d-flex align-items-center">
                                      <div class="actions dropdown">
                                          <a href="#" data-bs-toggle="dropdown"> ••• </a>
                                          <div class="dropdown-menu" role="menu">
                                              ' . $editButton . '
                                              ' . $deleteButton . '
                                             
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

 

    public function Getmaterialindetail(Request $request)
    {
        if ($request->ajax()) {
            $query = MaterialInDetail::with(['materialIn', 'item.unit']);
    
            // Filter by date range
            if ($request->has('startDate') && $request->has('endDate')) {
                $startDate = $request->startDate;
                $endDate = $request->endDate;
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
    
            $data = $query->get();          
    
            return datatables()->of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }



       






    public function Storematerialin(Request $request)
    {
        
         // Validate the request data
         $request->validate([
            'supplier_id' => 'required|integer',
            'no_sj' => 'required|string',
            'recived_by' => 'required|string',
            'location' => 'required|string',
            'courier' => 'required|string',
            'remark' => 'nullable|string',
            'details.*.original_no' => 'required|unique:material_in_details,original_no',
            // 'details' => 'required|array',
          
        ]);

            // Generate material_in_no
        $yearMonth = date('Ym');
        $lastMaterialIn = MaterialIn::where('material_in_no', 'like','IN'. $yearMonth . '%')
            ->orderBy('material_in_no', 'desc')
            ->first();

        if ($lastMaterialIn) {
            $lastNumber = substr($lastMaterialIn->material_in_no, -6);
            $newNumber = str_pad((int)$lastNumber + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '000001';
        }

        $materialInNo = 'IN' . $yearMonth . $newNumber;



        $materialIn = MaterialIn::create([
            'material_in_no' =>$materialInNo,
                 
            'supplier_id' => $request->supplier_id,
            'no_sj' => $request->no_sj,
            'received_by' => $request->recived_by,
            'location' =>$request->location,
            'courier' => $request->courier,
            'remark' => $request->remark,
            // 'status' =>$request->status, 
            'user_id' =>Auth::id(),
        ]);

        // Simpan detail pembelian ke purchase_order_details
        foreach ($request->details as $detail) {
            MaterialInDetail::create([
                'material_in_id' => $materialIn->id,
                'purchase_order_id' => '',
                'original_no' => $detail['original_no'],
                'receive_date' => $detail['receive_date'] ?? null,
                'supplier_name' => $detail['supplier_name'] ?? null,
                'item_code' => $detail['item_code'],
                'po' => $detail['po'] ?? null,
                'color_code' => $detail['color_code'] ?? null,
                'color_name' => $detail['color_name'] ?? null,
                'size' => $detail['size'] ?? null,
                'batch' => $detail['batch'] ?? null,
                'roll' => $detail['roll'],
                'gross_weight' => $detail['gross_weight'] ?? null,
                'net_weight' => $detail['net_weight'] ?? null,
                'qty' => $detail['qty'],
                'basic_width' => $detail['basic_width'] ?? null,
                'basic_grm' => $detail['basic_grm'] ?? null,
                'mo' => $detail['mo'] ?? null,
                'actual_weight' => $detail['actual_weight'] ?? null,
                'rak' => $detail['rak'] ?? null,
                'note' => '',
            ]);

        
           }
           return redirect()->route('all.materialin')->with('message', 'data berhasil diperbarui.');
    }




    public function Deletematerialin(Request $request, $id)
    {
     
        try {
            if ($request->has('detail_id')) {
                // Hapus detail berdasarkan ID detail
                $detail = MaterialInDetail::findOrFail($request->input('detail_id'));
    
                // Mengembalikan status item menjadi kosong di PurchaseOrderDetail
                PurchaseOrderDetail::where('purchase_order_id', $detail->purchase_order_id)
                    ->where('item_id', $detail->item_id)
                    ->update(['remark' => '']);
    
                $detail->delete();
    
              
                return response()->json([
                    'message' => true,
                    'message' => 'Detail berhasil dihapus!',
                ]);
            } else {
                // Hapus MaterialIn beserta detailnya
                $material_in = MaterialIn::findOrFail($id);
    
                // Mengembalikan status item menjadi kosong di PurchaseOrderDetail untuk setiap detail
                foreach ($material_in->details as $detail) {
                    PurchaseOrderDetail::where('purchase_order_id', $detail->purchase_order_id)
                        ->where('item_id', $detail->item_id)
                        ->update(['remark' => '']);
                }
    
                $material_in->details()->delete();
                $material_in->delete();
    
            
                return response()->json([
                    'message' => true,
                    'message' => 'Material In dan detail berhasil dihapus!',
                ]);
            }
        } catch (\Exception $e) {
     
            return response()->json([
                'message' => false,
                'message' => 'Terjadi kesalahan saat menghapus: ' . $e->getMessage(),
            ]);
        }
    }


    public function EditMaterialin($id){

         // Fetch the Purchase Order with details, supplier
    $materialIN = Materialin::with(['details.item.unit', 'supplier'])->findOrFail($id);


    return view('material_in.edit_materialin', compact('materialIN'));


    }


    public function Updatematerialin(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'supplier_id' => 'required|integer',
            'no_sj' => 'required|string',
            'recived_by' => 'required|string',
            'location' => 'required|string',
            'courier' => 'required|string',
            'remark' => 'nullable|string',
            'details.*.original_no' => 'required',
            // 'details.*.roll' => 'required|numeric',
            // 'details.*.qty' => 'required|numeric',
            // Tambahkan validasi tambahan jika diperlukan
        ]);

        // Update data MaterialIn
        $materialIn = MaterialIn::findOrFail($id);
        $materialIn->update([
            'supplier_id' => $request->supplier_id,
            'no_sj' => $request->no_sj,
            'received_by' => $request->recived_by,
            'location' => $request->location,
            'courier' => $request->courier,
            'remark' => $request->remark,
            'user_id' => Auth::id(), // Memperbarui user_id jika diperlukan
        ]);

        // Hapus detail yang ada sebelumnya
        MaterialInDetail::where('material_in_id', $id)->delete();

        // Simpan detail yang baru
        foreach ($request->details as $detail) {
            MaterialInDetail::create([
                'material_in_id' => $materialIn->id,
                'purchase_order_id' => '', // Kosong jika tidak digunakan
                'original_no' => $detail['original_no'],
                'receive_date' => $detail['receive_date'] ?? null,
                'supplier_name' => $detail['supplier_name'] ?? null,
                'item_code' => $detail['item_code'],
                'po' => $detail['po'] ?? null,
                'color_code' => $detail['color_code'] ?? null,
                'color_name' => $detail['color_name'] ?? null,
                'size' => $detail['size'] ?? null,
                'batch' => $detail['batch'] ?? null,
                'roll' => $detail['roll'],
                'gross_weight' => $detail['gross_weight'] ?? null,
                'net_weight' => $detail['net_weight'] ?? null,
                'qty' => $detail['qty'],
                'basic_width' => $detail['basic_width'] ?? null,
                'basic_grm' => $detail['basic_grm'] ?? null,
                'mo' => $detail['mo'] ?? null,
                'actual_weight' => $detail['actual_weight'] ?? null,
                'rak' => $detail['rak'] ?? null,
                'note' => '',
            ]);
        }

        return redirect()->route('all.materialin')->with('message', 'Material IN berhasil diperbarui.');
    }
 


    public function exportMaterialIn(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        return Excel::download(new MaterialInWithDetailsExport($startDate, $endDate), 'material_in_' . $startDate . '_to_' . $endDate . '.xlsx');
    }



    public function Getmaterialinoriginal($original_no){

        $qrcode = MaterialInDetail::with('uStockSumOri')->where('original_no', $original_no)->first();
        if ($qrcode) {
            return response()->json([
                'material_in_detail' => $qrcode,
                'stock' => $qrcode->uStockSumOri
            ]);
        } else {
            return response()->json(['message' => 'No data found'], 404);
        }

         

    }


    public function Storematerialinsp(Request $request)
        {

              // Generate material_in_no
        $yearMonth = date('Ym');
        $lastMaterialIn = MaterialIn::where('material_in_no', 'like', $yearMonth.'%')
            ->orderBy('material_in_no', 'desc')
            ->first();

        if ($lastMaterialIn) {
            $lastNumber = substr($lastMaterialIn->material_in_no, -6);
            $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '000001';
        }

        $materialInNo =  'IN'.$yearMonth . $newNumber;   

                    // Validate the request data
                    $request->validate([
                        'supplier_id' => 'required|integer',
                        'no_sj' => 'required|string',
                        'recived_by' => 'required|string',
                        'location' => 'required|string',
                        'courier' => 'required|string',
                        'remark' => 'nullable|string',
                        'details' => 'required|array',
                        'details.*.po_id' => 'required',
                        'details.*.item_id' => 'required',
              
                        'details.*.qty' => 'required',
                    ]);


            $materialIn = MaterialIn::create([
                'material_in_no' =>$materialInNo,
                    
                'supplier_id' => $request->supplier_id,
                'no_sj' => $request->no_sj,
                'received_by' => $request->recived_by,
                'location' =>$request->location,    
                'courier' => $request->courier,
                'remark' => $request->remark,
                'user_id' =>Auth::id(),
            ]);

            // Simpan detail pembelian ke purchase_order_details
            foreach ($request->details as $detail) {
                MaterialInDetail::create([
                    'material_in_id' => $materialIn->id,
                    'purchase_order_id' => $detail['po_id'],
                    'original_no' => '',
                    'item_id' =>$detail['item_id'],
                    'color' =>$detail['color'],
                    'size' =>$detail['size'],
                    'qty' => $detail['qty'],
                   
                ]);



                PurchaseOrderDetail::where('purchase_order_id', $detail['po_id'])
                ->where('item_id', $detail['item_id'])
                ->where('color', $detail['color'])
                ->where('size', $detail['size'])
                ->update(['remark' => 'recived']);



            
            
                }

                return redirect()->route('all.materialin')->with('success', 'Purchase Order berhasil diperbarui.');
                // return response()->json(['success' => true, 'message' => 'Data saved successfully']);
        }



       

}
