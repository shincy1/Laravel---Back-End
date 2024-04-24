<?php

namespace App\Http\Controllers;

use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenyewaanController extends Controller
{
    protected $penyewaanModel;

    public function __construct() {
        $this->penyewaanModel = new Penyewaan();
    }

    public function index()
    {
        $penyewaan = $this->penyewaanModel->get_penyewaan();

        if(count($penyewaan) === 0){
            return response()->json([
                'msg'=>"Data penyewaan masih kosong!",
                'data'=>$penyewaan
            ],204);
        } else {
            return response()->json([
                'data'=>$penyewaan
            ],200);
        }
    }

    public function show($id)
    {

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'penyewaan_nama'=> 'required|string|max:100',
            'penyewaan_alamat' => 'required|string|max:100',
            'penyewaan_notelp' => 'required|string|max:100',
            'penyewaan_email' => 'required|string|max:100',
        ]);

        if($validator->fails()) {
            return response()->json([
                'msg'=> 'Gagal menambahkan data penyewaan',
                'errors'=> $validator->errors()
            ],422);
        } else {
            $penyewaan = $this->penyewaanModel->create_penyewaan($validator->validated());

            return response()->json([
                'msg' => 'Berhasil menambahkan data penyewaan',
                'data' => $penyewaan
            ],201);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all, [
            'penyewaan_nama'=> 'required|string|max:100',
            'penyewaan_alamat' => 'required|string|max:100',
            'penyewaan_notelp' => 'required|string|max:100',
            'penyewaan_email' => 'required|string|max:100',
        ]);

        if($validator->fails()) {
            return response()->json([
                'msg'=> 'Gagal mengubah data penyewaan',
                'errors'=> $validator->errors()
            ],422);
        } else {
            $penyewaan = $this->penyewaanModel->update_penyewaan($validator->validated(), $id);

            return response()->json([
                'msg'=> 'Berhasil mengubah data penyewaan',
                'data'=> $penyewaan
            ],200);
        }
    }

    public function destroy($id)
    {
        $penyewaan = $this->penyewaanModel->delete_penyewaan($id);

        if(!$penyewaan) {
            return response()->json([
                'msg' => 'Gagal menghapus data penyewaan',
                'errors' => 'Data tidak ditemukan'
            ],404);
        } else {
            return response()->json([
                'msg'=> 'Berhasil menghapus data penyewaan',
                'data'=> $penyewaan
            ],200);
        }
    }
}
