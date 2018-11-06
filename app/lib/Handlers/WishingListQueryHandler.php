<?php
namespace App\lib\Handlers;

use Illuminate\Support\Facades\DB;

class WishingListQueryHandler
{
    function __construct()
    {
    }

    public function getAllWhishingMovies()
    {
        $movies = DB::table('movies')
            ->select('id','title','poster_path')
            ->where('waiting',1)
            ->orderBy('release_date','desc')
            ->get();

        return [
            'movies' => $movies,
            'status' => 'all'
        ];
    }
}