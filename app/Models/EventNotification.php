<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'sender_type', 
        'receiver_id', 
        'receiver_type', 
        'advertisement_id', 
        'events',
        'message',
        'is_read'
    ];

    public function getReceiverInfo()
    {
        return $this->belongsTo(User::class,'receiver_id');
    }

    public function getSenderInfo()
    {
        return $this->belongsTo(User::class,'sender_id');
    }
    public function getSenderAdminInfo()
    {
        return $this->belongsTo(AdminUser::class,'sender_id');
    }
    public function getAdsInfo()
    {
        return $this->belongsTo(Advertisement::class,'advertisement_id');
    }

}
