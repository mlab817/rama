<?php

use Carbon\Carbon;

if (! function_exists('format_date')) {
    function format_date(string $date) {
        $rawDate = Carbon::parse($date);

        return $rawDate->format('m/d/Y');
    }
}
