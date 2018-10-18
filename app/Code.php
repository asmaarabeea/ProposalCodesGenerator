<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Code extends Model
{
    use SoftDeletes;

    protected $fillable  = [
        'code', 'user_id','proposal_type', 'technical_approval', 'sales_agent','proposal_number', 'client_source',
        'client_name','proposal_date', 'proposal_value',
    ];
}
