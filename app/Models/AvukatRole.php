<?php
// app/Models/AdminRole.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvukatRole extends Model
{
    protected $fillable = ['name', 'slug'];

    public function admins()
    {
        return $this->belongsToMany(Avukat::class, 'finance_role', 'finance_role_id', 'finance_id');
    }
}
