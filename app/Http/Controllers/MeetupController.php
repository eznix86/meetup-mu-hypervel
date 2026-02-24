<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Meetup;
use Carbon\Carbon;
use Hypervel\Support\Facades\Cache;

class MeetupController extends AbstractController
{
    public function home()
    {
        $todays = Cache::remember('meetups_today', 600, function () {
            return Meetup::whereDate('date', '=', Carbon::today())
                ->orderBy('date', 'asc')
                ->get();
        });

        $meetups = Cache::remember('meetups_home', 600, function () {
            return Meetup::where('date', '>=', Carbon::now())
                ->orderBy('date', 'asc')
                ->get();
        });

        return view('index', compact('meetups', 'todays'));
    }
}
