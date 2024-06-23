<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="{{ asset('css/styles_table_res.css') }}" rel="stylesheet" />
    <style>
        @page {
            size: A5;
            margin: 0;
            border: 10px solid black;
        }

        body {
            font-family: Arial, sans-serif;
            width: 21cm;
            height: 14.8cm;
            margin: 0;
            padding: 2px;
        }

        .header {
            text-align: center;
        }

        .displaycustom {
            display: flex;
            align-items: stretch;
            justify-content: center;
            height: auto;
            width: 97%;

            padding: 0;
            margin-top: 0;
        }

        .bordercustom {
            border: 1px solid black;
        }

        .logo {
            width: 90px;
            height: 40px;
            margin-top: 8px;
        }

        .text {
            text-align: center;
            font-size: 12px;
            margin-top: 5px;
            margin-left: 5px;
            padding: 0;
        }

        @media print {
            body {
                width: auto;
                height: auto;
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        .customer {
            display: flex;
            justify-content: space-between;
            padding: 5px 10px;
            font-size: 12px;
        }

        .left {
            flex: 1;
            /* Menggunakan flex-grow untuk menjaga teks di kiri */
        }

        .right {
            flex-shrink: 0;
            /* Menghindari tanggal untuk menyusut */
        }

        /* Table full border */
        .table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .table th,
        .table td {
            border: 0.5px solid black;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .table tfoot td {
            border: none !important;
        }

        .notes {
            font-size: 12px;
            margin-top: 10px;
            padding: 0 10px;
            text-align: justify;
            font-weight: bold;
        }

        .note-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 5px;
        }

        .note-number {
            width: 20px;
            flex-shrink: 0;
        }

        .note-text {
            flex-grow: 1;
            text-align: justify;
        }

        .nb {
            font-weight: bold;
            margin-bottom: 5px;
            margin-right: 5px;
        }
    </style>

    <script type="text/javascript">
        function printAndClose() {
            window.print();
            window.onafterprint = function() {
                window.close();
            };
        }
        window.onload = printAndClose;
    </script>
</head>

<body>
    <div class="container bordercustom">

        <div class="container displaycustom bordercustom">
            <img src="{{ asset('img/logodinastysetya.png') }}" class="logo" alt="Logo Dinasty Setia Media">
            <div class="text">
                <b>Dinasty Setia Media</b><br>
                Jl. Raya Randuagung No.246 Kec. Singosari, Kab. Malang<br>
                Jawa Timur 65153
            </div>
        </div>
        <br>
        <h6 class="header"><b>INVOICE BARANG KELUAR</b></h6>

        <div class="container">
            <div class="customer">
                <div class="left">Ditujukan Kepada : {{ $test }}</div>
                <div class="right">Tanggal: 22 Juni 2024</div>
            </div>

            <table class="table table-sm">
                <thead>
                    <tr>
                        <th class="rata-tengah">No</th>
                        <th class="rata-tengah">Nama Barang</th>
                        <th class="rata-tengah">Qty</th>
                        <th class="rata-tengah">Harga</th>
                        <th class="rata-tengah">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="rata-tengah">1</td>
                        <td>Selang Baja</td>
                        <td class="rata-tengah">1</td>
                        <td class="rata-kanan">10.000</td>
                        <td class="rata-kanan">10.000</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td class="rata-tengah">1</td>
                        <td></td>
                        <td class="rata-kanan">10.000</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="notes">
            <div class="note-item">
                <div class="nb">NB</div>
                <div class="note-number">1.</div>
                <div class="note-text">Harap dijaga kondisi barangnya dan dikembalikan setelah digunakan</div>
            </div>
            <div class="note-item">
                <div class="note-number" style="margin-left: 22px">2.</div>
                <div class="note-text">Kerusakan ditanggung oleh pengguna</div>
            </div>
        </div>

        <div class="customer">
            <div class="left">Dikeluarkan Oleh,</div>
            <div class="right">Diterima Oleh,</div>
        </div>
        <br><br>
        <div class="customer">
            <div class="left">
                <u>
                    <pre>                 </pre>
                </u>
            </div>
            <div class="right">
                <u>
                    <pre>              </pre>
                </u>
            </div>
        </div>

    </div>
</body>

</html>