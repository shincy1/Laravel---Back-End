<?php

namespace App\Http\Controllers;

use App\Models\PelangganData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PelangganDataController extends Controller
{
    protected $pelangganDataModel;

    public function __construct()
    {
        $this->pelangganDataModel = new PelangganData();
    }

    public function index()
    {
        $pelangganData = $this->pelangganDataModel->get();

        if ($pelangganData->isEmpty()) {
            return response()->json([
                'msg' => "Data pelanggan data masih kosong!",
                'data' => []
            ], 200);
        } else {
            return response()->json([
                'data' => $pelangganData
            ], 200);
        }
    }

    public function show($id)
    {
        try {
            $pelangganData = $this->pelangganDataModel->findOrFail($id);

            return response()->json([
                'data' => $pelangganData
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'msg' => 'Data pelanggan data tidak ditemukan',
                'data' => []
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pelanggan_data_pelanggan_id' => 'required|integer',
            'pelanggan_data_jenis' => 'required|string|in:SIM,KTP', // Tambahkan validasi ENUM
            'pelanggan_data_file' => 'required|file|max:10240' // max 10 MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msg' => 'Gagal menambahkan data pelanggan data',
                'errors' => $validator->errors()
            ], 422);
        } else {
            $file = $request->file('pelanggan_data_file');
            $fileName = $file->getClientOriginalName();
            $path = $file->storeAs('pelanggan_data', $fileName);

            $data = [
                'pelanggan_data_pelanggan_id' => $request->input('pelanggan_data_pelanggan_id'),
                'pelanggan_data_jenis' => $request->input('pelanggan_data_jenis'),
                'pelanggan_data_file' => $path
            ];

            $pelangganData = $this->pelangganDataModel->create($data);

            return response()->json([
                'msg' => 'Berhasil menambahkan data pelanggan data',
                'data' => $pelangganData
            ], 201);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pelanggan_data_pelanggan_id' => 'required|integer',
            'pelanggan_data_jenis' => 'required|string|in:SIM,KTP', // Tambahkan validasi ENUM
            'pelanggan_data_file' => 'sometimes|file|max:10240' // max 10 MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msg' => 'Gagal mengubah data pelanggan data',
                'errors' => $validator->errors()
            ], 422);
        } else {
            $pelangganData = $this->pelangganDataModel->findOrFail($id);

            $file = $request->file('pelanggan_data_file');
            if ($file) {
                $fileName = $file->getClientOriginalName();
                $path = $file->storeAs('pelanggan_data', $fileName);
                $pelangganData->pelanggan_data_file = $path;
            }

            $pelangganData->pelanggan_data_pelanggan_id = $request->input('pelanggan_data_pelanggan_id');
            $pelangganData->pelanggan_data_jenis = $request->input('pelanggan_data_jenis');
            $pelangganData->save();

            return response()->json([
                'msg' => 'Berhasil mengubah data pelanggan data',
                'data' => $pelangganData
            ], 200);
        }
    }

    public function destroy($id)
    {
        $pelangganData = $this->pelangganDataModel->find($id);

        if (!$pelangganData) {
            return response()->json([
                'msg' => 'Gagal menghapus data pelanggan data',
                'errors' => 'Data tidak ditemukan'
            ], 404);
        } else {
            $pelangganData->delete();
            return response()->json([
                'msg' => 'Berhasil menghapus data pelanggan data',
                'data' => $pelangganData
            ], 200);
        }
    }
}
