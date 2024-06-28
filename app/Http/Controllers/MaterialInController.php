<?php

namespace App\Http\Controllers;

use App\Models\MaterialIn;
use Illuminate\Http\Request;

class MaterialInController extends Controller
{
    public function Allmaterialin()
    {
        return view('material_in.all_materialin
        ');
    }

    public function Addmaterialin()
    {
        return view('material_in.add_materialin
        ');
    }
}

