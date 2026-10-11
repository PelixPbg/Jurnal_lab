<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabSchedule extends Model {
    protected $fillable = ['lab_id', 'class_id', 'teacher_id', 'subject', 'activity', 'date', 'start_time', 'end_time', 'status'];

    public function lab() {
        return $this->belongsTo(Lab::class);
    }
    public function classRoom() {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
    public function labJournals() {
        return $this->hasMany(LabJournal::class, 'schedule_id');
    }
}