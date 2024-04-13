<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timdt extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'timdt';

    public function joinTimhd()
    {
        return $this->belongsTo(Timhd::class, 'nomerid', 'id');
    }
}
