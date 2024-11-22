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
                                    <h6 class="card-title text-center">MATERIAL OUT DETAIL</h6>
                                </div>

                            </div>

                        </div>
                        <hr>

                        <div class="row">
                            <div class="col">

                                <div class="btn-group" role="group" aria-label="Basic example">

                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 px-6">
                            <div class="col-md-4 d-flex align-items-center">
                                <label for="startDate" class="col-sm-3">FROM</label>
                                <input type="date" id="startDate" class="form-control" placeholder="Start Date">
                            </div>
                            <div class="col-md-4 d-flex align-items-center">
                                <label for="endDate" class="col-sm-3">TO</label>
                                <input type="date" id="endDate" class="form-control" placeholder="End Date">
                            </div>
                            <div class="col-md-4">
                                <button id="filterBtn" class="btn btn-primary">Filter</button>
                            </div>
                        </div>

                        <div class="table-responsive mt-2" id="cbdTablex" style="display: none;">

                            <table id="cbdTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>OUT NO</th>
                                        <th>Date</th>
                                        <th>ALLOCATION</th>
                                        <th>ORIGINAL NO</th>
                                        <th>SUPPLIER</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Unit</th>
                                        <th>Col Code</th>
                                        <th>Color Name</th>
                                        <th>Size</th>

                                        <th>Batch</th>
                                        <th>No Roll</th>

                                        <th>MO</th>
                                        <th>GR WEIGHT</th>
                                        <th>RAK</th>
                                        <th>qty</th>

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
                ajax: {
                    url: "{{ route('get.materialoutdetail') }}",
                    data: function(d) {
                        d.startDate = $('#startDate').val();
                        d.endDate = $('#endDate').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'out_no',
                        name: 'out_no'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'allocation',
                        name: 'allocation'
                    },
                    {
                        data: 'original_no',
                        name: 'original_no'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
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
                        data: 'unit',
                        name: 'unit'
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
                        data: 'size',
                        name: 'size'
                    },
                    {
                        data: 'batch',
                        name: 'batch'
                    },
                    {
                        data: 'no_roll',
                        name: 'no_roll'
                    },

                    {
                        data: 'mo',
                        name: 'mo'
                    },
                    {
                        data: 'gross_weight',
                        name: 'gross_weight'
                    },
                    {
                        data: 'rak',
                        name: 'rak'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                ],
                "initComplete": function(settings, json) {
                    // Hide the table until the filter button is clicked
                    $('#cbdTablex').hide();
                }
            });

            $('#filterBtn').click(function() {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();

                if (startDate && endDate) {
                    table.ajax.reload();
                    $('#cbdTablex').show();
                } else {
                    alert('Please select both start date and end date.');
                }
            });


        });
    </script>
@endsection