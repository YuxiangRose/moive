<?php
namespace App\lib\Handlers;

use Illuminate\Support\Facades\DB;

class MainPageQueryHandler
{
    function __construct()
    {
    }

    public function getMainPageData()
    {
        return [
            'movies' => $this->getMainPageMovies(),
            'tvShows' => $this->getMainPageTvShows(),
            'waitingList' => $this->getMainPageWaitingList()
        ];
    }

    public function getMainPageMovies()
    {
        return DB::table('movies')
            ->select('id','title','poster_path')
            ->where('waiting',0)
            ->orderBy('release_date','desc')
            ->limit(4)
            ->get();
    }

    public function getMainPageTvShows()
    {
        return DB::table('shows')
            ->select('id','title','poster_path')
            ->orderBy('last_air_date', 'desc')
            ->limit(4)
            ->get();
    }

    public function getMainPageWaitingList()
    {
        return DB::table('movies')->where('waiting',1)->orderBy('release_date','desc')->limit(4)->get();
    }
}