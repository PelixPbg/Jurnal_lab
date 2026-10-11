<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model {
    protected $table = 'classes';
    protected $fillable = ['name'];

    public function students() {
        return $this->hasMany(Student::class, 'class_id');
    }
    public function labSchedules() {
        return $this->hasMany(LabSchedule::class, 'class_id');
    }
}