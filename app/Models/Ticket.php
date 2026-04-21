<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'category_id',
        'requester_id',
        'assignee_id',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function history()
    {
        return $this->hasMany(TicketHistory::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}