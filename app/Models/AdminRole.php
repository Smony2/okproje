<?php
// app/Models/AdminRole.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    protected $fillable = ['name', 'slug'];

    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_role', 'admin_role_id', 'admin_id');
    }
}
