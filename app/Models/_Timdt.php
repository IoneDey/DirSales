<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timdt extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'timdt';
    protected $with = ['joinBarang'];

    public function joinTimhd()
    {
        return $this->belongsTo(Timhd::class, 'nomerid', 'id');
    }

    public function joinBarang()
    {
        return $this->belongsTo(Barangs::class, 'barangid', 'id');
    }
}
