<?php
// app/Models/AdminRole.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KatipRole extends Model
{
    protected $fillable = ['name', 'slug'];

    public function admins()
    {
        return $this->belongsToMany(Katip::class, 'katip_role', 'katip_role_id', 'katip_id');
    }
}
