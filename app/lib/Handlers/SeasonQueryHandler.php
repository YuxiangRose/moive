<?php
namespace App\lib\Handlers;

use Illuminate\Support\Facades\DB;

class SeasonQueryHandler
{
    function __construct()
    {
    }

    public function getSeasonDetails($seasonId)
    {
        $seasonDetails = DB::table('seasons')->where('season_id', $seasonId)->first();
        $episodes = DB::table('episodes')
                        ->where('season_id', $seasonId)
                        ->orderBy('name', 'asc')
                        ->get();
        return [
            'season' => $seasonDetails,
            'episodes' => $episodes
        ];
    }
}