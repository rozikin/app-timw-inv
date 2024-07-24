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

class MaterialInController extends Controller
{
    public function Allmaterialin()
    {
        return view('material_in.all_materialin
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


    public function Getmaterialin(Request $request)
    {
        if ($request->ajax()) {
            $data = MaterialIn::with(['details', 'details.item.unit', 'details.purchaseOrder.purchaseRequest', 'supplier'])
                ->get();
    
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('supplier_name', function($row) {
                    return $row->supplier->supplier_name ?? '';
                })
                ->addColumn('item_details', function($row) {
                    $itemDetails = [];
                    foreach ($row->details as $detail) {
                        $itemName = strlen($detail->item->item_name) > 25 ? 
                                    substr($detail->item->item_name, 0, 25) . '...' : 
                                    $detail->item->item_name;
    
                        $itemDetails[] = [
                            'item_code' => $detail->item->item_code,
                            'item_name' =>  $itemName,
                            'unit_code' => $detail->item->unit->unit_code ?? '',
                            'color' => $detail->color ?? '',
                            'size' => $detail->size ?? '',
                            'batch' =>  $detail->batch?? '',
                            'no_roll' =>  $detail->no_roll?? '',
                            'mo' =>  $detail->purchaseOrder->purchaseRequest->mo?? '',
                            'qty' => $detail->qty,
                            'remark' => $detail->remark ?? '',
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
                ->rawColumns(['purchase_order_no', 'action'])
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
            // 'details' => 'required|array',
            // 'details.*.po_id' => 'required',
            // 'details.*.item_id' => 'required', 
  
            // 'details.*.qty' => 'required',
        ]);

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
       
               $materialInNo = 'IN'.$yearMonth . $newNumber;    




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
                'purchase_order_id' => $request->po_id,
                'item_id' =>$request->item_id,
                'color' =>$request->color,
                'size' =>$request->size,
                'qty' => $detail['qty'],
                'batch' => $detail['batch'],
                'no_roll' => $detail['no_roll'],
                'gw' => $detail['gw'],
                'nw' => $detail['nw'],
                'width' => $detail['width'],
                'gramasi' => $detail['gramasi'],
                'mo' => $detail['mo'],
                'style' => $detail['style'],
                'rak_id' => $detail['rak_id'],
                'remark' => $detail['remark'],
                'satus' => $detail['satus']
            ]);



            PurchaseOrderDetail::where('purchase_order_id', $request->po_id)
            ->where('item_id', $request->item_id)
            ->where('color', $request->color)
            ->where('size', $request->size)
            ->update(['remark' => 'recived']);



          
        
           }
           return redirect()->route('all.materialin')->with('success', 'Purchase Order berhasil diperbarui.');
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
                        'success' => true,
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
                        'success' => true,
                        'message' => 'Material In dan detail berhasil dihapus!',
                    ]);
                }
            } catch (\Exception $e) {
         
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus: ' . $e->getMessage(),
                ]);
            }
        }


        public function EditMaterialin($id){

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

}

