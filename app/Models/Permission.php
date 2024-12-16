<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'permissionId';

    protected $fillable = [
        'permissionName',
    ];

    public function accountPermissions()
    {
        return $this->hasMany(AccountPermission::class, 'permissionId');
    }

}
