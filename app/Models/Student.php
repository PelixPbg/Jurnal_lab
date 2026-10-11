<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {
    protected $fillable = ['user_id', 'class_id', 'nis', 'name'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function classRoom() {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
    public function labJournals() {
        return $this->hasMany(LabJournal::class);
    }
    public function problemReports() {
        return $this->hasMany(ProblemReport::class);
    }
}