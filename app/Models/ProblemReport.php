<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProblemReport extends Model {
    protected $fillable = ['journal_id', 'student_id', 'lab_id', 'computer_id', 'item_name', 'problem_description', 'status', 'reported_at', 'resolved_at', 'resolution_notes'];

    protected $casts = [
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function journal() {
        return $this->belongsTo(LabJournal::class, 'journal_id');
    }
    public function student() {
        return $this->belongsTo(Student::class);
    }
    public function lab() {
        return $this->belongsTo(Lab::class, 'lab_id');
    }
    public function computer() {
        return $this->belongsTo(Computer::class);
    }
}