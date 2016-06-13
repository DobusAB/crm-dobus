<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Dobus\ManagesLeads;

class LeadController extends Controller
{
    /**
     * Get all leads
     */
     public function index(ManagesLeads $lead)
     {
         return $lead->all();
     }

    /**
     * Store a new lead
     */
    public function store(Request $request, ManagesLeads $lead)
    {
        $lead->create($request);
    }
}
