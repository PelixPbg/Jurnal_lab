<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model {
    protected $fillable = ['name', 'location', 'description'];

    public function computers() {
        return $this->hasMany(Computer::class);
    }
    public function labSchedules() {
        return $this->hasMany(LabSchedule::class);
    }
}