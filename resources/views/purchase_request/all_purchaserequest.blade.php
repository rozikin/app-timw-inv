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
                                    <h6 class="card-title text-center">PURCHASE REQUEST All</h6>
                                </div>
                              
                            </div>

                        </div>
                            
                        <div class="row">
                            <div class="col">
                             

                                <div class="btn-group" role="group" aria-label="Basic example">
                                 
                                
                                    <a href="{{ route('add.purchaserequest') }}"  class="btn btn-primary"><i class="feather-10" data-feather="plus"></i>  &nbsp;Add</a>
                                    {{-- <a href="{{ route('export.cbd') }}"  class="btn btn-primary"><i class="feather-10" data-feather="download"></i>  &nbsp;Export</a> --}}
                                  </div>
                            </div>
                        </div>


                        <div class="table-responsive mt-2">

                            <table id="cbdTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Request No</th>
                                        <th>CBD</th>
                                        <th>tipe</th>
                                        <th>Mo</th>
                                        <th>STYLE</th>
                                        <th>destination</th>
                                        <th>applicant</th>
                                        <th>Item_name</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Unit</th>
                                        <th>Total</th>
                                        <th>Remark</th>
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
                ajax: "{{ route('get.purchaserequest') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'purchase_request_no', name: 'purchase_request_no' },
                    { data: 'order_no', name: 'order_no' },
                    { data: 'tipe', name: 'tipe', render: function(data, type, row) {
                        if (data == 'Urgent') {
                            return '<span class="badge bg-danger">'+data+'</span>';
                        }
                        return data;
                    }},
                    { data: 'mo', name: 'mo' },
                    { data: 'style', name: 'style' },
                    { data: 'destination', name: 'destination' },
                    { data: 'applicant', name: 'applicant' },
                    { data: 'item_name', name: 'item_name' },
                    { data: 'color', name: 'color' },
                    { data: 'size', name: 'size' },
                    { data: 'unit', name: 'unit' },
                    { data: 'total', name: 'total' },
                    { data: 'remark', name: 'remark' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            
            });






            $('body').on('click', '.deletePurchaserequest', function() {



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
                            url: "/delete/purchaserequest/" + request_id,
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
