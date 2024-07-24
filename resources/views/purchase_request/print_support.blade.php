<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Arial, sans-serif';
            margin: 0;
            padding: 0;
        }

        .header,
        .footer {
            width: 100%;
            position: fixed;
            top: 0;
            /* Letakkan header di bagian atas */
            left: 0;
            z-index: 999;
            /* Pastikan header muncul di atas konten */
        }

        .header {
            margin: 0;
            padding: 10px 20px;
            box-sizing: border-box;
            border-bottom: 1px solid #000;
        }

        .footer {
            bottom: 0;
            font-size: 12px;
            margin: 0;
        }

        .content {
            margin-top: 120px;
            /* Atur margin top untuk konten */
            margin-bottom: 40px;
            position: relative;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid black;
            padding: 2px;
            text-align: left;
            font-size: 10px;
        }

        .table th {
            background-color: #f2f2f2;
            padding: 3px;
        }

        .table-no-border td {
            border: none;
            font-size: 10px;
        }

        .footer-table td {
            font-size: 10px;
        }

        .table-request {
            margin-left: 0;
            width: auto;
        }

        .table-request td {
            padding: 3px 8px;
        }

        .table-sign {
            margin-top: 20px;
            /* Atur margin top untuk konten */
            position: relative;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        .header-table td {
            padding: 5px;
        }

        .header-logo {
            text-align: left;
        }

        .header-logo h1 {
            font-style: italic;
            font-weight: bold;
            font-size: 45px;
            /* Ubah ukuran font disini */
            margin: 0;
        }

        .header-logo img {
            width: 80px;
        }

        .header-info {
            text-align: center;
            width: 100%;
        }

        .header-info h1 {
            font-size: 18px;
            margin: 0;
        }

        .header-info p {
            font-size: 12px;
            margin: 2px 0;
        }

        .header-form-no {
            text-align: left;
            font-size: 12px;
            padding: 5px;
            /* Atur padding */
        }

        .header-form-no p {
            border: 1px solid #000;
            /* Tambahkan border pada paragraf */
            padding: 5px;
            /* Atur padding */
            margin: 0;
            /* Hapus margin */
        }

        /* Lebarkan kolom "ITEMS REQUESTED" */
        .table-detail-request th:nth-child(2),
        .table-detail-request td:nth-child(2) {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .wide-column {
            width: 250px; /* Atur lebar kolom */
            max-width: 250px; /* Tetapkan lebar maksimal */
            overflow: hidden;
            text-overflow: ellipsis; /* Tampilkan elipsis jika teks terlalu panjang */
        }
    </style>
</head>

<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    <h1><em><strong>'TIMW'</strong></em></h1>
                </td>
                <td class="header-info">
                    <h1>PT. TI Matsuoka Winner Industry</h1>
                    <p>Jl. Raya Tegalpanas Jimbaran Dsn. Secang Rt 01 Ds. Samban </p>
                    <p>Kec. Bawen, Kab. Semarang, Jawa Tengah</p>
                </td>
                <td class="header-form-no">
                    <p>PCH/FORM/005
                        <br>
                        REV.00
                    </p>

                </td>
            </tr>
        </table>
    </div>
    <div class="content">
        <p style="font-weight: bold; text-decoration: underline; text-align: center;">PURCHASE REQUEST
            ({{ $purchaseRequest->tipe }})</p>
        <table class="table table-no-border table-request" style="width: auto; margin-left: 0;">
            <tr>
                <td>NO</td>
                <td>: {{ $purchaseRequest->purchase_request_no }}</td>
            </tr>
            <tr>
                <td>DATE</td>
                <td>: {{ $purchaseRequest->created_at->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>DEPARTMENT NAME</td>
                <td>: {{ $purchaseRequest->department }}</td>
            </tr>
            <tr>
                <td>APPLICANT NAME</td>
                <td>: {{ $purchaseRequest->applicant }}</td>
            </tr>
            <tr>
                <td>TIME LINE (USING DATE)</td>
                <td>: {{ $purchaseRequest->time_line }}</td>
            </tr>
        </table>

        <br>


        <table class="table detail-request table-detail-request">
            <thead>
                <tr>
                    <th>NO</th>
                    <th  class="wide-column">ITEMS REQUESTED</th>
                
                    <th>UNIT</th>
                    <th>WAREHOUSE STOCK</th>
                    <th>QTY REQUESTED</th>
                    <th>REMARKS</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseRequest->detailrequest as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ substr($detail->item->item_name, 0, 200) }}</td>
                        <!-- Batasi item_name hingga 50 karakter -->
                     
                        <td>{{ $detail->item->unit->unit_code }}</td>
                        <td>{{ $detail->warehouse_stock ?? 0 }}</td>
                        <td>{{ $detail->qty }}</td>
                        <td>{{ $detail->remark }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table-no-border table-sign" style="width: 100%;">
            <tr>
                <td style="text-align: center;">Purchasing</td>
                <td style="text-align: center;">Director</td>
                <td style="text-align: center;">Head Department</td>
                <td style="text-align: center;">Applicant</td>
            </tr>
            <tr>
                <td style="text-align: center; height: 80px;">_________________</td>
                <td style="text-align: center; height: 80px;">_________________</td>
                <td style="text-align: center; height: 80px;">_________________</td>
                <td style="text-align: center; height: 80px;">{{ $purchaseRequest->applicant }}</td>
            </tr>
        </table>
    </div>
    <div class="footer">
        <!-- Footer content can go here -->
    </div>
</body>

</html>
