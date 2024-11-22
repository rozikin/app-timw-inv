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
                                    <nav class="page-breadcrumb">
                                        <ol class="breadcrumb">

                                        </ol>
                                    </nav>
                                </div>

                                <div class="col">
                                    <h6 class="card-title text-center">STOCK RELAX BY ORIGINAL NO</h6>
                                </div>
                                <div class="col">
                                    <h6 class="card-title text-center"></h6>
                                </div>
                            </div>

                        </div>

                        <div class="table-responsive">

                            <table id="colorTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Original no</th>
                                        <th>RCV DATE</th>
                                        <th>SPLR</th>

                                        <th>code</th>
                                        <th>Name</th>
                                        <th>size</th>
                                        <th>cl code</th>
                                        <th>color name</th>
                                        <th>cat</th>
                                        <th>unit</th>
                                        <th>PO</th>
                                        <th>batch</th>
                                        <th>roll</th>
                                        <th>GW</th>
                                        <th>NW</th>
                                        <th>B. Width</th>
                                        <th>B. Grm</th>
                                        <th>Stock</th>

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

            $('#modal-create').on('shown.bs.modal', function() {
                $(this).find('[autofocus]').focus();
            });




            var table = $('#colorTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get.stockrelaxdetail') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'original_no',
                        name: 'original_no'
                    },
                    {
                        data: 'received_date',
                        name: 'received_date'
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier_name'
                    },
                    {
                        data: 'item_code',
                        name: 'item_code'
                    },
                    {
                        data: 'item_name',
                        name: 'item_name'
                    },
                    {
                        data: 'size',
                        name: 'size'
                    },
                    {
                        data: 'color_code',
                        name: 'color_code'
                    },
                    {
                        data: 'color_name',
                        name: 'color_name'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'unit',
                        name: 'unit'
                    },
                    {
                        data: 'po',
                        name: 'po'
                    },
                    {
                        data: 'batch',
                        name: 'batch'
                    },
                    {
                        data: 'roll',
                        name: 'roll'
                    },
                    {
                        data: 'gross_weight',
                        name: 'gross_weight'
                    },
                    {
                        data: 'net_weight',
                        name: 'net_weight'
                    },
                    {
                        data: 'basic_width',
                        name: 'basic_width'
                    },
                    {
                        data: 'basic_grm',
                        name: 'basic_grm'
                    },

                    {
                        data: 'stock',
                        name: 'stock'
                    },







                ]
            });







        });
    </script>
@endsection
