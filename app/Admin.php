<?php

namespace App;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
	use SoftDeletes;
    protected $table = 'admin';

    public function getAuthPassword()
    {
        return $this->password;
    }
}
