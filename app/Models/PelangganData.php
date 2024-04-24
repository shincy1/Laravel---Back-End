<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelangganData extends Model
{
    use HasFactory;

    protected $table = 'pelanggan_data';
    protected $primaryKey = 'pelanggan_data_id';
    protected $fillable = [
        'pelanggan_data_pelanggan_id',
        'pelanggan_data_jenis',
        'pelanggan_data_file',
    ];

    public function get_pelanggan_data()
    {
        return self::all();
    }

    public function create_pelanggan_data($data)
    {
        return self::create($data);
    }

    public function update_pelanggan_data($data, $id)
    {
        $pelangganData = self::find($id);
        $pelangganData->fill($data);
        $pelangganData->save();
        return $pelangganData;
    }

    public function delete_pelanggan_data($id)
    {
        $pelangganData = self::find($id);
        $pelangganData->delete();
        return $pelangganData;
    }
}
