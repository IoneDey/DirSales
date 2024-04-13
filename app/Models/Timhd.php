<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kotas;
use App\Models\User;
use App\Models\Timdt;

class Timhd extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'timhd';
    protected $with = ['joinUser', 'joinKota', 'joinTimdt'];

    public function joinKota()
    {
        return $this->belongsTo(Kotas::class, 'kotaid', 'id');
    }

    public function joinUser()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function joinTimdt()
    {
        return $this->hasMany(Timdt::class, 'nomerid', 'id');
    }
}
