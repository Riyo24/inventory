<?php

namespace App\Http\Controllers;

use App\Category;
use App\Customer;
use App\Exports\ExportProdukKeluar;
use App\Product;
use App\Product_Keluar;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use PDF;


class ProductKeluarController extends Controller
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
        $products = Product::orderBy('nama','ASC')->where('qty', '>=', '1')
            ->get();

        $customers = Customer::orderBy('nama','ASC')
            ->get()
            ->pluck('nama','id');

            $invoice_data = Product_Keluar::all();
        // $invoice_data = Product_Keluar::orderBy('id', 'DESC')->take(1)->get();
        return view('product_keluar.index', compact('products','customers', 'invoice_data'));
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
           'product_id'     => 'required',
           'customer_id'    => 'required',
           'qty'            => 'required',
           'tanggal'           => 'required'
        ]);

        $product = Product::findOrFail($request->product_id);
        $qtyInput = (int)$request->qty;
        if($qtyInput <= 0){
            return response()->json([
                'error'    => true,
                'message'    => 'Input anda tidak masuk akal'
            ]);

        }
        if($qtyInput > $product->qty){
            return response()->json([
                'error'    => true,
                'message'    => 'Stok Yang Diinput melebihi batas'
            ]);
        }

        Product_Keluar::create($request->all());
        
        $product->qty -= $request->qty;
        $product->save();

        return response()->json([
            'success'    => true,
            'message'    => 'Penjualan Barang Berhasil ditambahkan',
            'data'       => $product,
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product_keluar = Product_Keluar::find($id);
        return $product_keluar;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'product_id'     => 'required',
            'customer_id'    => 'required',
            'qty'            => 'required',
            'tanggal'           => 'required'
        ]);

        $product = Product::findOrFail($request->product_id);
        $qtyInput = (int)$request->qty;
        if($qtyInput <= 0){
            return response()->json([
                'error'    => true,
                'message'    => 'Input anda tidak masuk akal'
            ]);

        }
        if($qtyInput > $product->qty){
            return response()->json([
                'error'    => true,
                'message'    => 'Stok Yang Diinput melebihi batas'
            ]);
        }
        $product_keluar = Product_Keluar::findOrFail($id);
        $product_keluar->update($request->all());

        $product->qty -= $request->qty;
        $product->update();

        return response()->json([
            'success'    => true,
            'message'    => 'Penjualan Barang Berhasil Diubah'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product_Keluar::destroy($id);

        return response()->json([
            'success'    => true,
            'message'    => 'Penjualan Barang Berhasil Dihapus'
        ]);
    }



    public function apiProductsOut(Request $request){
        $filter_bulan = $request->filter_bulan ?? null;
        $product = Product_Keluar::orderBy('tanggal', 'ASC');
        if($filter_bulan != null){
            $filter_bulanArr = explode("-", $filter_bulan);
            $totalTanggal = cal_days_in_month(CAL_GREGORIAN, $filter_bulanArr[1], $filter_bulanArr[0]);
            $filterTanggalMulai = $filter_bulan . "-01";
            $filterTanggalSelesai = $filter_bulan . "-" . $totalTanggal;

            $product->where('product_keluar.tanggal', '>=', $filterTanggalMulai)
            ->where('product_keluar.tanggal', '<=', $filterTanggalSelesai);
        }
        $product = $product->get();

        return Datatables::of($product)
            ->addColumn('products_name', function ($product){
                return $product->product->nama;
            })
            ->addColumn('harga_format', function ($product){
                return "Rp. " . $product->product->harga;
            })
            ->addColumn('customer_name', function ($product){
                return $product->customer->nama;
            })
            ->addColumn('action', function($product){
                return '<a onclick="editForm('. $product->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Ubah</a> ' .
                    '<a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
            })
            ->rawColumns(['products_name','customer_name','action'])->make(true);

    }

    public function exportProductKeluarAll(Request $request)
    {
        $filter_bulan = $request->filter_bulan ?? null;
        $product_keluar = Product_Keluar::orderBy('tanggal', 'ASC');
        if($filter_bulan != null){
            $filter_bulanArr = explode("-", $filter_bulan);
            $totalTanggal = cal_days_in_month(CAL_GREGORIAN, $filter_bulanArr[1], $filter_bulanArr[0]);
            $filterTanggalMulai = $filter_bulan . "-01";
            $filterTanggalSelesai = $filter_bulan . "-" . $totalTanggal;

            $product_keluar->where('product_keluar.tanggal', '>=', $filterTanggalMulai)
            ->where('product_keluar.tanggal', '<=', $filterTanggalSelesai);
        }
        $product_keluar = $product_keluar->get();
        $pdf = PDF::loadView('product_keluar.productKeluarAllPDF',compact('product_keluar'));
        return $pdf->download('product_keluar.pdf');
    }

    public function exportProductKeluar($id)
    {
        $product_keluar = Product_Keluar::findOrFail($id);
        $pdf = PDF::loadView('product_keluar.productKeluarPDF', compact('product_keluar'));
        return $pdf->download($product_keluar->id.'_product_keluar.pdf');
    }

    public function exportExcel()
    {
        return (new ExportProdukKeluar)->download('product_keluar.xlsx');
    }
}
