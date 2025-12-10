<?php

namespace App\Traits;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Trait HasLocalDates {

    /**
     * Localize a date to users timezone 
     * 
     * @param null $dateField
     * @return Carbon
     */
    public function localize($dateField = null)
    {
        $userTimezone = Auth::user()->timezone ;

        $dateValue = is_null($this->{$dateField}) ? Carbon::now($userTimezone) : $this->{$dateField};
        return $this->inUsersTimezone($dateValue);
    }

    /**
     * Change timezone of a carbon date 
     * 
     * @param $dateValue
     * @return Carbon
     */
    private function inUsersTimezone($dateValue): Carbon
    {
        $timezone = optional(auth()->user())->timezone ?? config('app.timezone');
        return $this->asDateTime($dateValue)->timezone($timezone);
    }

}


