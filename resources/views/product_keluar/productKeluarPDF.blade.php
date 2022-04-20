<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cetak Nota</title>


</head>

<style>
    #table-data {
        border-collapse: collapse;
        padding: 3px;
    }

    #table-data td, #table-data th {
        border: 1px solid black;
    }
</style>

<body>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            {{-- <img src="{{ asset('logo.png') }}" style="width:100%; max-width:300px; height: 50px; objec-fit: contain; display: none;"> --}}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>


        <table border="0" id="table-data" width="100%">
            <tr>
                <td width="70px">Invoice ID</td>
                <td width="">: {{ $product_keluar->id }}</td>
                <td width="30px">Created</td>
                <td>: {{ $product_keluar->tanggal }}</td>
            </tr>

            <tr>
                <td>Telepon</td>
                <td>: {{ $product_keluar->customer->telepon }}</td>
                <td>Alamat</td>
                <td>: {{ $product_keluar->customer->alamat }}</td>
            </tr>

            <tr>
                <td>Nama</td>
                <td>: {{ $product_keluar->customer->nama }}</td>
                <td>Email</td>
                <td>: {{ $product_keluar->customer->email }}</td>
            </tr>

            <tr>
                <td>Barang</td>
                <td >: {{ $product_keluar->product->nama }}</td>
                <td>Quantity</td>
                <td >: {{ $product_keluar->qty }}</td>
            </tr>

            <tr>
                <td>Harga</td>
                <td >: {{ $product_keluar->product->harga }}</td>
                <td>Total Bayar</td>
                <td >: {{ $product_keluar->qty * $product_keluar->product->harga }}</td>
            </tr>

        </table>

        {{--<hr  size="2px" color="black" align="left" width="45%">--}}


        <table border="0" width="100%">
            <tr align="right">
                <td>Hormat Kami</td>
            </tr>
        </table>

    {{-- <table border="0" width="10%">
        <tr align="right">
            <td><img src="https://upload.wikimedia.org/wikipedia/en/f/f4/Timothy_Spall_Signature.png" width="100px" height="100px"></td>
        </tr>
    </table> --}}

    <table border="0" width="100%">
        <tr align="right">
            <td>PT asik asik bengkel</td>
        </tr>
    </table>
</div>
