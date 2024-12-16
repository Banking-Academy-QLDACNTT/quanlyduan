<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountPermission extends Model
{
    protected $table = 'account_permissions';

    protected $fillable = [
        'id', 
        'permissionId', 
        'can_view', 
        'can_edit', 
        'can_delete', 
        'can_add',
    ];

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permissionId');
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, 'id');
    }

}
