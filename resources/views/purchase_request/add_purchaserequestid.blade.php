@extends('admin.admin_dashboard')

@section('admin')
    <div class="page-content mt-5">

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div class="row">

                            <div class="container">

                                <form action="{{ route('store.purchaserequest') }}" method="POST">
                                    @csrf
                                    <!-- Purchase Request Fields -->

                                    <div class="text-center">
                                        Purchase Request Information
                                    </div>
                                    <hr>

                                  <p class="text-danger">Add Purchase Request for CBD ID: {{ $cbdno }}</p>

                                    @if ($errors->any())
                                        <div class="alert alert-danger" role="alert">
                                            @foreach ($errors->all() as $error)
                                                <h3> {{ $error }} </h3>
                                            @endforeach

                                        </div>
                                    @endif

                                    <div class="row">

                                        <div class="col-md-6">

                                            <div class="form-group row">
                                                {{-- <label for="cbd_id" class="col-sm-4 col-form-label">CBD ID</label> --}}
                                                <div class="col-sm-5">
                                                    <input type="hidden" class="form-control" id="cbd_id"
                                                        name="cbd_id" value="{{ $cbdId }}" readonly>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="tipe" class="col-sm-4 col-form-label">Type:</label>
                                                <div class="col-sm-5">
                                                    <select class="js-example-basic-single form-select" id="tipe"
                                                        name="tipe">
                                                        <option value="Urgent">Urgent</option>
                                                        <option value="Non Urgent">Non Urgent</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="mo" class="col-sm-4 col-form-label">MO</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="mo"
                                                        name="mo">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="style" class="col-sm-4 col-form-label">Style</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="style"
                                                        name="style">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="destination" class="col-sm-4 col-form-label">Destination</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="destination"
                                                        name="destination">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group row">
                                                <label for="department" class="col-sm-4 col-form-label">Department</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="department"
                                                        name="department" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="applicant" class="col-sm-4 col-form-label">Applicant</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="applicant"
                                                        name="applicant" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="time_line" class="col-sm-4 col-form-label">Timeline</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="time_line"
                                                        name="time_line">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="remark1" class="col-sm-4 col-form-label">Remark</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="remark1"
                                                        name="remark1">
                                                </div>
                                            </div>

                                            <!-- Table displaying the pivot size/color/quantity data -->
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Size \ Color</th>
                                                            @foreach ($colors as $color)
                                                                <th>{{ $color }}</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($sizes as $size)
                                                            <tr>
                                                                <td>{{ $size }}</td>
                                                                @foreach ($colors as $color)
                                                                    <td>
                                                                       {{ $qtyData[$size][$color] ?? '' }}
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>

                                    </div>
                                    <hr>

                                    <!-- Purchase Request Details Fields -->
                                    <div class="row">

                                        <div class="">
                                            <button type="button" class="btn btn-secondary btn-sm mb-2" id="add-detail">Add
                                                Detail</button>
                                                <table class="table table-bordered" id="details-table">
                                                    <thead>
                                                        <tr>
                                                            {{-- <th>Item ID</th> --}}
                                                            <th>item ID</th>
                                                            <th>item Code</th>
                                                            <th>Name</th>
                                                            <th>Color</th>
                                                            <th>Size</th>
                                                            <th>UNIT</th>
                                                            <th>QTY</th>
                                                            <th>Consumption</th>
                                                            <th>Allowance</th>
                                                            <th>Total</th>
    
                                                            <th>Remark</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="details-container">
                                                        <tr class="detail-row">
                                                            <td><input type="text" class="form-control item-id"
                                                                    name="details[0][item_id]" required></td>
                                                            <td>
                                                           <input type="text" class="form-control item-code"
                                                                name="details[0][item_code]" required>
                                                            </td>
                                                            <td><input type="text" class="form-control item-name"
                                                                name="details[0][item_name]" required>
                                                            </td>
                                                            <td><input type="text" class="form-control color-id"
                                                                    name="details[0][color]"></td>
                                                            <td><input type="text" class="form-control size-id"
                                                                    name="details[0][size]"></td>
                                                            <td><input type="text" class="form-control unit"
                                                                    name="details[0][unit]"></td>
                                                            <td><input type="text" class="form-control qty"
                                                                    name="details[0][qty]" required value="0"
                                                                    pattern="[0-9]+"></td>
                                                            <td><input type="text" class="form-control consumption"
                                                                    name="details[0][consumption]" value="0"
                                                                    pattern="[0-9]+"></td>
                                                            <td><input type="text" class="form-control allowance"
                                                                    name="details[0][allowance]" value="0"
                                                                    pattern="[0-9]+"></td>
                                                            <td><input type="text" class="form-control total"
                                                                    name="details[0][total]" readonly></td>
    
                                                            <td><input type="text" class="form-control"
                                                                    name="details[0][remark]"></td>
                                                            <td><button type="button"
                                                                    class="btn btn-danger btn-sm remove-detail">Remove</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalLabel">Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table id="items-table" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Unit</th>
                                <th>Category</th>
                                <th>Description</th>

                            </tr>
                        </thead>
                        <tbody id="data_item">
                            <!-- Employee data will be dynamically populated here -->
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal for Color Selection -->
    <div class="modal fade" id="colorModal" tabindex="-1" aria-labelledby="colorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="colorModalLabel">Select Color</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover" id="colors-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Color Code</th>
                                <th>Color Name</th>

                            </tr>
                        </thead>
                        <tbody>
                            <!-- Colors will be loaded here by Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Size Selection -->
    <div class="modal fade" id="sizeModal" tabindex="-1" aria-labelledby="sizeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sizeModalLabel">Select Size</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-hover" id="sizes-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Size Code</th>
                                <th>Size Name</th>

                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sizes will be loaded here by Ajax -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function() {
            $('.alert-danger').fadeOut('slow');
        }, 1000);


        $(document).ready(function() {
            var table;



            let selectedInput;
            let detailIndex = 1;


            function calculateTotal(row) {
                const qty = parseFloat(row.find('.qty').val()) || 0;
                const consumption = parseFloat(row.find('.consumption').val()) || 0;
                const allowance = parseFloat(row.find('.allowance').val()) || 0;
                const total = (qty * consumption) + allowance;
                row.find('.total').val(total.toFixed(0)); // Format to 2 decimal places
            }

            // Function to add event listeners for calculation
            function addEventListeners(row) {
                row.find('.qty, .consumption, .allowance').on('input', function() {
                    calculateTotal(row);
                });
            }


            $('#add-detail').on('click', function() {
                const newDetailRow = `
            <tr class="detail-row">
                <td><input type="text" class="form-control item-id" name="details[${detailIndex}][item_id]" required></td>
                <td><input type="text" class="form-control item-code" name="details[${detailIndex}][item_code]" required></td>
                <td><input type="text" class="form-control item-name" name="details[${detailIndex}][item_name]" required></td>
                <td><input type="text" class="form-control color-id" name="details[${detailIndex}][color]"></td>
                <td><input type="text" class="form-control size-id" name="details[${detailIndex}][size]"></td>
                <td><input type="text" class="form-control unit" name="details[${detailIndex}][unit]"></td>
                <td><input type="text" class="form-control qty" name="details[${detailIndex}][qty]" required  value="0" pattern="[0-9]+"></td>
                <td><input type="text" class="form-control consumption" name="details[${detailIndex}][consumption]"  value="0" pattern="[0-9]+"></td>
                <td><input type="text" class="form-control allowance" name="details[${detailIndex}][allowance]"  value="0" pattern="[0-9]+"></td>
                <td><input type="text" class="form-control total" name="details[${detailIndex}][total]" readonly></td>
          
                <td><input type="text" class="form-control" name="details[${detailIndex}][remark]"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-detail">Remove</button></td>
            </tr>`;
                $('#details-container').append(newDetailRow);
                const newRow = $('#details-container').find('.detail-row').last();
                addEventListeners(newRow); // Add event listeners to the new row
                detailIndex++;
            });

            // Remove detail row
            $(document).on('click', '.remove-detail', function() {
                $(this).closest('tr').remove();
            });

            // Calculate total for the initial row
            calculateTotal($('.detail-row'));

            // Add event listeners for calculation on all detail rows
            $('.detail-row').each(function() {
                addEventListeners($(this));
            });



            $(document).on('click', '.item-id', function() {
                selectedInput = $(this); // Store the input element
                $('#itemModal').modal('show');
                loadItems(); // Call function to load items via Ajax


            });

            $(document).on('click', '.color-id', function() {
                selectedInput = $(this);
                $('#colorModal').modal('show');
                loadColors();
            });

            $(document).on('click', '.size-id', function() {
                selectedInput = $(this);
                $('#sizeModal').modal('show');
                loadSizes();
            });


            function loadItems() {
                $('#items-table tbody').empty();

                $.ajax({
                    url: '{{ route('get.itemglobal') }}', // Sesuaikan dengan route yang benar
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        // Inisialisasi DataTable
                        table = $('#items-table').DataTable({
                            paging: true,
                            searching: true,
                            ordering: true,
                            destroy: true,
                            info: true,
                            data: data,
                            columns: [{
                                    title: "ID",
                                    data: "id"
                                },
                                {
                                    title: "Item Code",
                                    data: "item_code"
                                },
                                {
                                    title: "Item Name",
                                    data: "item_name"
                                },
                                {
                                    title: "Unit",
                                    data: "unit.unit_code" // Sesuaikan dengan struktur data JSON dari relasi
                                },
                                {
                                    title: "category",
                                    data: "category.name" // Sesuaikan dengan struktur data JSON dari relasi
                                },
                                {
                                    title: "Description",
                                    data: "description"
                                }
                            ]
                        });

                        // Tambahkan event handler untuk setiap baris tabel
                        $('#items-table tbody').on('click', 'tr', function() {
                            var data = table.row(this).data();
                            selectedInput.val(data.id); // Mengambil ID item
                            $(selectedInput).closest('.detail-row').find('.item-code').val(data.item_code);
                            $(selectedInput).closest('.detail-row').find('.item-name').val(data.item_name);
                            $(selectedInput).closest('.detail-row').find('.unit').val(data.unit.unit_code);
                            $('#itemModal').modal('hide');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            function loadColors() {
                $('#colors-table tbody').empty();

                $.ajax({
                    url: '{{ route('get.colorglobal') }}',
                    method: 'GET',
                    success: function(data) {
                        // Inisialisasi DataTable
                        table = $('#colors-table').DataTable({
                            paging: true,
                            searching: true,
                            ordering: true,
                            destroy: true,
                            info: true,
                            data: data,
                            columns: [{
                                    title: "ID",
                                    data: "id"
                                },
                                {
                                    title: "Color Code",
                                    data: "color_code"
                                },
                                {
                                    title: "Color Name",
                                    data: "color_name"
                                },

                            ]
                        });

                        // Tambahkan event handler untuk setiap baris tabel
                        $('#colors-table tbody').on('click', 'tr', function() {
                            var data = table.row(this).data();
                            selectedInput.val(data.color_code); // Mengambil ID item
                            $('#colorModal').modal('hide');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }

                });
            }

            function loadSizes() {
                $('#sizes-table tbody').empty();

                $.ajax({
                    url: '{{ route('get.sizeglobal') }}',
                    method: 'GET',
                    success: function(data) {
                        // Inisialisasi DataTable
                        table = $('#sizes-table').DataTable({
                            paging: true,
                            searching: true,
                            ordering: true,
                            destroy: true,
                            info: true,
                            data: data,
                            columns: [{
                                    title: "ID",
                                    data: "id"
                                },
                                {
                                    title: "Size Code",
                                    data: "size_code"
                                },
                                {
                                    title: "Size Name",
                                    data: "size_name"
                                },

                            ]
                        });

                        // Tambahkan event handler untuk setiap baris tabel
                        $('#sizes-table tbody').on('click', 'tr', function() {
                            var data = table.row(this).data();
                            selectedInput.val(data.size_code); // Mengambil ID item
                            $('#sizeModal').modal('hide');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }

                });
            }






        });
    </script>
@endsection
