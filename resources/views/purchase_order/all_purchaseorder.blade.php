@extends('admin.admin_dashboard')

@section('admin')
    <div class="page-content mt-5">


        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div>
                            <div class="row">
                            

                                <div class="col">
                                    <h6 class="card-title text-center">PURCHASE ORDER All</h6>
                                </div>
                              
                            </div>

                        </div>
                            
                        <div class="row">
                            <div class="col">
                             

                                <div class="btn-group" role="group" aria-label="Basic example">
                                 
                                
                                    {{-- <a href="{{ route('add.purchaseorder') }}"  class="btn btn-primary"><i class="feather-10" data-feather="plus"></i>  &nbsp;Add</a> --}}
                                    {{-- <a href="{{ route('export.cbd') }}"  class="btn btn-primary"><i class="feather-10" data-feather="download"></i>  &nbsp;Export</a> --}}
                                  </div>
                            </div>
                        </div>


                        <div class="table-responsive mt-2">

                            <table id="cbdTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Purchase No</th>
                                        <th>Request No</th>
                                        <th>Supplier</th>
                                        <th>Date in House</th>
                                        <th>delivery_at</th>
                                        <th>applicant</th>
                                        <th>Item_name</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Unit</th>
                                        <th>qty</th>
                                        <th>price</th>
                                    
                                        <th>Status</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <br />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>











    <script>
        $(function() {

            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });

        

            var table = $('#cbdTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get.purchaseorder') }}",
                columns: [
                    { "data": "DT_RowIndex", "name": "DT_RowIndex", "searchable": false },
                    { "data": "purchase_order_no", "name": "purchase_order_no" },
                    { "data": "purchase_request_no", "name": "purchase_request_no" },
                    { "data": "supplier_name", "name": "supplier_name" },
                    { "data": "date_in_house", "name": "date_in_house" },
                    { "data": "delivery_at", "name": "delivery_at" },
                    { "data": "applicant", "name": "applicant" },
                    { 
                        "data": "item_name", 
                        "name": "item_name",
                        "render": function(data, type, row) {
                            if (Array.isArray(row.item_details) && row.item_details.length > 0) {
                                var items = '<ul>';
                                row.item_details.forEach(function(item) {
                                    items += '<li>' + item.item_name + '</li>';
                                });
                                items += '</ul>';
                                return items;
                            } else {
                                return '';
                            }
                        }
                    },
                    { 
                        "data": "color", 
                        "name": "color",
                        "render": function(data, type, row) {
                            if (Array.isArray(row.item_details) && row.item_details.length > 0) {
                                var colors = '<ul>';
                                row.item_details.forEach(function(item) {
                                    colors += '<li>' + (item.color ? item.color : '-') + '</li>';
                                });
                                colors += '</ul>';
                                return colors;
                            } else {
                                return '';
                            }
                        }
                    },
                    { 
                        "data": "size", 
                        "name": "size",
                        "render": function(data, type, row) {
                            if (Array.isArray(row.item_details) && row.item_details.length > 0) {
                                var sizes = '<ul>';
                                row.item_details.forEach(function(item) {
                                    sizes += '<li>' + (item.size ? item.size : '-') + '</li>';
                                });
                                sizes += '</ul>';
                                return sizes;
                            } else {
                                return '';
                            }
                        }
                    },
                    { 
                        "data": "unit", 
                        "name": "unit",
                        "render": function(data, type, row) {
                            if (Array.isArray(row.item_details) && row.item_details.length > 0) {
                                var sizes = '<ul>';
                                row.item_details.forEach(function(item) {
                                    sizes += '<li>' + (item.unit_code ? item.unit_code : '-') + '</li>';
                                });
                                sizes += '</ul>';
                                return sizes;
                            } else {
                                return '';
                            }
                        }
                    },
                    { 
                        "data": "qty", 
                        "name": "qty",
                        "render": function(data, type, row) {
                            if (Array.isArray(row.item_details) && row.item_details.length > 0) {
                                var qtys = '<ul>';
                                row.item_details.forEach(function(item) {
                                    qtys += '<li>' + item.qty + '</li>';
                                });
                                qtys += '</ul>';
                                return qtys;
                            } else {
                                return '';
                            }
                        }
                    },
                    { 
                        "data": "price", 
                        "name": "price",
                        "render": function(data, type, row) {
                            if (Array.isArray(row.item_details) && row.item_details.length > 0) {
                                var prices = '<ul>';
                                row.item_details.forEach(function(item) {
                                    prices += '<li>' + item.price + '</li>';
                                });
                                prices += '</ul>';
                                return prices;
                            } else {
                                return '';
                            } 
                        }
                    },
                   
                    { 
                        "data": "status", 
                        "name": "status",
                        "render": function(data, type, row) {
                            if (Array.isArray(row.item_details) && row.item_details.length > 0) {
                                var statuss = '<ul>';
                                row.item_details.forEach(function(item) {
                                    statuss += '<li>' + (item.status ? item.status : '') + '</li>';
                                });
                                statuss += '</ul>';
                                return statuss;
                            } else {
                                return '';
                            }
                        }
                    },
                    { 
                        "data": "action", 
                        "name": "action", 
                        "orderable": false,
                        "searchable": false 
                    }
                ],
            
            });






            $('body').on('click', '.deletePurchaseorder', function() {



                var request_id = $(this).data("id");

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger me-2'
                    },
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            type: "GET",
                            url: "/delete/purchaseorder/" + request_id,
                            success: function(data) {
                                table.ajax.reload(null, false);

                                swalWithBootstrapButtons.fire({
                                    title: 'Deleted!',
                                    text: 'Your file has been deleted.',
                                    icon: 'success',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    willClose: () => {
                                        // Optional: Add any additional actions you want to perform after the alert closes
                                    }
                                })
                            },
                            error: function(data) {
                                console.log('Error:', data);

                                swalWithBootstrapButtons.fire({
                                    title: 'Cancelled!',
                                    text: `'There is relation data'.${data.responseJSON.message}`,
                                    icon: 'error',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    willClose: () => {
                                        // Optional: Add any additional actions you want to perform after the alert closes
                                    }
                                })



                            }
                        });


                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire({
                            title: 'Cancelled!',
                            text: 'Your file is safe :)',
                            icon: 'error',
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                // Optional: Add any additional actions you want to perform after the alert closes
                            }
                        })
                    }
                })

            });




        });
    </script>
@endsection
