@extends('layouts.master')


@section('top')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
    <div class="box">

        <div class="box-header">
            <h3 class="box-title">Data Penjualan Barang</h3>
        </div>

        <div class="box-header" style="display: flex;">
            <a onclick="addForm()" class="btn btn-primary" style="margin-right: 15px;">Tambah Penjualan Barang</a>
            <button onclick="exportPDFLaporan();" class="btn btn-success" style="margin-right: 15px;">Cetak Laporan Penjualan Barang</button>
            <div class="form-group" style="margin: 0;">
                <input id="filter_bulan" name="filter_bulan" type="month" class="form-control" style="min-width: 190px; max-width: 200px;">
            </div>
            {{-- <a href="{{ route('exportExcel.productKeluarAll') }}" class="btn btn-success">Export Excel</a> --}}
        </div>

        <!-- /.box-header -->
        <div class="box-body">
            <table id="products-out-table" class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Barang</th>
                    <th>Pelanggan</th>
                    <th>QTY</th>
                    <th>Tanggal Pembelian</th>
                    <th></th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>



    <div class="box col-md-6">

        <div class="box-header">
            <h3 class="box-title">Cetak Nota</h3>
        </div>

        {{--<div class="box-header">--}}
            {{--<a onclick="addForm()" class="btn btn-primary" >Add Products Out</a>--}}
            {{--<a href="{{ route('exportPDF.productKeluarAll') }}" class="btn btn-danger">Export PDF</a>--}}
            {{--<a href="{{ route('exportExcel.productKeluarAll') }}" class="btn btn-success">Export Excel</a>--}}
        {{--</div>--}}

        <!-- /.box-header -->
        <div class="box-body">
            <table id="invoice" class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Barang</th>
                    <th>Pelanggan</th>
                    <th>QTY</th>
                    <th>Harga</th>
                    <th>Total Bayar</th>
                    <th>Tanggal Pembelian</th>
                    <th>Aksi</th>
                </tr>
                </thead>

                @foreach($invoice_data as $i)
                    <tbody>
                        <td>{{ $i->id }}</td>
                        <td>{{ $i->product->nama }}</td>
                        <td>{{ $i->customer->nama }}</td>
                        <td>{{ $i->qty }}</td>
                        <td>Rp. {{ number_format($i->product->harga, 0, ',', '.')}}</td>
                        <td>Rp. {{ number_format($i->qty * $i->product->harga, 0, ',', '.') }}</td>
                        <td>{{ $i->tanggal }}</td>
                        <td>
                            <a href="{{ route('exportPDF.productKeluar', [ 'id' => $i->id ]) }}" class="btn btn-sm btn-success">Cetak</a>
                        </td>
                    </tbody>
                @endforeach
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    @include('product_keluar.form')

@endsection

@section('bot')

    <!-- DataTables -->
    <script src=" {{ asset('assets/bower_components/datatables.net/js/jquery.dataTables.min.js') }} "></script>
    <script src="{{ asset('assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }} "></script>


    <!-- InputMask -->
    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('assets/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('assets/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('assets/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- bootstrap time picker -->
    <script src="{{ asset('assets/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    {{-- Validator --}}
    <script src="{{ asset('assets/validator/validator.min.js') }}"></script>

    <script>
    $(function () {
    // $('#items-table').DataTable()
    var invoice = $('#invoice').DataTable({
    'paging'      : true,
    'lengthChange': false,
    'searching'   : false,
    'ordering'    : true,
    'info'        : true,
    'autoWidth'   : false,
    'processing'  : true,
    // 'serverSide'  : true
    })
    })
    </script>

    <script>
        $(function () {

            //Date picker
            $('#tanggal').datepicker({
                autoclose: true,
                // dateFormat: 'yyyy-mm-dd'
            })

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            //Timepicker
            $('.timepicker').timepicker({
                showInputs: false
            })
        })
    </script>

    <script type="text/javascript">
        function fetchData(filter_bulan = ""){
            var table = $('#products-out-table').DataTable({
                "bDestroy": true,
                processing: true,
                serverSide: true,
                ajax:{
                    url: "{{ route('api.productsOut') }}",
                    data:{
                        filter_bulan: filter_bulan
                    },
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'products_name', name: 'products_name'},
                    {data: 'customer_name', name: 'customer_name'},
                    {data: 'qty', name: 'qty'},
                    {data: 'tanggal', name: 'tanggal'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        }

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Tambah Penjualan Barang');
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
            $('#tanggal').val(today);
            $("#tanggal").prop('disabled', true);
        }

        function editForm(id) {
            save_method = 'edit';
            $('input[name=_method]').val('PATCH');
            $('#modal-form form')[0].reset();
            $("#tanggal").prop('disabled', false);
            $.ajax({
                url: "{{ url('productsOut') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Ubah Penjualan Barang');

                    $('#id').val(data.id);
                    $('#product_id').val(data.product_id);
                    $('#customer_id').val(data.customer_id);
                    $('#qty').val(data.qty);
                    $('#tanggal').val(data.tanggal);
                },
                error : function() {
                    alert("Nothing Data");
                }
            });
        }

        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
                title: 'Yakin mau dihapus?',
                text: "Data yang sudah terhapus tidak dapat dikembalikan",
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus'
            }).then(function () {
                $.ajax({
                    url : "{{ url('productsOut') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        // table.ajax.reload();
                        fetchData();
                        swal({
                            title: 'Success!',
                            text: data.message,
                            type: 'success',
                            timer: '1500'
                        });
                    },
                    error : function () {
                        swal({
                            title: 'Oops...',
                            text: data.message,
                            type: 'error',
                            timer: '1500'
                        })
                    }
                });
            });
        }

        $(function(){
            $('#modal-form form').validator().on('submit', function (e) {
                if (!e.isDefaultPrevented()){
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('productsOut') }}";
                    else url = "{{ url('productsOut') . '/' }}" + id;

                    $("#tanggal").prop('disabled', false);
                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            if(data.error === true){
                                swal({
                                    title: 'Oops...',
                                    text: data.message,
                                    type: 'error',
                                    timer: '1500'
                                })
                            } else if(data.success) {
                                $('#modal-form').modal('hide');
                                fetchData();
                                // table.ajax.reload();
                                window.location.reload();
                                swal({
                                    title: 'Success!',
                                    text: data.message,
                                    type: 'success',
                                    timer: '1500'
                                })
                            }
                        },
                        error : function(data){
                            swal({
                                title: 'Oops...',
                                text: data.message,
                                type: 'error',
                                timer: '1500'
                            })
                        }
                    });
                    return false;
                }
            });
        });

        function exportPDFLaporan(){
            var filter_bulan = $('#filter_bulan').val();
            var url = `{{ url('exportProductKeluarAll?filter_bulan=${filter_bulan}') }}`;
            window.location.href = url;
        }

        $('#filter_bulan').on('change', function (e) {
            var filter_bulan = $('#filter_bulan').val();

            fetchData(filter_bulan);
        });

        fetchData();
    </script>

@endsection
