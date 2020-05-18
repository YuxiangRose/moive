<?php
namespace App\lib\Handlers;

use App\Http\Controllers\Api\SettingApiController;
use App\lib\ApiConnector\MVDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MoviesAllQueryHandler
{
    private $languages;

    function __construct()
    {
        $this->languages = [
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

    public function getAllMovies()
    {
        $movies = DB::table('movies')
        ->select('id','title','poster_path')
        ->where('waiting',0)
        ->orderBy('release_date','desc')
        ->get();
        return [
            'movies' => $movies,
            'status' => 'all'
        ];
    }

    public function getMoviesByName($name)
    {
        $status = 'non';
        
        if(\substr($name,0,1) === '!') {
            $name = \substr($name,1);
            $movies = DB::table('movies')
            ->select('id','title','poster_path')
            ->where('title', $name)
            ->orderBy('release_date','desc')
            ->get();
        } else {
            $movies = DB::table('movies')
            ->select('id','title','poster_path')
            ->where('title','like', '%'.$name.'%')
            ->orderBy('release_date','desc')
            ->get();
        }
        if(count($movies) > 0) {
            $status = 'local_search';
        } else {
            $movies = $this->getMoviesByNameFromMVDB($name);
            if (count($movies) > 0) {
                $status = 'online_search';
            }
        }

        return [
            'movies' => $movies,
            'status' => $status
        ];
    }
    public function getMoviesByTag($tagId)
    {
        $res = DB::table('genres')->where('id', $tagId)->first();
        $tag = $res->name;
        $movies = DB::table('movie_genres')
            ->select('movies.id','movies.title','movies.poster_path')
            ->join('movies', 'movies.id', '=', 'movie_genres.movie_id')
            ->where('genre_id',$tagId)
            ->orderBy('release_date','desc')
            ->get();

        return [
            'movies' => $movies,
            'status' => $tag
        ];
    }

    public function getMoviesByCollection($collectionId)
    {
        $status = 'non';
        $movies = DB::table('movies')
            ->select('id','title','poster_path','collection_name')
            ->where('collection_id','=', $collectionId)
            ->orderBy('release_date','desc')
            ->get();
        if(count($movies) > 0) {
            $status = $movies[0]->collection_name;
        }

        return [
            'movies' => $movies,
            'status' => $status
        ];
    }

    public function getMoviesByLanguage($language)
    {
        $movies = DB::table('movies')
            ->select('id','title','poster_path')
            ->where('original_language', 'like', '%'.$language.'%')
            ->orderBy('release_date','desc')
            ->get();
        
        return [
            'movies' => $movies,
            'status' => $language
        ];
    }

    private function getMoviesByNameFromMVDB($name)
    {
        $mvdb = new MVDB();
        $results = $mvdb->getMovieSearchResult($name)->results;
        
        $movies = [];

        foreach ($results as $movieDetails)
        {
            $noPoster = false;
            $movieId = $movieDetails->id ? $movieDetails->id : NULL;
            $title = $movieDetails->title ? $movieDetails->title : NULL;
            $language = $movieDetails->original_language;
            if ($movieDetails->original_language == 'cn' || $movieDetails->original_language == 'zh')
            {
                $language = 'zh';
            }

            $posters = $mvdb->getMoviePosterByLanguage($movieId,$language);
            if(count($posters)){
                $filePath = $posters[0]->file_path;
            } else {
                $noPoster = true;
                //$filePath = '/pic-404.png';
                $filePath = '/pic-404.jpg';
            }
            $exists = Storage::disk()->exists('public/poster'.$filePath);
            if (!$exists){
                if($noPoster) {
                    $exists = true;
                } else {
                    $url = 'https://image.tmdb.org/t/p/w300/'.$filePath;
                    $contents = file_get_contents($url);
                    Storage::put('public/poster'.$filePath, $contents);
                    $exists = Storage::disk()->exists('public/poster'.$filePath);
                }
            }
//            $exists = Storage::disk()->exists('public/poster'.$filePath);
//            $url = 'https://image.tmdb.org/t/p/w300/'.$movieDetails->poster_path;
//            $contents = file_get_contents($url);
//            $name = $movieDetails->poster_path;
//            Storage::put('public/poster'.$name, $contents);
//            $exists = Storage::disk()->exists('public/poster'.$name);
            if ($exists) {
                $movie = (object)[
                    'title' => $title,
                    'id' => $movieId,
                    'poster_path' => $filePath,
                ];
                $movies[] = $movie;
            }
        }

        return $movies;
    }
}