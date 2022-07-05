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
            <h3 class="box-title">Data Pelayanan Jasa</h3>
        </div>

        <div class="box-header">
            <a onclick="addForm()" class="btn btn-primary" >Tambah Pelayanan Jasa</a>
            <a href="{{ route('exportPDF.penjualanJasaAll') }}" class="btn btn-success">Cetak Laporan Jasa</a>
        </div>

        <!-- /.box-header -->
        <div class="box-body">
            <table id="penjualan-jasa-table" class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Jasa</th>
                    <th>Pelanggan</th>
                    <th>Alamat</th>
                    <th>Tanggal Pembelian</th>
                    <th>Aksi</th>
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

        <!-- /.box-header -->
        <div class="box-body">
            <table id="invoice" class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Jasa</th>
                    <th>Pelanggan</th>
                    <th>Alamat</th>
                    <th>Total Bayar</th>
                    <th>Keterangan</th>
                    <th>Tanggal Pembelian</th>
                    <th>Aksi</th>
                </tr>
                </thead>

                @foreach($invoice_data as $i)
                    <tbody>
                        <td>{{ $i->id }}</td>
                        <td>{{ $i->jasa->nama }}</td>
                        <td>{{ $i->customer->nama }}</td>
                        <td>{{ $i->customer->alamat }}</td>
                        <td>Rp. {{ number_format($i->harga, 0, ',', '.') }}</td>
                        <td>{{ $i->keterangan }}</td>
                        <td>{{ $i->tanggal }}</td>
                        <td>
                            <a href="{{ route('exportPDF.penjualanJasa', [ 'id' => $i->id ]) }}" class="btn btn-sm btn-success">Cetak</a>
                        </td>
                    </tbody>
                @endforeach
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    @include('penjualan_jasa.form')

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
        var table = $('#penjualan-jasa-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.penjualanJasa') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'jasa_name', name: 'jasa_name'},
                {data: 'customer_name', name: 'customer_name'},
                {data: 'customer_alamat', name: 'customer_alamat'},
                {data: 'tanggal', name: 'tanggal'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        function addForm() {
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Tambah Penjualan Jasa');
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
                url: "{{ url('penjualanJasa') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modal-form').modal('show');
                    $('.modal-title').text('Ubah Penjualan Jasa');

                    $('#id').val(data.id);
                    $('#jasa_id').val(data.jasa_id);
                    $('#customer_id').val(data.customer_id);
                    $('#harga').val(data.harga);
                    $('#keterangan').val(data.keterangan);
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
                    url : "{{ url('penjualanJasa') }}" + '/' + id,
                    type : "POST",
                    data : {'_method' : 'DELETE', '_token' : csrf_token},
                    success : function(data) {
                        table.ajax.reload();
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
                    $("#tanggal").prop('disabled', false);
                    var id = $('#id').val();
                    if (save_method == 'add') url = "{{ url('penjualanJasa') }}";
                    else url = "{{ url('penjualanJasa') . '/' }}" + id;
                    $.ajax({
                        url : url,
                        type : "POST",
                        //hanya untuk input data tanpa dokumen
//                      data : $('#modal-form form').serialize(),
                        data: new FormData($("#modal-form form")[0]),
                        contentType: false,
                        processData: false,
                        success : function(data) {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                            window.location.reload();
                            swal({
                                title: 'Success!',
                                text: data.message,
                                type: 'success',
                                timer: '1500'
                            })
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
    </script>

@endsection
