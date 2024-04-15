<?php

namespace App\Models;

use App\Models\Kotas;
use App\Models\Timdt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timhd extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'timhd';
    protected $with = ['joinPt', 'joinKota', 'joinUser', 'joinTimdt', 'joinTimdt.joinBarang'];

    public function joinPt()
    {
        return $this->belongsTo(Pts::class, 'ptid', 'id');
    }

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
