<?php

namespace App\Http\Controllers;

use App\Jasa;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JasaController extends Controller
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
        $jasa = Jasa::all();
        return view('jasa.index', compact('jasa'));
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
			'nama' => 'required',
			// 'keterangan' => 'required',
			// 'harga' => 'required',
		]);

		Jasa::create($request->all());

		return response()->json([
			'success' => true,
			'message' => 'Jasa Berhasil Ditambah',
		]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Jasa  $jasa
     * @return \Illuminate\Http\Response
     */
    public function show(Jasa $jasa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Jasa  $jasa
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jasa = Jasa::find($id);
		return $jasa;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Jasa  $jasa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
			'nama' => 'required|string|min:2',
			// 'keterangan' => 'required|string|min:2',
			// 'harga' => 'required',
		]);

		$jasa = Jasa::findOrFail($id);

		$jasa->update($request->all());

		return response()->json([
			'success' => true,
			'message' => 'Jasa Berhasil Diubah',
		]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Jasa  $jasa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Jasa::destroy($id);

		return response()->json([
			'success' => true,
			'message' => 'Jasa Berhasil Dihapus',
		]);
    }

    public function apiJasa() {
		$jasa = Jasa::all();

		return Datatables::of($jasa)
			->addColumn('action', function ($jasa) {
				return '<a onclick="editForm(' . $jasa->id . ')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Ubah</a> ' .
				'<a onclick="deleteData(' . $jasa->id . ')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Hapus</a>';
			})
			->rawColumns(['action'])->make(true);
	}
}
