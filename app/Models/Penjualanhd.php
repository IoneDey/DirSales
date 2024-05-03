<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualanhd extends Model {
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['joinTimSetup'];

    public function joinTimSetup() {
        return $this->belongsTo(Timsetup::class, 'timsetupid', 'id');
    }
}
