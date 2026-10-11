<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalChecklist extends Model {
    protected $fillable = ['journal_id', 'item_type', 'item_name', 'condition_before', 'condition_after', 'notes'];

    public function journal() {
        return $this->belongsTo(LabJournal::class, 'journal_id');
    }
}