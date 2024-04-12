<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kotas;
use App\Models\User;

class Timhd extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'timhd';
    protected $with = ['joinUser', 'joinKota'];

    public function joinKota()
    {
        return $this->belongsTo(Kotas::class, 'kotaid', 'id');
    }

    public function joinUser()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
}
