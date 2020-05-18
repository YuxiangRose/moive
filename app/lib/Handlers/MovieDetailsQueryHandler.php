<?php
namespace App\lib\Handlers;

use Illuminate\Support\Facades\DB;

class MovieDetailsQueryHandler
{
    function __construct()
    {
    }

    public function getMovieDetails($movieId)
    {

        $movieDetails = DB::table('movies')->where('id',$movieId)->first();
        $movieGenres = DB::table('genres')
                        ->select('genres.id', 'genres.name')
                        ->join('movie_genres', 'movie_genres.genre_id', '=', 'genres.id')
                        ->join('movies', 'movies.id', '=', 'movie_genres.movie_id')
                        ->where('movie_id',$movieId)->get();
        if($movieDetails){
            $moviePath = $movieDetails->path;
            if(!$this->checkMovieExisting($moviePath)) {
                $movieDetails = DB::table('movies')->where('id',$movieId)->first();
            }
        }

        return [
            'movie' => $movieDetails,
            'genres' => $movieGenres
        ];
    }

    private function checkMovieExisting($moviePath)
    {
        $existing = file_exists($moviePath);
        if (!$existing) {
            DB::table('movies')
                ->where('path',$moviePath)
                ->update([
                    'path' => null,
                    'waiting' => 1
                ]);
        }

        return $existing;
    }

//    public function getMainPageData()
//    {
//        return [
//            'movies' => $this->getMainPageMovies(),
//            'tvShows' => $this->getMainPageTvShows(),
//            'waitingList' => $this->getMainPageWaitingList()
//        ];
//    }
//
//    public function getMainPageMovies()
//    {
//        return DB::table('movies')
//            ->select('id','title','poster_path')
//            ->where('waiting',0)
//            ->orderBy('release_date','desc')
//            ->limit(4)
//            ->get();
//    }
//
//    public function getMainPageTvShows()
//    {
//        return array();
//    }
//
//    public function getMainPageWaitingList()
//    {
//        return DB::table('movies')->where('waiting',1)->orderBy('release_date','desc')->limit(4)->get();
//    }
}