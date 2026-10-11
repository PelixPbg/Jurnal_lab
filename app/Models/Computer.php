<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Computer extends Model {
    protected $fillable = ['lab_id', 'code', 'name', 'status'];

    public function lab() {
        return $this->belongsTo(Lab::class);
    }
    public function problemReports() {
        return $this->hasMany(ProblemReport::class);
    }
}