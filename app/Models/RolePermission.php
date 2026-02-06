<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
        protected $fillable = ['role_id','permission_id'];
    
      public function role(){
        return $this->belongTo(Role::class);
      }
      public function permission(){
        return $this->belongTo(Permission::class);
      }
}


