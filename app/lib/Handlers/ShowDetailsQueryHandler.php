<?php
namespace App\lib\Handlers;

use App\lib\ApiConnector\MVDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShowDetailsQueryHandler
{
    private  $languages;

    function __construct()
    {
        $this->languages =  [
            'zh' => '中文',
            'en' => '英文',
            'cn' => '粤语',
            'fr' => '法语',
            'ko' => '韩语',
            'ja' => '日语',
            'de' => '德语',
            'es' => '西班牙语',
            'it' => '意大利语',
            'hi' => '印地语',
        ];
    }

    public function getShowDetails($showId)
    {

        $showDetails = DB::table('shows')->where('id', $showId)->first();
        $showGenres = DB::table('genres')
            ->select('genres.id', 'genres.name')
            ->join('movie_genres', 'movie_genres.genre_id', '=', 'genres.id')
            ->join('shows', 'shows.id', '=', 'movie_genres.movie_id')
            ->where('movie_id', $showId)->get();
        $seasons = DB::table('seasons')
            ->where('show_id', $showId)
            ->orderBy('season_number', 'asc')
            ->get();
        return [
            'show' => $showDetails,
            'genres' => $showGenres,
            'seasons' => $seasons
        ];
    }

    public function getShowDataFromOnLine($showId)
    {
        $mvdb = new MVDB();

        $showDetails = $mvdb->getShowsDetailsById($showId);
        $showDetails->title = $showDetails->name;
        $showDetails->language = $this->languages[$showDetails->original_language];
        $showGenres = $showDetails->genres;
        $showDetails->rating = $showDetails->vote_average;
        //$seasons = $showDetails->seasons;

        return [
            'show' => $showDetails,
            'genres' => $showGenres
        ];
    }
}