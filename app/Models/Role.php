<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
        protected $fillable = ['name'];



        public function roles()
        {
            return $this->belongsToMany(
                Permission::class,
                'role_permissions'
            );
        }
}
