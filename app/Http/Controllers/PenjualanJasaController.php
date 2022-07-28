<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
use App\Jasa;
use App\Penjualan_Jasa;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;

class PenjualanJasaController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jasa = Jasa::orderBy('nama','ASC')
            ->get()
            ->pluck('nama','id');

        $customers = Customer::orderBy('nama','ASC')
            ->get()
            ->pluck('nama','id');

            $invoice_data = Penjualan_Jasa::all();
        // $invoice_data = Penjualan_Jasa::orderBy('id', 'DESC')->take(1)->get();
        return view('penjualan_jasa.index', compact('jasa','customers', 'invoice_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'jasa_id'        => 'required',
            'customer_id'    => 'required',
            'tanggal'        => 'required',
            'harga'        => 'required',
            'keterangan'        => 'required'
        ]);
        $hargaInput = (int)$request->harga;
        if($hargaInput <= 0){
            return response()->json([
                'error'    => true,
                'message'    => 'Input anda tidak masuk akal'
            ]);
        }
 
         Penjualan_Jasa::create($request->all());
 
         return response()->json([
             'success'    => true,
             'message'    => 'Penjualan Jasa Berhasil ditambah'
         ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Penjualan_Jasa  $penjualan_Jasa
     * @return \Illuminate\Http\Response
     */
    public function show(Penjualan_Jasa $penjualan_Jasa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Penjualan_Jasa  $penjualan_Jasa
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $penjualan_jasa = Penjualan_Jasa::find($id);
        return $penjualan_jasa;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Penjualan_Jasa  $penjualan_Jasa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'jasa_id'     => 'required',
            'customer_id'    => 'required',
            'tanggal'           => 'required',
            'harga'           => 'required',
            'keterangan'           => 'required'
        ]);
        $hargaInput = (int)$request->harga;
        if($hargaInput <= 0){
            return response()->json([
                'error'    => true,
                'message'    => 'Input anda tidak masuk akal'
            ]);
        }

        $penjualan_jasa = Penjualan_Jasa::findOrFail($id);
        $penjualan_jasa->update($request->all());

        return response()->json([
            'success'    => true,
            'message'    => 'Penjualan Jasa Berhasil Diubah'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Penjualan_Jasa  $penjualan_Jasa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Penjualan_Jasa::destroy($id);

        return response()->json([
            'success'    => true,
            'message'    => 'Penjualan Jasa Berhasil Dihapus'
        ]);
    }
    public function apiPenjualanJasa(Request $request){
        $filter_bulan = $request->filter_bulan ?? null;
        $penjualan_jasa = Penjualan_Jasa::orderBy('tanggal', 'ASC');
        if($filter_bulan != null){
            $filter_bulanArr = explode("-", $filter_bulan);
            $totalTanggal = cal_days_in_month(CAL_GREGORIAN, $filter_bulanArr[1], $filter_bulanArr[0]);
            $filterTanggalMulai = $filter_bulan . "-01";
            $filterTanggalSelesai = $filter_bulan . "-" . $totalTanggal;

            $penjualan_jasa->where('penjualan_jasa.tanggal', '>=', $filterTanggalMulai)
            ->where('penjualan_jasa.tanggal', '<=', $filterTanggalSelesai);
        }
        $penjualan_jasa = $penjualan_jasa->get();

        return Datatables::of($penjualan_jasa)
            ->addColumn('jasa_name', function ($penjualan_jasa){
                return $penjualan_jasa->jasa->nama;
            })
            ->addColumn('customer_name', function ($penjualan_jasa){
                return $penjualan_jasa->customer->nama;
            })
            ->addColumn('customer_alamat', function ($penjualan_jasa){
                return $penjualan_jasa->customer->alamat;
            })
            ->addColumn('action', function($penjualan_jasa){
                return '<a onclick="editForm('. $penjualan_jasa->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Ubah</a> ' .
                    '<a onclick="deleteData('. $penjualan_jasa->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
            })
            ->rawColumns(['jasa_name','customer_name','action'])->make(true);
    }
    public function exportPenjualanJasaAll(Request $request)
    {
        $filter_bulan = $request->filter_bulan ?? null;
        $penjualan_jasa = Penjualan_Jasa::orderBy('tanggal', 'ASC');
        if($filter_bulan != null){
            $filter_bulanArr = explode("-", $filter_bulan);
            $totalTanggal = cal_days_in_month(CAL_GREGORIAN, $filter_bulanArr[1], $filter_bulanArr[0]);
            $filterTanggalMulai = $filter_bulan . "-01";
            $filterTanggalSelesai = $filter_bulan . "-" . $totalTanggal;

            $penjualan_jasa->where('penjualan_jasa.tanggal', '>=', $filterTanggalMulai)
            ->where('penjualan_jasa.tanggal', '<=', $filterTanggalSelesai);
        }
        $penjualan_jasa = $penjualan_jasa->get();
        
        $pdf = PDF::loadView('penjualan_jasa.penjualanJasaAllPDF',compact('penjualan_jasa'));
        return $pdf->download('penjualan_jasa.pdf');
    }

    public function exportPenjualanJasa($id)
    {
        $penjualan_jasa = Penjualan_Jasa::findOrFail($id);
        $pdf = PDF::loadView('penjualan_jasa.penjualanJasaPDF', compact('penjualan_jasa'));
        return $pdf->download($penjualan_jasa->id.'_penjualan_jasa.pdf');
    }
}
