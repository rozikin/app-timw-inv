<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use App\Models\Cbd;


class PurchaseRequestController extends Controller
{
    public function Allpurchaserequest()
    {
        return view('purchase_request.all_purchaserequest');
    }

    public function Addpurchaserequest(){
        return view('purchase_request.add_purchaserequest');
    }

    public function Addpurchaserequestid($id){
     // Fetch the CBD by ID along with its details
     $cbd = Cbd::with('details')->findOrFail($id);

     // Extract size, color, and quantity data from CBD details
     $sizes = [];
     $colors = [];
     $qtyData = [];
 
     foreach ($cbd->details as $detail) {
         $sizes[$detail->size] = $detail->size;
         $colors[$detail->color] = $detail->color;
         $qtyData[$detail->size][$detail->color] = $detail->qty;
     }
 
     // Sort sizes and colors alphabetically
     sort($sizes);
     sort($colors);

     $cbdId = $cbd->id;
     $cbdno = $cbd->order_no;

        return view('purchase_request.add_purchaserequestid',compact('sizes', 'colors', 'qtyData', 'cbdId','cbdno'));
    }



    
    public function Getpurchaserequest(Request $request)
    {
        if ($request->ajax()) {
            $data = PurchaseRequest::with('detailrequest.item')->get();
    
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<div class="d-flex align-items-center justify-content-between flex-wrap">
                              <div class="d-flex align-items-center">
                                  <div class="d-flex align-items-center">
                                      <div class="actions dropdown">
                                          <a href="#" data-bs-toggle="dropdown"> ••• </a>
                                          <div class="dropdown-menu" role="menu">
                                           <a href="/edit/purchaserequest/'.$row->id.'" class="dropdown-item text-primary"> &nbsp; Edit</a>
                                           <a href="javascript:void(0)" class="dropdown-item text-danger deletePurchaserequest" data-id="' . $row->id . '"> &nbsp; Delete</a>
                                             
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>';
                })
                ->addColumn('item_name', function ($row) {
                    $details = '<ul>';
                    foreach ($row->detailrequest as $detail) {
                        $details .= '<li>' . $detail->item->item_name . '</li>';
                    }
                    $details .= '</ul>';
                    return $details;
                })
                ->addColumn('color', function ($row) {
                    $details = '<ul>';
                    foreach ($row->detailrequest as $detail) {
                        $details .= '<li>' . $detail->color . '</li>';
                    }
                    $details .= '</ul>';
                    return $details;
                })
                ->addColumn('size', function ($row) {
                    $details = '<ul>';
                    foreach ($row->detailrequest as $detail) {
                        $details .= '<li>' . $detail->size . '</li>';
                    }
                    $details .= '</ul>';
                    return $details;
                })
                ->addColumn('total', function ($row) {
                    $details = '<ul>';
                    foreach ($row->detailrequest as $detail) {
                        $details .= '<li>' . $detail->total . '</li>';
                    }
                    $details .= '</ul>';
                    return $details;
                })
                ->addColumn('remark', function ($row) {
                    return $row->remark;
                })
                ->rawColumns(['action', 'item_name', 'color', 'size', 'total'])
                ->make(true);
        }
    }
    





    public function Storepurchaserequest(Request $request)
    {
        // Validasi input
        $request->validate([
            // 'cbd_id' => 'exists:cbds,id',
            'tipe' => 'required',
            'department' => 'required',
            'applicant' => 'required',
            'details.*.item_id' => 'required',
            'details.*.qty' => 'required|numeric',
            'details.*.total' => 'required|numeric',
        ]);

        // Simpan Purchase Request
        $purchaseRequest = PurchaseRequest::create([
            'cbd_id' => $request->cbd_id,
            'tipe' => $request->tipe,
            'purchase_request_no' => 0,
            'department' => $request->department,
            'applicant' => $request->applicant,
            'mo' => $request->mo,
            'style' => $request->style,
            'destination' => $request->destination,
            'time_line' => $request->time_line,
            'remark' => $request->remark1,
            'status' => '',
            'user_id' => Auth::id(),
        ]);

        // Simpan Detail Purchase Request
        if ($purchaseRequest) {
            foreach ($request->details as $detail) {
                PurchaseRequestDetail::create([
                    'purchase_request_id' => $purchaseRequest->id,
                    'item_id' => $detail['item_id'],
                    'color' => $detail['color'] ?? null,
                    'size' => $detail['size'] ?? null,
                    'qty' => $detail['qty'],
                    'consumtion' => $detail['consumption'] ?? null,
                    'allowance' => $detail['allowance'] ?? null,
                    'total' => $detail['total'],
                    'remark' => $detail['remark'] ?? null,
                    'status' => '',
                ]);
            }
        }

        return redirect()->route('all.purchaserequest')->with('success', 'Purchase Request berhasil disimpan.');
    }


    public function Editpurchaserequest($id)
    {
       // Ambil data Purchase Request berdasarkan ID
    $purchaseRequest = PurchaseRequest::with('detailrequest')->findOrFail($id);

    $sizes = [];
    $colors = [];
    $qtyData = [];
    $cbdId = null;
    $cbdno = null;

    // Periksa apakah ada CBD terkait
    if ($purchaseRequest->cbd_id) {
        // Ambil CBD berdasarkan ID bersama dengan detailnya
        $cbd = Cbd::with('details')->findOrFail($purchaseRequest->cbd_id);

        // Extract size, color, dan quantity data dari detail CBD
        if ($cbd->details) {
            foreach ($cbd->details as $detail) {
                $sizes[$detail->size] = $detail->size;
                $colors[$detail->color] = $detail->color;
                $qtyData[$detail->size][$detail->color] = $detail->qty;
            }
        }

        // Sort sizes dan colors secara alfabetis
        ksort($sizes);
        ksort($colors);

        // Ambil CBD ID dan nomor CBD
        $cbdId = $cbd->id;
        $cbdno = $cbd->order_no;
    }


    
    return view('purchase_request.edit_purchaserequest', compact('purchaseRequest', 'sizes', 'colors', 'qtyData', 'cbdId', 'cbdno'));
 

    }



    public function Updatepurchaserequest(Request $request, $id)
{
    // Validasi input
    $request->validate([
        // Sesuaikan dengan kebutuhan validasi
        'tipe' => 'required',
        'department' => 'required',
        'applicant' => 'required',
        'details.*.item_id' => 'required',
        'details.*.qty' => 'required|numeric',
        'details.*.total' => 'required|numeric',
    ]);

    // Temukan Purchase Request berdasarkan ID
    $purchaseRequest = PurchaseRequest::findOrFail($id);

    // Periksa apakah pengguna memiliki izin untuk mengedit
    // if ($purchaseRequest->user_id !== Auth::id()) {
    //     return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit Purchase Request ini.');
    // }

    // Update Purchase Request
    $purchaseRequest->update([
        'tipe' => $request->tipe,
        'department' => $request->department,
        'applicant' => $request->applicant,
        'mo' => $request->mo,
        'style' => $request->style,
        'destination' => $request->destination,
        'time_line' => $request->time_line,
        'remark' => $request->remark1,
    ]);

    // Hapus semua detail terkait sebelum menambahkan yang baru
    $purchaseRequest->detailrequest()->delete();

    // Simpan Detail Purchase Request
    foreach ($request->details as $detail) {
        PurchaseRequestDetail::create([
            'purchase_request_id' => $purchaseRequest->id,
            'item_id' => $detail['item_id'],
            'color' => $detail['color'] ?? null,
            'size' => $detail['size'] ?? null,
            'qty' => $detail['qty'],
            'consumtion' => $detail['consumption'] ?? null,
            'allowance' => $detail['allowance'] ?? null,
            'total' => $detail['total'],
            'remark' => $detail['remark'] ?? null,
            'status' => '',
        ]);
    }

    return redirect()->route('all.purchaserequest')->with('success', 'Purchase Request berhasil diperbarui.');
}



public function Deletepurchaserequest(Request $request, $id)
{
    if ($request->has('detail_id')) {
        // Hapus detail berdasarkan ID detail
        $detail = PurchaseRequestDetail::findOrFail($request->input('detail_id'));
        $detail->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!.',
        ]);
    } else {
        // Hapus Purchase Request beserta detailnya
        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $purchaseRequest->detailrequest()->delete();
        $purchaseRequest->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil Dihapus!.',
        ]);
    }
}

  
}
