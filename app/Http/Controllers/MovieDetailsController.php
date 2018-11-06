<?php
namespace App\Http\Controllers;

use App\lib\ApiConnector\MVDB;
use App\lib\Handlers\MovieDetailsQueryHandler;
use App\lib\Model\MovieDetailsViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieDetailsController extends Controller
{
    private $languages;

    public function __construct()
    {
        $this->languages = [
            'zh' => '中文',
            'en' => '英文',
            'cn' => '粤语'
        ];
    }

    public function index(
        Request $request,
        MovieDetailsQueryHandler $movieDetailsQueryHandler,
        MovieDetailsViewModel $movieDetailsViewModel
    )
    {
        $movieId = $request->route()->getParameter('id');
        $data = $movieDetailsQueryHandler->getMovieDetails($movieId);
        if (!$data['movie']) {
            //$data['movie'] = $this->getMovieDataFromOnLine($movieId);
            $data = $this->getMovieDataFromOnLine($movieId);
        }
        $viewModel = $movieDetailsViewModel->build($data);

        return view('movie-details')->with('data', $viewModel);
    }

    private function getMovieDataFromOnLine($movieId){
        $mvdb = new MVDB();

        $movieDetails = $mvdb->getMovieDetailsById($movieId);
        $originalLanguage = isset($this->languages[$movieDetails->original_language]) ? $this->languages[$movieDetails->original_language] : $movieDetails->original_language;
        $language = $movieDetails->original_language;
        if ($movieDetails->belongs_to_collection){
            $collection_name = $movieDetails->belongs_to_collection->name;
            $collection_id = $movieDetails->belongs_to_collection->id;
        } else {
            $collection_name = null;
            $collection_id = null;
        }
        if ($movieDetails->original_language == 'cn' || $movieDetails->original_language == 'zh')
        {
            $language = 'zh';
        }
        $posters = $mvdb->getMoviePosterByLanguage($movieId,$language);
        $filePath = $posters[0]->file_path;
        $exists = Storage::disk()->exists('public/poster'.$filePath);
        if (!$exists){
            $url = 'https://image.tmdb.org/t/p/w300/'.$filePath;
            $contents = file_get_contents($url);
            Storage::put('public/poster'.$filePath, $contents);
            $exists = Storage::disk()->exists('public/poster'.$filePath);
        }
        if ($exists) {
            $movie = (object)[
                'id' => $movieDetails -> id,
                'title' => $movieDetails -> title,
                'poster_path' => $filePath,
                'original_language'=> $originalLanguage,
                'original_title' => $movieDetails -> original_title,
                'overview'=> $movieDetails -> overview,
                'release_date' => $movieDetails -> release_date,
                'waiting' => 2,
                'path' => NULL,
                'collection_name' => $collection_name,
                'collection_id' => $collection_id,
                'runtime' => $movieDetails->runtime ? $movieDetails->runtime : Null,
                'budget' => $movieDetails->budget ? $movieDetails->budget : Null,
                'revenue' => $movieDetails->revenue ? $movieDetails->revenue : Null,
                'rating' =>$movieDetails->vote_average ? $movieDetails->vote_average : Null
            ];
        }
        $data = [
            'movie' => $movie,
            'genres' => $movieDetails->genres,
        ];
        return $data;
    }
}