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

                        <div class="row mb-3 mt-3">
                            <div class="col-md-5">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" id="startDate" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" id="endDate" class="form-control">
                            </div>
                            <div class="col-md-3 d-flex align-items-end ">
                                <button id="filterBtn" class="btn btn-primary me-2">Filter</button>
                                <button id="exportExcelBtn" class="btn btn-success">
                                    Export to Excel
                                </button>
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
                                        <th>MO /Style</th>
                                        <th>Supplier</th>
                                        <th>Date in House</th>
                                        {{-- <th>applicant</th> --}}
                                        <th>Item_code</th>
                                        <th>Item_name</th>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Unit</th>
                                        <th>qty</th>
                                        <th>Price</th>
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
        $(document).ready(function() {
            // Handle Export Excel Button
            $('#exportExcelBtn').click(function() {
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
                    `{{ route('export.purchaseorder') }}?startDate=${startDate}&endDate=${endDate}`;
            });
        });
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table;

            // Fungsi untuk inisialisasi DataTable
            function initDataTable(startDate, endDate) {

                if ($.fn.DataTable.isDataTable('#cbdTable')) {
                    table.destroy();
                }




                table = $('#cbdTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('get.purchaseorder') }}",
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
                            "data": "purchase_order_no",
                            "name": "purchase_order_no"
                        },
                        {
                            "data": "purchase_request_no",
                            "name": "purchase_request_no"
                        },
                        {
                            "data": "mo",
                            "name": "mo"
                        },
                        {
                            title: "supplier_name",
                            data: "supplier_name",
                            render: function(data, type, row) {
                                // Batasi panjang teks maksimal menjadi 25 karakter
                                if (type === 'display' && data.length > 25) {
                                    return data.substr(0, 25) + '...';
                                }
                                return data;
                            }
                        },
                        {
                            "data": "date_in_house",
                            "name": "date_in_house"
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
                            "data": "color",
                            "name": "color",
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
                            "data": "unit",
                            "name": "unit",
                            "render": function(data, type, row) {
                                if (Array.isArray(row.item_details) && row.item_details.length >
                                    0) {
                                    var sizes = '<ul>';
                                    row.item_details.forEach(function(item) {
                                        sizes += (item.unit_code ? '<li>' + item.unit_code +
                                            '</li>' : '');
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
                            "data": "price",
                            "name": "price",
                            "render": function(data, type, row) {
                                if (Array.isArray(row.item_details) && row.item_details.length >
                                    0) {
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
                                if (Array.isArray(row.item_details) && row.item_details.length >
                                    0) {
                                    var statuss = '<ul>';
                                    row.item_details.forEach(function(item) {
                                        statuss += (item.status ?
                                            '<li><span class="text-danger">' +

                                            item.status +
                                            '</span></li>' : '');
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
                initDataTable(startDate, endDate);
            }







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
