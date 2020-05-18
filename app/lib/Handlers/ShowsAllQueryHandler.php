<?php
namespace App\lib\Handlers;

use App\lib\ApiConnector\MVDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShowsAllQueryHandler
{
    private $languages;

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

    public function getAllShows()
    {
        $shows = DB::table('shows')
            ->select('id','title', 'poster_path')
            ->orderBy('last_air_date', 'desc')
            ->get();

        return [
            'shows' => $shows,
            'status' => 'all'
        ];
    }

    public function getShowsByName($name)
    {
        $status = 'non';
        $shows = DB::table('shows')
            ->select('id','title','poster_path')
            ->where('title','like', '%'.$name.'%')
            ->orWhere('original_name','like', '%'.$name.'%')
            ->orderBy('release_date','desc')
            ->get();
        if(count($shows) > 0) {
            $status = 'local_search';
        } else {
            $shows = $this->getShowsByNameFromMVDB($name);
            if (count($shows) > 0) {
                $status = 'online_search';
            }
        }

        return [
            'shows' => $shows,
            'status' => $status
        ];
    }

    public function getShowsByNameFromMVDB($name)
    {
        $mvdb = new MVDB();
        $results = $mvdb->getShowsSearchResult($name)->results;
        $shows = [];

        foreach ($results as $showDetails)
        {
            $showId = $showDetails->id ? $showDetails->id : NULL;
            $title = $showDetails->name ? $showDetails->name : NULL;
            $filePath = $showDetails->poster_path;
            $exists = Storage::disk()->exists('public/poster'.$filePath);
            if (!$exists){
                $url = 'https://image.tmdb.org/t/p/w300/'.$filePath;
                $contents = file_get_contents($url);
                Storage::put('public/poster'.$filePath, $contents);
                $exists = Storage::disk()->exists('public/poster'.$filePath);
            }
            if ($exists) {
                $show = (object)[
                    'title' => $title,
                    'id' => $showId,
                    'poster_path' => $filePath,
                ];
                $shows[] = $show;
            }
        }

        return $shows;
    }

    public function getShowsByTag($tagId)
    {
        $res = DB::table('genres')->where('id', $tagId)->first();
        $tag = $res->name;
        $shows = DB::table('movie_genres')
            ->select('shows.id','shows.title','shows.poster_path')
            ->join('shows', 'shows.id', '=', 'movie_genres.movie_id')
            ->where('genre_id',$tagId)
            ->orderBy('release_date','desc')
            ->get();

        return [
            'shows' => $shows,
            'status' => $tag
        ];
    }
}