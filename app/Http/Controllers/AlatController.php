<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class AlatController extends Controller
{
    protected $alatModel; // Deklarasi properti alatModel

    public function __construct()
    {
        $this->alatModel = new Alat(); // Inisialisasi objek Alat
    }

    public function index()
{
    $alat = $this->alatModel->get_alat();

    if ($alat->isEmpty()) {
        return response()->json([
            'msg' => "Data alat masih kosong!",
            'data' => []
        ], 200);
    } else {
        return response()->json([
            'data' => $alat
        ], 200);
    }
}



    public function show($id)
    {
        try {
            $alat = $this->alatModel->findOrFail($id);

            return response()->json([
                'data' => $alat
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'msg' => 'Data alat tidak ditemukan',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alat_kategori_id' => 'required|int',
            'alat_nama' => 'required|string|max:150',
            'alat_deskripsi' => 'required|string|max:255',
            'alat_hargaperhari' => 'required|int',
            'alat_stok' => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msg' => 'Gagal menambahkan data alat',
                'errors' => $validator->errors()
            ], 422);
        } else {
            $alat = $this->alatModel->create_alat($validator->validated());

            return response()->json([
                'msg' => 'Berhasil menambahkan data alat',
                'data' => $alat
            ], 201);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'alat_kategori_id' => 'required|int',
            'alat_nama' => 'required|string|max:150',
            'alat_deskripsi' => 'required|string|max:255',
            'alat_hargaperhari' => 'required|int',
            'alat_stok' => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'msg' => 'Gagal mengubah data kategori',
                'errors' => $validator->errors()
            ], 422);
        } else {
            $alat = $this->alatModel->update_alat($validator->validated(), $id);

            return response()->json([
                'msg' => 'Berhasil mengubah data alat',
                'data' => $alat
            ], 200);
        }
    }

    public function destroy($id)
    {
        $alat = $this->alatModel->delete_alat($id);

        if (!$alat) {
            return response()->json([
                'msg' => 'Gagal menghapus data alat',
                'errors' => 'Data tidak ditemukan'
            ], 404);
        } else {
            return response()->json([
                'msg' => 'Berhasil menghapus data alat',
                'data' => $alat
            ], 200);
        }
    }

    public function patch(Request $request, $id)
    {
        try {
            $alat = $this->alatModel->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'alat_kategori_id' => 'sometimes|required|int',
                'alat_nama' => 'sometimes|required|string|max:150',
                'alat_deskripsi' => 'sometimes|required|string|max:255',
                'alat_hargaperhari' => 'sometimes|required|int',
                'alat_stok' => 'sometimes|required|int',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $alat->update($request->all());

            return response()->json([
                'msg' => 'Berhasil memperbarui data alat',
                'data' => $alat
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'msg' => 'Data alat tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        } catch (ValidationException $exception) {
            return response()->json([
                'msg' => 'Gagal memperbarui data alat',
                'errors' => $exception->validator->getMessageBag()->toArray()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function delete($id)
    {
        try {
            // Cari alat berdasarkan ID
            $alat = $this->alatModel->findOrFail($id);

            // Hapus alat
            $alat->delete();

            return response()->json([
                'msg' => 'Berhasil menghapus data alat',
                'data' => $alat
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            // Tangani jika alat tidak ditemukan
            return response()->json([
                'msg' => 'Data alat tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $exception) {
            // Tangani kesalahan umum lainnya
            return response()->json([
                'msg' => 'Gagal menghapus data alat',
                'error' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
