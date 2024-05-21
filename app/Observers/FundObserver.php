<?php

namespace App\Observers;

use App\Models\Fund;
use App\Models\FundCategory;

class FundObserver
{
    /**
     * Handle the Fund "created" event.
     */
    public function created(Fund $fund)
    {
       //
    }

    /**
     * Handle the Fund "updated" event.
     */
    public function updated(Fund $fund): void
    {
        //
    }

    /**
     * Handle the Fund "deleted" event.
     */
    public function deleted(Fund $fund): void
    {
        //
    }

    /**
     * Handle the Fund "restored" event.
     */
    public function restored(Fund $fund): void
    {
        //
    }

    /**
     * Handle the Fund "force deleted" event.
     */
    public function forceDeleted(Fund $fund): void
    {
        //
    }
}
