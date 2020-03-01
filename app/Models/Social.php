<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use User;

class Social extends Model
{
    protected $table = 'socials';

    protected $fillable = [
        'user_id', 'provider', 'provider_id'
    ];

    public function user() {
        return $this->hasOne(User::class, 'id');
    }
}
