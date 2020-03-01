<?php


namespace Modules\Auth\Models;


use Illuminate\Database\Eloquent\Model;
use User;

class PasswordReset extends Model
{
    public $timestamps = false;
    protected $table = 'password_resets';

    protected $fillable = [
        'email', 'token'
    ];
}
