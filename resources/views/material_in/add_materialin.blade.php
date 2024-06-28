@extends('admin.admin_dashboard')

@section('admin')
    <div class="page-content mt-5">

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
 
                            <div class="container">

                                <form action="{{ route('store.purchaseorder') }}" method="POST">
                                    @csrf
                                    <!-- Purchase Request Fields -->

                                    <div class="text-center">
                                        Material IN Information
                                    </div>
                                    <hr>

                                    {{-- <p class="text-danger">Add Purchase Request for CBD ID: {{ $cbdno }}</p> --}}

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
                                                    {{-- <input type="hidden" class="form-control" id="cbd_id"
                                                        name="cbd_id" value="{{ $cbdId }}" readonly> --}}
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="supplier_id" class="col-sm-4 col-form-label">Supplier</label>
                                                <div class="col-sm-5">

                                             
                                                    <input type="hidden" class="form-control supplier_id" id="supplier_id"
                                                        name="supplier_id">
                                                    <input type="text" class="form-control supplier_name"
                                                        id="supplier_name" name="supplier_name">
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="request_in_house" class="col-sm-4 col-form-label">Request In
                                                    House</label>
                                                <div class="col-sm-5">
                                                    <input type="date" class="form-control" id="request_in_house"
                                                        name="request_in_house" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="terms" class="col-sm-4 col-form-label">Terms</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="terms"
                                                        name="terms">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="payment" class="col-sm-4 col-form-label">Payment</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="payment"
                                                        name="payment">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="shipment_mode" class="col-sm-4 col-form-label">Shipment
                                                    Mode</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="shipment_mode"
                                                        name="shipment_mode">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group row">
                                                <label for="allocation" class="col-sm-4 col-form-label">Allocation</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="allocation"
                                                        name="allocation" required >
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="applicant" class="col-sm-4 col-form-label">Applicant</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="applicant"
                                                        name="applicant" required >
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="approval" class="col-sm-4 col-form-label">Approval</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="approval"
                                                        name="approval" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="quotation_no" class="col-sm-4 col-form-label">Quotition
                                                    No</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="quotation_no"
                                                        name="quotation_no">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="delivery_at" class="col-sm-4 col-form-label">Delivery
                                                    At</label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="delivery_at"
                                                        name="delivery_at">
                                                </div>
                                            </div>

                                       

                                        </div>

                                    </div>
                                    <hr>

                                    <!-- Purchase Request Details Fields -->
                                    <div class="row">

                                        <div>
                                            <h6>PO Items</h6>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Item ID</th>
                                                        <th>Item Code</th>
                                                        <th>Item Name</th>
                                                        <th>Color</th>
                                                        <th>Size</th>
                                                        <th>Quantity</th>
                                                        <th>Total</th>
                                                        <th>Unit</th>
                                                        <th>Remark</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                 
                                                </tbody>
                                            </table>
                                        </div>

                                        <hr>

                                        <div class="">
                                            {{-- <button type="button" class="btn btn-secondary btn-sm mb-2"
                                                id="add-detail">Add
                                                Detail</button> --}}

                                            <h6>IN Items</h6>
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
                                                        <th>PIRCE</th>
                                                        <th>TOTAL PRICE</th>

                                                        <th>Remark</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="details-container">
                                                    {{-- <tr class="detail-row">
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
                                                    </tr> --}}
                                                </tbody>
                                            </table>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <textarea class="form-control" rows="3" name="note1" id="note1" placeholder="Enter notes here..">  </textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- accepted payments column -->
                                        <div class="col-7 mt-5">
                                            <div class="form-group row">
                                                <label for="style" class="col-sm-1 col-form-label">Notes</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" rows="3" name="note2" id="note2" placeholder="Enter notes here.."></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="rule" class="col-sm-1 col-form-label">Rule</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" rows="3" name="rule" id="rule" placeholder="Enter rule here.."></textarea>
                                                </div>
                                            </div>

                                        </div>
                                       
                                        <!-- /.col -->
                                        <div class="col-5">

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm">
                                                    <tr>
                                                        <th style="width:50%">Sub total :</th>
                                                        <td>
                                                            <input type="text"
                                                                class="form-control col-sm-6 form-control-xs"
                                                                name="sub_total" id="sub_total" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Rounding :</th>
                                                        <td>
                                                            <input type="text"
                                                                class="form-control col-sm-6 form-control-xs"
                                                                name="rounding" id="rounding" value="0">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Discount :</th>
                                                        <td>
                                                            <input type="text"
                                                                class="form-control col-sm-6 form-control-xs"
                                                                name="discount" id="discount" value="0">
                                                        </td>
                                                    </tr>
                                                    <tr>

                                                        <th>
                                                            Tax (%) :
                                                        </th>

                                                        <td id="tax_result">
                                                            <input type="text"
                                                                class="form-control col-sm-6  form-control-xs"
                                                                name="tax" id="tax">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                        </th>
                                                        <td id="tax_result">
                                                            <input type="text"
                                                                class="form-control col-sm-6 form-control-xs"
                                                                name="tax_end" id="tax_end" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Grand Total:</th>
                                                        <td id="grand_total">
                                                            <input type="text"
                                                                class="form-control col-sm-6 form-control-xs"
                                                                name="grand_total_end" id="grand_total_end" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th id="purchase_amount">Purchase Amount:</th>
                                                        <td>
                                                            <input type="text"
                                                                class="form-control col-sm-6 form-control-xs"
                                                                name="purchase_amount_end" id="purchase_amount_end"
                                                                readonly>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.col -->
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

    <div class="modal fade" id="supplierModal" tabindex="-1" role="dialog" aria-labelledby="supplierModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierModalLabel">Select Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <table id="suppliers-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Supplier Name</th>
                                <th>Supplier Address</th>
                               
                                <th>Person</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Supplier rows will be appended here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function() {
            $('.alert-danger').fadeOut('slow');
        }, 6000);


        $(document).ready(function() {
            var table;


            let selectedSupplierInput;
            let selectedInput;
            let detailIndex = 1;

            function calculateTotalPrice(row) {
                const qty = parseFloat(row.find('.qty').val()) || 0;
                const price = parseFloat(row.find('.price').val()) || 0;
                const totalPrice = qty * price;
                row.find('.total_price').val(totalPrice.toLocaleString(
                    'en-US')); // Format to localized string without decimals
            }



            function addEventListeners(row) {
                row.find('.qty, .price').on('input', function() {
                    calculateTotalPrice(row);

                    console.log('coba');
                });
            }


            $(document).on('click', '.select-detail', function() {
                var detail = $(this).data('detail'); // Retrieve data-detail directly

                // Ensure detail is a string representation of JSON, parse it
                try {

                    console.log('Parsed Detail:', detail);

                    const detailx = JSON.stringify(detail);

                    detail = JSON.parse(detailx);

                    // Verify the parsed data


                    // Construct the new row with parsed JSON data
                    var newRowx = `<tr class="detail-row">
                    <td><input type="text" class="form-control item-id" name="details[${detail.id}][item_id]" value="${detail.item_id}" required></td>
                    <td><input type="text" class="form-control item-code" name="details[${detail.id}][item_code]" value="${detail.item.item_code}" required></td>
                    <td><input type="text" class="form-control item-name" name="details[${detail.id}][item_name]" value="${detail.item.item_name}" required></td>
                    <td><input type="text" class="form-control color-id" name="details[${detail.id}][color]" value="${detail.color ? detail.color : ''}"></td>
                    <td><input type="text" class="form-control size-id" name="details[${detail.id}][size]" value="${detail.size ? detail.size : ''}"></td>
                    <td><input type="text" class="form-control unit" name="details[${detail.id}][unit]" value="${detail.item.unit.unit_code}"></td>
                    <td><input type="text" class="form-control qty" name="details[${detail.id}][qty]" value="${detail.qty}" required></td>
                    <td><input type="text" class="form-control price" name="details[${detail.id}][price]" value="0" pattern="[0-9]+"></td>
                
                    <td><input type="text" class="form-control total_price" name="details[${detail.id}][total_price]" pattern="[0-9]+" readonly></td>
                   <td>
                        <input type="text" class="form-control" name="details[${detail.id}][remark]"
                            value="${detail.remark ? detail.remark : ''}">
                    </td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-detail">Remove</button></td>
                </tr>`;

                    $('#details-container').append(newRowx); // Append new row to details container
                    const newlyAddedRow = $('#details-container').find('.detail-row').last();

                    addEventListeners(newlyAddedRow);




                    // Optionally, you can remove the row from the original table
                    $(this).closest('tr').remove();

                } catch (error) {
                    console.error('Error parsing JSON:', error);
                    // Handle parsing error gracefully
                    return; // Exit function or handle the error as needed
                }
            });




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
                <td><input type="text" class="form-control price" name="details[${detailIndex}][price]"  value="0" pattern="[0-9]+"></td>
                <td><input type="text" class="form-control total_price" name="details[${detailIndex}][total_price]" pattern="[0-9]+" readonly></td>
          
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
            calculateTotalPrice($('.detail-row'));

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

            // Event listener for clicking on supplier_name input
            $(document).on('click', '.supplier_name', function() {
                selectedSupplierInput = $(this); // Store the input element
                $('#supplierModal').modal('show'); // Show the modal
                loadSuppliers(); // Load suppliers data
            });

            function loadSuppliers() {
                $('#supplier-table tbody').empty();

                $.ajax({
                    url: '{{ route('get.supplierglobal') }}', // Sesuaikan dengan route yang benar
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        // Inisialisasi DataTable
                        table = $('#suppliers-table').DataTable({
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
                                    title: "supplier_name",
                                    data: "supplier_name"
                                },
                                {
                                    title: "supplier_address",
                                    data: "supplier_address",
                                    render: function(data, type, row) {
                                        // Batasi panjang teks maksimal menjadi 50 karakter
                                        if (type === 'display' && data.length > 50) {
                                            return data.substr(0, 50) + '...';
                                        }
                                        return data;
                                    }
                                },
                        
                                {
                                    title: "supplier_person",
                                    data: "supplier_person"
                                },
                                {
                                    title: "remark",
                                    data: "remark"
                                }
                            ]
                        });

                        // Tambahkan event handler untuk setiap baris tabel
                        $('#suppliers-table tbody').on('click', 'tr', function() {
                            var supplier = table.row(this).data();
                            selectedSupplierInput.val(supplier
                            .supplier_name); // Mengisi supplier name
                            $('#supplier_id').val(supplier.id); // Mengisi supplier id
                            $('#supplierModal').modal('hide');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }









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
                            $(selectedInput).closest('.detail-row').find('.item-code').val(data
                                .item_code);
                            $(selectedInput).closest('.detail-row').find('.item-name').val(data
                                .item_name);
                            $(selectedInput).closest('.detail-row').find('.unit').val(data.unit
                                .unit_code);
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





            function calculateTotals() {
                let subTotal = 0;

                // Sum up all total_price values from the detail rows
                $('.detail-row').each(function() {
                    const totalPrice = parseFloat($(this).find('.total_price').val().replace(/,/g, '')) ||
                        0;
                    subTotal += totalPrice;
                });

                // Format and update sub_total input
                $('#sub_total').val(subTotal.toLocaleString('en-US'));

                // Get the tax and rounding values
                const rounding = parseFloat($('#rounding').val().replace(/,/g, '')) || 0;
                const tax = parseFloat($('#tax').val().replace(/,/g, '')) || 0;

                // Calculate tax amount
                const taxAmount = (subTotal * (tax / 100));
                $('#tax_end').val(taxAmount.toLocaleString('en-US'));

                // Calculate grand total
                const grandTotal = subTotal + taxAmount + rounding;
                $('#grand_total_end').val(grandTotal.toLocaleString('en-US'));

                // Update purchase amount (if necessary, here it's the same as grand total)
                $('#purchase_amount_end').val(grandTotal.toLocaleString('en-US'));
            }

            // Event listeners for inputs
            $(document).on('input', '.qty, .price, #rounding, #tax', function() {
                calculateTotals();
            });

            // Initial calculation
            calculateTotals();




        });
    </script>
@endsection
