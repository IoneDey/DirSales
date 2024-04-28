<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timsetup extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['joinUser', 'joinTim', 'joinKota'];

    public function joinUser()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function joinTim()
    {
        return $this->belongsTo(Tim::class, 'timid', 'id');
    }

    public function joinKota()
    {
        return $this->belongsTo(Kota::class, 'kotaid', 'id');
    }
}
