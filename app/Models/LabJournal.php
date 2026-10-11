<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabJournal extends Model {
    protected $fillable = ['schedule_id', 'student_id', 'check_in_at', 'check_out_at', 'activity_notes', 'student_notes', 'status', 'teacher_notes', 'verified_at'];

    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function schedule() {
        return $this->belongsTo(LabSchedule::class, 'schedule_id');
    }
    public function student() {
        return $this->belongsTo(Student::class);
    }
    public function checklists() {
        return $this->hasMany(JournalChecklist::class, 'journal_id');
    }
    public function problemReports() {
        return $this->hasMany(ProblemReport::class, 'journal_id');
    }
}