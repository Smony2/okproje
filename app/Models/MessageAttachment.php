<?php
// MessageAttachment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MessageAttachment extends Model
{
    protected $fillable = ['message_id', 'file_path', 'file_name', 'file_size', 'file_type'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    // Dosya silindiğinde diskten de silinsin
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        });
    }
}
