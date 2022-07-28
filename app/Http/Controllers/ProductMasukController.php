<?php

namespace App\Http\Controllers;


use App\Exports\ExportProdukMasuk;
use App\Product;
use App\Product_Masuk;
use App\Supplier;
use PDF;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class ProductMasukController extends Controller
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
        $products = Product::orderBy('nama','ASC')
            ->get()
            ->pluck('nama','id');

        $suppliers = Supplier::orderBy('nama','ASC')
            ->get()
            ->pluck('nama','id');

        $invoice_data = Product_Masuk::all();
        return view('product_masuk.index', compact('products','suppliers','invoice_data'));
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
            'supplier_id'    => 'required',
            'qty'            => 'required',
            'tanggal'        => 'required'
        ]);

        $qtyInput = (int)$request->qty;
        if($qtyInput <= 0){
            return response()->json([
                'error'    => true,
                'message'    => 'Input anda tidak masuk akal'
            ]);

        }
        Product_Masuk::create($request->all());
        $product = Product::findOrFail($request->product_id);
        $product->qty += $request->qty;
        $product->save();

        return response()->json([
            'success'    => true,
            'message'    => 'Barang Masuk Berhasil Ditambah'
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
        $product_masuk = Product_Masuk::find($id);
        return $product_masuk;
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
            'supplier_id'    => 'required',
            'qty'            => 'required',
            'tanggal'        => 'required'
        ]);
        $qtyInput = (int)$request->qty;
        if($qtyInput <= 0){
            return response()->json([
                'error'    => true,
                'message'    => 'Input anda tidak masuk akal'
            ]);

        }

        $product_masuk = Product_Masuk::findOrFail($id);
        $product_masuk->update($request->all());

        $product = Product::findOrFail($request->product_id);
        $product->qty += $request->qty;
        $product->update();

        return response()->json([
            'success'    => true,
            'message'    => 'Barang Masuk Berhasil Diubah'
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
        Product_Masuk::destroy($id);

        return response()->json([
            'success'    => true,
            'message'    => 'Barang Masuk Berhasil Dihapus'
        ]);
    }



    public function apiProductsIn(Request $request){
        $filter_bulan = $request->filter_bulan ?? null;
        $product = Product_Masuk::orderBy('tanggal', 'ASC');
        if($filter_bulan != null){
            $filter_bulanArr = explode("-", $filter_bulan);
            $totalTanggal = cal_days_in_month(CAL_GREGORIAN, $filter_bulanArr[1], $filter_bulanArr[0]);
            $filterTanggalMulai = $filter_bulan . "-01";
            $filterTanggalSelesai = $filter_bulan . "-" . $totalTanggal;

            $product->where('product_masuk.tanggal', '>=', $filterTanggalMulai)
            ->where('product_masuk.tanggal', '<=', $filterTanggalSelesai);
        }
        $product = $product->get();

        return Datatables::of($product)
            ->addColumn('products_name', function ($product){
                return $product->product->nama;
            })
            ->addColumn('supplier_name', function ($product){
                return $product->supplier->nama;
            })
            ->addColumn('action', function($product){
                return '<a onclick="editForm('. $product->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Ubah</a> ' .
                    '<a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Hapus</a> ';


            })
            ->rawColumns(['products_name','supplier_name','action'])->make(true);

    }

    public function exportProductMasukAll(Request $request)
    {
        $filter_bulan = $request->filter_bulan ?? null;
        $product_masuk = Product_Masuk::orderBy('tanggal', 'ASC');
        if($filter_bulan != null){
            $filter_bulanArr = explode("-", $filter_bulan);
            $totalTanggal = cal_days_in_month(CAL_GREGORIAN, $filter_bulanArr[1], $filter_bulanArr[0]);
            $filterTanggalMulai = $filter_bulan . "-01";
            $filterTanggalSelesai = $filter_bulan . "-" . $totalTanggal;

            $product_masuk->where('product_masuk.tanggal', '>=', $filterTanggalMulai)
            ->where('product_masuk.tanggal', '<=', $filterTanggalSelesai);
        }
        $product_masuk = $product_masuk->get();
        $pdf = PDF::loadView('product_masuk.productMasukAllPDF',compact('product_masuk'));
        return $pdf->download('product_masuk.pdf');
    }

    public function exportProductMasuk($id)
    {
        $product_masuk = Product_Masuk::findOrFail($id);
        $pdf = PDF::loadView('product_masuk.productMasukPDF', compact('product_masuk'));
        return $pdf->download($product_masuk->id.'_product_masuk.pdf');
    }

    public function exportExcel()
    {
        return (new ExportProdukMasuk)->download('product_masuk.xlsx');
    }
}
