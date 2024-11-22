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
                                    <h6 class="card-title text-center">MATERIAL IN</h6>
                                </div>

                            </div>

                        </div>

                        <!-- Filter Section -->
                        <div class="row mb-3 mt-3">
                            <div class="col-md-5">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" id="startDate" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" id="endDate" class="form-control">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button id="filterBtn" class="btn btn-primary me-2">Filter</button>
                                <button id="exportBtn" class="btn btn-success">Export to Excel</button>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col">

                                <div class="btn-group" role="group" aria-label="Basic example">

                                    <a href="{{ route('add.materialinsp') }}" class="btn btn-success"><i class="feather-10"
                                            data-feather="plus"></i> &nbsp;Add</a>

                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mt-2">

                            <table id="cbdTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>IN No</th>
                                        <th>Supplier</th>
                                        <th>Date in House</th>
                                        <th>NO SJ</th>
                                        <th>Reciver</th>
                                        <th>location</th>
                                        <th>Courier</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Unit</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>qty</th>

                                        <th>MO</th>

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
        $(document).ready(function() {
            // Handle Export Excel Button
            $('#exportBtn').click(function() {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();

                // Validasi input tanggal
                if (!startDate || !endDate) {
                    Swal.fire({
                        title: 'Invalid Input',
                        text: 'Please select both start and end dates before exporting.',
                        icon: 'warning',
                        timer: 2000,
                        timerProgressBar: true
                    });
                    return;
                }

                if (new Date(startDate) > new Date(endDate)) {
                    Swal.fire({
                        title: 'Invalid Date Range',
                        text: 'End date must be greater than or equal to the start date.',
                        icon: 'error',
                        timer: 2000,
                        timerProgressBar: true
                    });
                    return;
                }

                // Redirect to export URL with query parameters
                window.location.href =
                    `{{ route('export.materialin') }}?startDate=${startDate}&endDate=${endDate}`;
            });
        });


        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Variabel untuk DataTable
            var table;

            // Fungsi untuk inisialisasi DataTable
            function initDataTable(startDate, endDate) {
                if ($.fn.DataTable.isDataTable('#cbdTable')) {
                    table.destroy();
                }
                // Inisialisasi DataTable tanpa memuat data awal
                table = $('#cbdTable').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false, // Nonaktifkan pencarian bawaan DataTable
                    ajax: {
                        url: "{{ route('get.materialin') }}",
                        data: function(d) {
                            d.startDate = startDate; // Ambil Start Date
                            d.endDate = endDate; // Ambil End Date
                        },
                        dataSrc: function(json) {
                            if (json.data.length === 0) {
                                Swal.fire({
                                    title: 'No Data',
                                    text: 'No records found for the selected dates.',
                                    icon: 'info',
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                            }
                            return json.data;
                        }
                    },

                    columns: [{
                            "data": "DT_RowIndex",
                            "name": "DT_RowIndex",
                            "searchable": false
                        },
                        {
                            "data": "material_in_no",
                            "name": "material_in_no"
                        },
                        {
                            "data": "supplier_name",
                            "name": "supplier_name",
                            "render": function(data, type, row) {
                                if (data.length > 20) {
                                    return data.substring(0, 20) + "...";
                                } else {
                                    return data;
                                }
                            }
                        }, {
                            "data": "created_at",
                            "name": "date_in_house",
                            "render": function(data, type, row) {
                                var date = new Date(data);
                                var year = date.getFullYear();
                                var month = ("0" + (date.getMonth() + 1)).slice(-
                                    2); // Adding leading zero
                                var day = ("0" + date.getDate()).slice(-2); // Adding leading zero
                                return year + '-' + month + '-' + day;
                            }
                        },
                        {
                            "data": "no_sj",
                            "name": "no_sj"
                        },
                        {
                            "data": "received_by",
                            "name": "received_by"
                        },
                        {
                            "data": "location",
                            "name": "location"
                        },
                        {
                            "data": "courier",
                            "name": "courier"
                        },
                        {
                            "data": "item_code",
                            "name": "item_code",
                            "render": function(data, type, row) {
                                if (Array.isArray(row.item_details) && row.item_details.length >
                                    0) {
                                    var items = '<ul>';
                                    row.item_details.forEach(function(item) {
                                        items += '<li>' + item.item_code + '</li>';
                                    });
                                    items += '</ul>';
                                    return items;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            "data": "item_name",
                            "name": "item_name",
                            "render": function(data, type, row) {
                                if (Array.isArray(row.item_details) && row.item_details.length >
                                    0) {
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
                            "data": "unit",
                            "name": "unit",
                            "render": function(data, type, row) {
                                if (Array.isArray(row.item_details) && row.item_details.length >
                                    0) {
                                    var sizes = '<ul>';
                                    row.item_details.forEach(function(item) {
                                        sizes += '<li>' + (item.unit_code ? item.unit_code :
                                            '-') + '</li>';
                                    });
                                    sizes += '</ul>';
                                    return sizes;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            "data": "color",
                            "name": "color_name",
                            "render": function(data, type, row) {
                                if (Array.isArray(row.item_details) && row.item_details.length >
                                    0) {
                                    var colors = '<ul>';
                                    row.item_details.forEach(function(item) {
                                        colors += (item.color ? '<li>' + item.color +
                                            '</li>' :
                                            '');
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
                                if (Array.isArray(row.item_details) && row.item_details.length >
                                    0) {
                                    var sizes = '<ul>';
                                    row.item_details.forEach(function(item) {
                                        sizes += (item.size ? '<li>' + item.size + '</li>' :
                                            '');
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
                                if (Array.isArray(row.item_details) && row.item_details.length >
                                    0) {
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
                            "data": "mo",
                            "name": "mo",
                            "render": function(data, type, row) {
                                if (Array.isArray(row.item_details) && row.item_details.length >
                                    0) {
                                    var items = '<ul>';
                                    row.item_details.forEach(function(item) {
                                        items += (item.mo ? '<li>' + item.mo + '</li>' :
                                            '');
                                    });
                                    items += '</ul>';
                                    return items;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            "data": "remark",
                            "name": "remark",
                            "render": function(data, type, row) {
                                if (Array.isArray(row.item_details) && row.item_details.length >
                                    0) {
                                    var items = '<ul>';
                                    row.item_details.forEach(function(item) {
                                        items += (item.remark ? '<li>' + item.remark +
                                            '</li>' :
                                            '');
                                    });
                                    items += '</ul>';
                                    return items;
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
            }

            // Filter data berdasarkan tanggal
            $('#filterBtn').click(function() {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();

                // Validasi input tanggal
                if (!startDate || !endDate) {
                    Swal.fire({
                        title: 'Invalid Input',
                        text: 'Please select both start and end dates.',
                        icon: 'warning',
                        timer: 2000,
                        timerProgressBar: true
                    });
                    return;
                }

                if (new Date(startDate) > new Date(endDate)) {
                    Swal.fire({
                        title: 'Invalid Date Range',
                        text: 'End date must be greater than or equal to the start date.',
                        icon: 'error',
                        timer: 2000,
                        timerProgressBar: true
                    });
                    return;
                }

                initDataTable(startDate, endDate);
            });


            // Mengecek jika ada filter yang sudah dipilih saat halaman dimuat
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            if (startDate && endDate) {
                // Jika tanggal sudah terisi, inisialisasi DataTable langsung
                initDataTable(startDate, endDate);
            }
        });
    </script>
@endsection
