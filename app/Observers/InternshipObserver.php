<?php

namespace App\Observers;

use App\Models\Certificate;
use App\Models\Internship;

class InternshipObserver
{
    /**
     * Handle the Internship "created" event.
     * Auto-create a draft certificate record.
     */
    public function created(Internship $internship): void
    {
        Certificate::create([
            'internship_id' => $internship->id,
            'status' => 'draft',
        ]);
    }
}
