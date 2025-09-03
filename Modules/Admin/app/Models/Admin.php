<?php

namespace Modules\Admin\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';
    protected $fillable = ['first_name','email','password','last_name','phone'];
}
