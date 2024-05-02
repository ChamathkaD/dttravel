<?php

namespace App\Services;

use Carbon\Carbon;

class AgeCalculationService
{
    /**
     * Calculate the age based on the provided birthday.
     *
     * @param  Carbon|string|null  $birthday The user's birthday (in a valid date format or Carbon instance).
     * @return int The calculated age in years.
     */
    public function calculateAge(Carbon|string $birthday = null): int
    {
        $birthdayDate = Carbon::parse($birthday);
        $now = Carbon::now();

        return $now->diffInYears($birthdayDate);
    }
}
