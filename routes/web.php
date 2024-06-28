<?php


use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Permission;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\ColorController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ItemController;        
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RakController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\MaterialInController;
use App\Http\Controllers\CbdController;
use App\Http\Controllers\ItemVariantController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|                                       
*/


require __DIR__ . '/auth.php';

Route::get('/login', function () {

    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role === 'agent') {
            return redirect()->route('agent.dashboard');
        } elseif (auth()->user()->role === 'user') {
            return redirect()->route('user.dashboard');
        }
    }
    return view('admin.admin_login');
});


Route::get('/admin/login', [AdminController::class, 'store'])->name('admin.login');

//admin group middleware
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route::get('/', [AdminController::class, 'AdminDashboard'])->name('admin.login');
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('admin.update.password');
});

Route::middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/agent/dashboard', [AgentController::class, 'AgentDashboard'])->name('agent.dashboard');
});



Route::middleware(['auth', 'role:admin'])->group(function () {

    //permission all route
    Route::controller(RoleController::class)->group(function () {
        Route::get('/all/permission', 'AllPermission')->name('all.permission')->middleware('can:add.permission');
        Route::get('/add/permission', 'AddPermission')->name('add.permission')->middleware('can:add.permission');
        Route::post('/store/permission', 'StorePermission')->name('store.permission')->middleware('can:add.permission');
        Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission')->middleware('can:add.permission');
        Route::post('/update/permission', 'UpdatePermission')->name('update.permission');
        Route::get('/delete/permission/{id}', 'DeletePermission')->name('delete.permission')->middleware('can:delete.permission');
        Route::get('/import/permission', 'ImportPermission')->name('import.permission');
        Route::get('/export', 'Export')->name('export');
        Route::post('/import', 'Import')->name('import');
    });

    //role all route
    Route::controller(RoleController::class)->group(function () {
        Route::get('/all/roles', 'AllRoles')->name('all.roles')->middleware('can:all.roles');
        Route::get('/add/roles', 'AddRoles')->name('add.roles')->middleware('can:add.roles');
        Route::post('/store/roles', 'StoreRoles')->name('store.roles')->middleware('can:add.roles');
        Route::get('/edit/roles/{id}', 'EditRoles')->name('edit.roles')->middleware('can:edit.roles');
        Route::post('/update/roles', 'UpdateRoles')->name('update.roles');
        Route::get('/delete/roles/{id}', 'DeleteRoles')->name('delete.roles')->middleware('can:delete.roles');


        Route::get('/add/roles/permission', 'AddRolesPermission')->name('add.roles.permission')->middleware('can:add.roles.permission');
        Route::post('/add/permission/store', 'RolesPermissionStore')->name('roles.permission.store');
        Route::get('/all/roles/permission', 'AllRolesPermission')->name('all.roles.permission')->middleware('can:all.roles.permission');
        Route::get('/admin/edit/roles/{id}', 'AdminEditRoles')->name('admin.edit.roles')->middleware('can:admin.edit.roles');
        Route::post('/admin/roles/update/{id}', 'AdminRolesUpdate')->name('admin.roles.update');
        Route::get('/admin/delete/roles/{id}', 'AdminDeleteRoles')->name('admin.delete.roles')->middleware('can:admin.delete.roles');
    });


    //Admin User All Route
    Route::controller(AdminController::class)->group(function () {
        Route::get('/all/admin', 'AllAdmin')->name('all.admin')->middleware('can:all.admin');
        Route::get('/add/admin', 'AddAdmin')->name('add.admin')->middleware('can:add.admin');
        Route::post('/store/admin', 'StoreAdmin')->name('store.admin');
        Route::get('/edit/admin/{id}', 'EditAdmin')->name('edit.admin')->middleware('can:edit.admin');
        Route::post('/update/admin/{id}', 'UpdateAdmin')->name('update.admin');
        Route::get('/delete/admin/{id}', 'DeleteAdmin')->name('delete.admin')->middleware('can:delete.admin');
    });

    

      //color User All Color
      Route::controller(ColorController::class)->group(function () {
        Route::get('/all/color', 'AllColor')->name('all.color')->middleware('can:all.color');
        Route::get('/get/color', 'GetColor')->name('get.color')->middleware('can:all.color');
        Route::get('/get/colorglobal', 'GetColorGlobal')->name('get.colorglobal');
        Route::get('/add/color', 'AddColor')->name('add.color')->middleware('can:add.color');
      
        Route::post('/store/color', 'StoreColor')->name('store.color');
        Route::get('/edit/color/{id}', 'EditColor')->name('edit.color')->middleware('can:edit.color');
        Route::post('/update/color/{id}', 'UpdateColor')->name('update.color');
        Route::get('/delete/color/{id}', 'DeleteColor')->name('delete.color')->middleware('can:delete.color');;
        Route::get('/export/color', 'ExportColor')->name('export.color');
    });
      Route::controller(UnitController::class)->group(function () {
        Route::get('/all/unit', 'Allunit')->name('all.unit')->middleware('can:all.unit');
        Route::get('/get/unit', 'Getunit')->name('get.unit')->middleware('can:all.unit');
        Route::get('/get/unitglobal', 'GetunitGlobal')->name('get.unitglobal');
        Route::get('/add/unit', 'Addunit')->name('add.unit')->middleware('can:add.unit');
      
        Route::post('/store/unit', 'Storeunit')->name('store.unit')->middleware('can:add.unit');
        Route::get('/edit/unit/{id}', 'Editunit')->name('edit.unit')->middleware('can:edit.unit');
        Route::post('/update/unit/{id}', 'Updateunit')->name('update.unit');
        Route::get('/delete/unit/{id}', 'Deleteunit')->name('delete.unit')->middleware('can:delete.unit');
        Route::get('/export/unit', 'Exportunit')->name('export.unit');

    });

    Route::controller(CategorieController::class)->group(function () {
        Route::get('/all/category', 'Allcategory')->name('all.category')->middleware('can:all.category');
        Route::get('/get/category', 'Getcategory')->name('get.category')->middleware('can:get.category');
        Route::get('/get/categoryglobal', 'GetCategoryGlobal')->name('get.categoryglobal');
        Route::get('/add/category', 'Addcategory')->name('add.category')->middleware('can:add.category');
      
        Route::post('/store/category', 'Storecategory')->name('store.category')->middleware('can:add.category');
        Route::get('/edit/category/{id}', 'Editcategory')->name('edit.category')->middleware('can:edit.category');
        Route::post('/update/category/{id}', 'Updatecategory')->name('update.category')->middleware('can:edit.category');
        Route::get('/delete/category/{id}', 'Deletecategory')->name('delete.category')->middleware('can:delete.category');
        Route::get('/export/category', 'Exportcategory')->name('export.category');
    });

    Route::controller(SizeController::class)->group(function () {
        Route::get('/all/size', 'Allsize')->name('all.size')->middleware('can:all.size');
        Route::get('/get/size', 'Getsize')->name('get.size')->middleware('can:all.size');
        Route::get('/get/sizeglobal', 'GetsizeGlobal')->name('get.sizeglobal');
        Route::get('/add/size', 'Addsize')->name('add.size')->middleware('can:add.size');
      
        Route::post('/store/size', 'Storesize')->name('store.size');
        Route::get('/edit/size/{id}', 'Editsize')->name('edit.size')->middleware('can:edit.size');
        Route::post('/update/size/{id}', 'Updatesize')->name('update.size');
        Route::get('/delete/size/{id}', 'Deletesize')->name('delete.size')->middleware('can:delete.size');
        Route::get('/export/size', 'Exportsize')->name('export.size');
    });
    Route::controller(SupplierController::class)->group(function () {
        Route::get('/all/supplier', 'Allsupplier')->name('all.supplier')->middleware('can:all.supplier');
        Route::get('/get/supplier', 'Getsupplier')->name('get.supplier')->middleware('can:all.supplier');
        Route::get('/get/supplierglobal', 'GetsupplierGlobal')->name('get.supplierglobal');
        Route::get('/add/supplier', 'Addsupplier')->name('add.supplier')->middleware('can:add.supplier');
      
        Route::post('/store/supplier', 'Storesupplier')->name('store.supplier')->middleware('can:add.supplier');
        Route::get('/edit/supplier/{id}', 'Editsupplier')->name('edit.supplier')->middleware('can:edit.supplier');
        Route::post('/update/supplier/{id}', 'Updatesupplier')->name('update.supplier');
        Route::get('/delete/supplier/{id}', 'Deletesupplier')->name('delete.supplier');
        Route::get('/export/supplier', 'Exportsupplier')->name('export.supplier');
    });


     

    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/all/employee', 'AllEmployee')->name('all.employee')->middleware('can:all.employee');
        Route::get('/add/employee', 'AddEmployee')->name('add.employee')->middleware('can:add.employee');
        Route::post('/store/employee', 'StoreEmployee')->name('store.employee');
        Route::get('/edit/employee/{id}', 'EditEmployee')->name('edit.employee')->middleware('can:edit.employee');
        Route::post('/update/employee/{id}', 'UpdateEmployee')->name('update.employee')->middleware('can:edit.employee');
        Route::get('/delete/employee/{id}', 'DeleteEmployee')->name('delete.employee')->middleware('can:delete.employee');
        Route::get('/export/employee', 'ExportEmployee')->name('export.employee')->middleware('can:export.employee');;
        Route::get('/get/employee', 'Getemployee')->name('get.employee')->middleware('can:all.employee');
        Route::get('/get/employeecount', 'GetEmployeeCount')->name('get.employeecount');
        Route::get('/get/posisiemployee', 'GetPosisi')->name('get.posisiemployee');
        Route::post('/check/employee', 'CheckEmployee')->name('check.employee');
        Route::get('/print/employee', 'PrintEmployee')->name('print.employee')->middleware('can:export.employee');
        Route::post('/pdf/employee', 'exportPDF')->name('pdf.employee')->middleware('can:export.employee');
        Route::post('/import/employee', 'ImportEmployee')->name('import.employee')->middleware('can:import.employee');
        Route::get('/import/employees', 'Importemployees')->name('import.employees')->middleware('can:import.employee');
      
    });


    
    Route::controller(ItemController::class)->group(function () {
        Route::get('/all/item', 'Allitem')->name('all.item')->middleware('can:all.item');
        Route::get('/add/item', 'Additem')->name('add.item')->middleware('can:add.item');
        Route::post('/store/item', 'Storeitem')->name('store.item');
        Route::get('/edit/item/{id}', 'Edititem')->name('edit.item')->middleware('can:edit.item');
        Route::post('/update/item/{id}', 'Updateitem')->name('update.item');
        Route::get('/delete/item/{id}', 'DeleteItem')->name('delete.item')->middleware('can:delete.item');
        Route::get('/export/item', 'Exportitem')->name('export.item')->middleware('can:export.item');
        Route::get('/get/item', 'Getitem')->name('get.item')->middleware('can:all.item');
        Route::get('/get/itemglobal', 'Getitemglobal')->name('get.itemglobal')->middleware('can:get.itemglobal');
        Route::get('/get/itemcount', 'GetitemCount')->name('get.itemcount');
        Route::get('/get/posisi', 'GetPosisi')->name('get.posisi');
        Route::post('/check/item', 'Checkitem')->name('check.item');
        Route::get('/print/item', 'Printitem')->name('print.item')->middleware('can:export.item');
        Route::post('/pdf/item', 'exportPDF')->name('pdf.item')->middleware('can:export.item');
        Route::post('/import/item', 'Importitem')->name('import.item')->middleware('can:import.item');
        Route::get('/import/items', 'Importitems')->name('import.items')->middleware('can:import.item');
      
    });

   
    Route::controller(RakController::class)->group(function () {
        Route::get('/all/rak', 'Allrak')->name('all.rak')->middleware('can:all.rak');
        Route::get('/add/rak', 'Addrak')->name('add.rak')->middleware('can:add.rak');
        Route::post('/store/rak', 'Storerak')->name('store.rak')->middleware('can:add.rak');
        Route::get('/edit/rak/{id}', 'Editrak')->name('edit.rak')->middleware('can:edit.rak');
        Route::post('/update/rak/{id}', 'Updaterak')->name('update.rak')->middleware('can:edit.rak');
        Route::get('/delete/rak/{id}', 'Deleterak')->name('delete.rak')->middleware('can:delete.rak');
        Route::get('/export/rak', 'Exportrak')->name('export.rak')->middleware('can:export.rak');;
        Route::get('/get/rak', 'Getrak')->name('get.rak')->middleware('can:all.rak');
        Route::get('/get/rakcount', 'GetrakCount')->name('get.rakcount');
        Route::get('/get/posisirak', 'GetPosisi')->name('get.posisirak');
        Route::post('/check/rak', 'Checkrak')->name('check.rak');
        Route::get('/print/rak', 'Printrak')->name('print.rak')->middleware('can:export.rak');
        Route::post('/pdf/rak', 'exportPDF')->name('pdf.rak')->middleware('can:export.rak');
        Route::post('/import/rak', 'Importrak')->name('import.rak')->middleware('can:import.rak');
        Route::get('/import/raks', 'Importraks')->name('import.raks')->middleware('can:import.rak');
      
    });

    
    Route::controller(CbdController::class)->group(function () {
        Route::get('/all/cbd', 'Allcbd')->name('all.cbd')->middleware('can:all.cbd');
        Route::get('/add/cbd', 'Addcbd')->name('add.cbd')->middleware('can:add.cbd');
        Route::post('/store/cbd', 'Storecbd')->name('store.cbd');
        Route::get('/edit/cbd/{id}', 'Editcbd')->name('edit.cbd')->middleware('can:edit.cbd');
        Route::post('/update/cbd/{id}', 'Updatecbd')->name('update.cbd')->middleware('can:edit.cbd');
        Route::get('/delete/cbd/{id}', 'Deletecbd')->name('delete.cbd')->middleware('can:delete.cbd');
        Route::get('/export/cbd', 'Exportcbd')->name('export.cbd')->middleware('can:export.cbd');;
        Route::get('/get/cbd', 'Getcbd')->name('get.cbd')->middleware('can:all.cbd');
        Route::get('/get/cbdglobal', 'Getcbdglobal')->name('get.cbdglobal');
        Route::get('/get/cbdcount', 'GetcbdCount')->name('get.cbdcount');
        Route::get('/get/posisicbd', 'GetPosisi')->name('get.posisicbd');
        Route::post('/check/cbd', 'Checkcbd')->name('check.cbd');
        Route::get('/print/cbd', 'Printcbd')->name('print.cbd')->middleware('can:export.cbd');
        Route::post('/pdf/cbd', 'exportPDF')->name('pdf.cbd')->middleware('can:export.cbd');
        Route::post('/import/cbd', 'Importcbd')->name('import.cbd')->middleware('can:import.cbd');
        Route::get('/import/cbds', 'Importcbds')->name('import.cbds')->middleware('can:import.cbd');
      
    });
    

    Route::controller(PurchaseRequestController::class)->group(function () {
        Route::get('/all/purchaserequest', 'Allpurchaserequest')->name('all.purchaserequest')->middleware('can:all.purchaserequest');
        Route::get('/add/purchaserequest', 'Addpurchaserequest')->name('add.purchaserequest')->middleware('can:add.purchaserequest');
        Route::get('/add/purchaserequestid/{id}', 'Addpurchaserequestid')->name('add.purchaserequestid')->middleware('can:add.purchaserequest');
        Route::post('/store/purchaserequest', 'Storepurchaserequest')->name('store.purchaserequest');
        Route::get('/edit/purchaserequest/{id}', 'Editpurchaserequest')->name('edit.purchaserequest')->middleware('can:edit.purchaserequest');
        Route::post('/update/purchaserequest/{id}', 'Updatepurchaserequest')->name('update.purchaserequest')->middleware('can:edit.purchaserequest');
        Route::get('/delete/purchaserequest/{id}', 'Deletepurchaserequest')->name('delete.purchaserequest')->middleware('can:delete.purchaserequest');
        Route::get('/export/purchaserequest', 'Exportpurchaserequest')->name('export.purchaserequest')->middleware('can:export.purchaserequest');;
        Route::get('/get/purchaserequest', 'Getpurchaserequest')->name('get.purchaserequest')->middleware('can:all.purchaserequest');
        Route::get('/get/purchaserequestsp', 'Getpurchaserequestsp')->name('get.purchaserequestsp')->middleware('can:all.purchaserequest');
        Route::get('/get/purchaserequestitems', 'Getpurchaserequestitems')->name('get.purchaserequestitems')->middleware('can:all.purchaserequest');
        Route::get('/get/purchaserequestcount', 'GetpurchaserequestCount')->name('get.purchaserequestcount');
        Route::get('/print/purchaserequest', 'Printpurchaserequest')->name('print.purchaserequest')->middleware('can:export.purchaserequest');
        Route::get('/pdf/purchaserequest/{id}', 'exportPDF')->name('pdf.purchaserequest')->middleware('can:export.purchaserequest');
        Route::post('/import/purchaserequest', 'Importpurchaserequest')->name('import.purchaserequest')->middleware('can:import.purchaserequest');
        Route::get('/import/purchaserequests', 'Importpurchaserequests')->name('import.purchaserequests')->middleware('can:import.purchaserequest');
      
    });

    Route::controller(PurchaseOrderController::class)->group(function () {
        Route::get('/all/purchaseorder', 'Allpurchaseorder')->name('all.purchaseorder')->middleware('can:all.purchaseorder');
        Route::get('/add/purchaseorder', 'Addpurchaseorder')->name('add.purchaseorder')->middleware('can:add.purchaseorder');
        Route::get('/add/purchaseorderid/{id}', 'Addpurchaseorderid')->name('add.purchaseorderid')->middleware('can:add.purchaseorder');
        Route::post('/store/purchaseorder', 'Storepurchaseorder')->name('store.purchaseorder');
        Route::get('/edit/purchaseorder/{id}', 'Editpurchaseorder')->name('edit.purchaseorder')->middleware('can:edit.purchaseorder');
        Route::post('/update/purchaseorder/{id}', 'Updatepurchaseorder')->name('update.purchaseorder')->middleware('can:edit.purchaseorder');
        Route::get('/delete/purchaseorder/{id}', 'Deletepurchaseorder')->name('delete.purchaseorder')->middleware('can:delete.purchaseorder');
        Route::get('/export/purchaseorder', 'Exportpurchaseorder')->name('export.purchaseorder')->middleware('can:export.purchaseorder');;
        Route::get('/get/purchaseorder', 'Getpurchaseorder')->name('get.purchaseorder')->middleware('can:all.purchaseorder');
        Route::get('/get/purchaseordercount', 'GetpurchaseorderCount')->name('get.purchaseordercount');
        Route::get('/print/purchaseorder', 'Printpurchaseorder')->name('print.purchaseorder')->middleware('can:export.purchaseorder');
        Route::get('/pdf/purchaseorder/{id}', 'exportPDF')->name('pdf.purchaseorder')->middleware('can:export.purchaseorder');
        Route::post('/import/purchaseorder', 'Importpurchaseorder')->name('import.purchaseorder')->middleware('can:import.purchaseorder');
        Route::get('/import/purchaseorders', 'Importpurchaseorders')->name('import.purchaseorders')->middleware('can:import.purchaseorder');
    
    });

    Route::controller(MaterialInController::class)->group(function () {
        Route::get('/all/materialin', 'Allmaterialin')->name('all.materialin')->middleware('can:all.materialin');
        Route::get('/add/materialin', 'Addmaterialin')->name('add.materialin')->middleware('can:add.materialin');
        Route::get('/add/materialin', 'Addmaterialin')->name('add.materialin')->middleware('can:add.materialin');
        Route::post('/store/materialin', 'Storematerialin')->name('store.materialin');
        Route::get('/edit/materialin/{id}', 'Editmaterialin')->name('edit.materialin')->middleware('can:edit.materialin');
        Route::post('/update/materialin/{id}', 'Updatematerialin')->name('update.materialin')->middleware('can:edit.materialin');
        Route::get('/delete/materialin/{id}', 'Deletematerialin')->name('delete.materialin')->middleware('can:delete.materialin');
        Route::get('/export/materialin', 'Exportmaterialin')->name('export.materialin')->middleware('can:export.materialin');;
        Route::get('/get/materialin', 'Getmaterialin')->name('get.materialin')->middleware('can:all.materialin');
        Route::get('/get/materialincount', 'GetmaterialinCount')->name('get.materialincount');
        Route::get('/get/posisimaterialin', 'GetPosisi')->name('get.posisimaterialin');
        Route::post('/check/materialin', 'Checkmaterialin')->name('check.materialin');
        Route::get('/print/materialin', 'Printmaterialin')->name('print.materialin')->middleware('can:export.materialin');
        Route::get('/pdf/materialin/{id}', 'exportPDF')->name('pdf.materialin')->middleware('can:export.materialin');
        Route::post('/import/materialin', 'Importmaterialin')->name('import.materialin')->middleware('can:import.materialin');
        Route::get('/import/materialins', 'Importmaterialins')->name('import.materialins')->middleware('can:import.materialin');
    
    });




    
   

}); 

//end admin middleware


