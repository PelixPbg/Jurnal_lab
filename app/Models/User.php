<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = ['username', 'password', 'role'];
    protected $hidden = ['password'];

    public function student() {
        return $this->hasOne(Student::class);
    }
    public function teacher() {
        return $this->hasOne(Teacher::class);
    }
}