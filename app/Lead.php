<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_name', 'address', 'corporate_identity_number', 'phone', 'contact_person', 'email', 'status', 'industry_id'];
}
