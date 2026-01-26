<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountRequest extends Model
{
    protected $fillable = ['name', 'email', 'role', 'justification', 'status'];
}
