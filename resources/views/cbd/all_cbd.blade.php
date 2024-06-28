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
                                    <h6 class="card-title text-center">CBD All</h6>
                                </div>
                              
                            </div>

                        </div>

                        <div class="row">
                            <div class="col">
                             

                                <div class="btn-group" role="group" aria-label="Basic example">
                                 
                            
                                    <a href="{{ route('import.cbds') }}"  class="btn btn-primary"><i class="feather-10" data-feather="upload"></i>  &nbsp;Import</a>
                                    <a href="{{ route('export.cbd') }}"  class="btn btn-primary"><i class="feather-10" data-feather="download"></i>  &nbsp;Export</a>
                                  </div>
                            </div>
                        </div>


                        <div class="table-responsive mt-2">

                            <table id="cbdTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Order No</th>
                                        <th>SPLR MAT</th>
                                        <th>Item</th>
                                        <th>Sample / Style</th>
                                        <th>Color Code</th>
                                        <th>Color</th>
                                        <th>Size Code</th>
                                        <th>Size</th>
                                        <th>Qty</th>
                                        <th>Remark</th>
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
                ajax: "{{ route('get.cbd') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'order_no', name: 'order_no' },
                    { data: 'supplier_raw_material_code', name: 'supplier_raw_material_code' },
                    { data: 'item', name: 'item' },
                    { data: 'sample_code', name: 'sample_code' },
                    { data: 'color_code', name: 'color_code' },
                    { data: 'color', name: 'color' },
                    { data: 'size_code', name: 'size_code' },
                    { data: 'size', name: 'size' },
                    { data: 'qty', name: 'qty' },
                    { data: 'remark', name: 'remark' },
                    { data: 'action', name: 'action', orderable: true, searchable: true },
                ],
                
            });






            $('body').on('click', '.deleteCbd', function() {



                var cbd_id = $(this).data("id");

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
                            url: "/delete/cbd/" + cbd_id,
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
