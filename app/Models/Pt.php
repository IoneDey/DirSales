<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pt extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['joinUser'];

    public function joinUser()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }
}
