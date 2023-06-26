<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    // use SoftDeletes;
    protected $table = 'notification';
    protected $primaryKey = 'id';

    // protected $fillable = [
    //     'id', 'user_id', 'title', 'message', 'notification_type', 'read_status', 'created_at', 'updated_at'
    // ];

    // protected $hidden = ['deleted_at'];

    public function unread(){
        return $this->hasMany('App\Notification','notification_to','notification_to')->where('read_status','unread');
    }
}
