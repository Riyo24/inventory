<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cetak Nota</title>


</head>

<style>
    h1,h4{
        margin: 0;
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
                        <left>
    <h4>Jl. Gunung Karang No.10, Pemecutan Kelod,Denpasar Barat, Bali</h4>
</left>
                    </tr>
                </table>
            </td>
          <td align="right">
            Denpasar, {{ $penjualan_jasa->tanggal }}
          </td>
        </tr>
    </table>
    <hr>

    <table border="0" width="100%" style="margin-top: 10px;">
        <tr>
            <td width="12%">
            Pelanggan
          </td>
          <td>
            : {{ $penjualan_jasa->customer->nama }}
          </td>
        </tr>
        <tr>
            <td width="12%">
            Alamat
          </td>
          <td>
            : {{ $penjualan_jasa->customer->alamat }}
          </td>
        </tr>
        <tr>
            <td width="12%">
                Telepon
            </td>
            <td>
                : {{ $penjualan_jasa->customer->telepon }}
            </td>
        </tr>
    </table>

    <table border="0" id="table-data" width="100%">
        <?php 
        /* 
        <tr style="background: #000; color: #fff;">
          <td align="center" colspan="4"><strong>Invoice ID {{ $penjualan_jasa->id }}</strong></td>
        </tr>
        */
        ?>

        <tr align="center">
            <td width="46%" align="left"><strong>Jasa</strong></td>
            <td width="18%"><strong>Keterangan</strong></td>
            <td width="18%"><strong>Harga</strong></td>
            <td width="18%"><strong>Total</strong></td>
        </tr>
        <tr align="center">
            <td width="46%" align="left">{{ $penjualan_jasa->jasa->nama }}</td>
            <td width="18%">{{ $penjualan_jasa->keterangan }}</td>
            <td width="18%">Rp. {{ number_format($penjualan_jasa->harga, 0, ',', '.') }}</td>
            <td width="18%">Rp. {{ number_format($penjualan_jasa->harga, 0, ',', '.') }}</td>
        </tr>
    </table>

    <table border="0" width="100%" style="margin-top: 60px; clear: both;">
        <tr align="right">
            <td>Hormat Kami</td>
        </tr>
        <table border="0" width="100%" style="margin-top: 50px; clear: both;">
        <tr align="right">
            <td>Bengkel murah jaya</td>
        </tr>
    </table>
</div>
