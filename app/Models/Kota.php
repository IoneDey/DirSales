<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['joinUser'];

    public function joinUser()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function joinProvinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsiid', 'id');
    }
}
