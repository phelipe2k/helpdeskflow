<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'file_name',
        'file_path',
        'file_type',
        'uploaded_by',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}