<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Dobus\Eniro;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $request = (object) $request->all();

        $eniro = new Eniro(
            'christopherdobus',
            '7783936476218749106',
            '1.1.3',
            'se',
            $request->city,
            $request->query,
            $request->from,
            $request->to
        );

        return $eniro->companies();
    }
}
