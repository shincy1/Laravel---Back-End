<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PelangganController extends Controller
{
    protected $pelangganModel;

    public function __construct() {
        $this->pelangganModel = new Pelanggan();
    }

    public function index()
{
    $pelanggan = $this->pelangganModel->get_pelanggan();

    if(count($pelanggan) === 0){
        return response()->json([
            'msg'=>"Data pelanggan masih kosong!",
            'data'=>[]
        ],200); // Ubah status code menjadi 200
    } else {
        return response()->json([
            'data'=>$pelanggan
        ],200);
    }
}


    public function show($id)
    {
        try {
            $pelanggan = $this->pelangganModel->findOrFail($id);

            return response()->json([
                'data' => $pelanggan
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'msg' => 'Data pelanggan tidak ditemukan',
                'data' => []
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'pelanggan_nama'=> 'required|string|max:100',
            'pelanggan_alamat' => 'required|string|max:100',
            'pelanggan_notelp' => 'required|string|max:100',
            'pelanggan_email' => 'required|string|max:100',
        ]);

        if($validator->fails()) {
            return response()->json([
                'msg'=> 'Gagal menambahkan data pelanggan',
                'errors'=> $validator->errors()
            ],422);
        } else {
            $pelanggan = $this->pelangganModel->create_pelanggan($validator->validated());

            return response()->json([
                'msg' => 'Berhasil menambahkan data pelanggan',
                'data' => $pelanggan
            ],201);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pelanggan_nama'=> 'required|string|max:100',
            'pelanggan_alamat' => 'required|string|max:100',
            'pelanggan_notelp' => 'required|string|max:100',
            'pelanggan_email' => 'required|string|max:100',
        ]);

        if($validator->fails()) {
            return response()->json([
                'msg'=> 'Gagal mengubah data pelanggan',
                'errors'=> $validator->errors()
            ],422);
        } else {
            $pelanggan = $this->pelangganModel->update_pelanggan($validator->validated(), $id);

            return response()->json([
                'msg'=> 'Berhasil mengubah data pelanggan',
                'data'=> $pelanggan
            ],200);
        }
    }

    public function destroy($id)
    {
        $pelanggan = $this->pelangganModel->delete_pelanggan($id);

        if(!$pelanggan) {
            return response()->json([
                'msg' => 'Gagal menghapus data pelanggan',
                'errors' => 'Data tidak ditemukan'
            ],404);
        } else {
            return response()->json([
                'msg'=> 'Berhasil menghapus data pelanggan',
                'data'=> $pelanggan
            ],200);
        }
    }
}
