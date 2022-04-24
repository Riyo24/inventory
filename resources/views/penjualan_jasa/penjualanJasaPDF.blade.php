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
            Denpasar, {{ $penjualan_jasa->tanggal }}
          </td>
        </tr>
    </table>

    <table border="0" width="30%" style="margin-top: 10px;">
        <tr>
            <td>
            Pelanggan
          </td>
          <td>
            : {{ $penjualan_jasa->customer->nama }}
          </td>
        </tr>
        <tr>
            <td>
                Telepon
            </td>
            <td>
                : {{ $penjualan_jasa->customer->telepon }}
            </td>
        </tr>
    </table>

    <table border="0" id="table-data" width="100%">
        <tr style="background: #000; color: #fff;">
          <td align="center" colspan="4"><strong>Invoice ID {{ $penjualan_jasa->id }}</strong></td>
        </tr>

        <tr align="center">
            <td width="46%" align="left"><strong>Jasa</strong></td>
            <td width="18%"><strong>Keterangan</strong></td>
            <td width="18%"><strong>Harga</strong></td>
            <td width="18%"><strong>Total</strong></td>
        </tr>
        <tr align="center">
            <td width="46%" align="left">{{ $penjualan_jasa->jasa->nama }}</td>
            <td width="18%">{{ $penjualan_jasa->jasa->keterangan }}</td>
            <td width="18%">Rp. {{ $penjualan_jasa->jasa->harga }}</td>
            <td width="18%">Rp. {{ $penjualan_jasa->jasa->harga }}</td>
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
