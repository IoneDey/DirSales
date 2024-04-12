<?php

namespace App\Http\Controllers;

use App\Models\Pts;
use Illuminate\Http\Request;

class PtController extends Controller
{
    //
    public function pt()
    {
        $data = Pts::where('nama', 'like', '%' . request('q') . '%')->paginate(15);
        return response()->json($data);
    }
}
