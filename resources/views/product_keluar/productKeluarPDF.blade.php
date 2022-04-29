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
        border: 1px solid #ddd;
        padding: 5px;
    }
</style>

<body>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0" width="100%">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <h1>Bengkel Murah Jaya</h1>
                        </td>
                    </tr>
                </table>
            </td>
          <td align="right">
            Denpasar, {{ $product_keluar->tanggal }}
          </td>
        </tr>
    </table>

    <table border="0" width="30%" style="margin-top: 10px;">
        <tr>
            <td>
            Pelanggan
          </td>
          <td>
            : {{ $product_keluar->customer->nama }}
          </td>
        </tr>
        <tr>
            <td>
                Telepon
            </td>
            <td>
                : {{ $product_keluar->customer->telepon }}
            </td>
        </tr>
    </table>

    <table border="0" id="table-data" width="100%">
        <?php 
        /* 
        <tr style="background: #000; color: #fff;">
          <td align="center" colspan="4"><strong>Invoice ID {{ $product_keluar->id }}</strong></td>
        </tr>
        */
        ?>

        <tr align="center">
            <td width="58%" align="left"><strong>Barang</strong></td>
            <td width="6%"><strong>Jumlah</strong></td>
            <td width="22%"><strong>Harga Satuan</strong></td>
            <td width="22%"><strong>Total</strong></td>
        </tr>
        <tr align="center">
            <td width="58%" align="left">{{ $product_keluar->product->nama }}</td>
            <td width="6%">{{ $product_keluar->qty }}</td>
            <td width="18%">Rp. {{ $product_keluar->product->harga }}</td>
            <td width="18%">Rp. {{ $product_keluar->qty * $product_keluar->product->harga }}</td>
        </tr>
    </table>

    <table border="0" width="100%" style="margin-top: 20px; clear: both;">
        <tr align="right">
            <td>Hormat Kami</td>
        </tr>
        <tr align="right">
            <td>Bengkel Murah Jaya</td>
        </tr>
    </table>
</div>
