<?php
namespace App\lib\Model;

use Illuminate\Support\Facades\Storage;

class MovieDetailsViewModel
{
    public $id;

    public $title;

    public $posterPath;

    public $originalLanguage;

    public $originalTitle;

    public $overview;

    public $releaseDate;

    public $path;

    public $isWaiting;

    public $genres;

    public $collectionName;

    public $collectionId;

    public $runtime;

    public $budget;

    public $revenue;

    public $rating;

    public function build($data)
    {
        $movie = $data['movie'];
        $genres = $data['genres'];
        $this->id = $movie->id;
        $this->title = utf8_encode($movie->title);
        if ( $movie->title === $movie->original_title) {
            $this->originalTitle = '';
        } else {
            $this->originalTitle = utf8_encode($movie->original_title);
        }
//        $this->posterPath = 'https://image.tmdb.org/t/p/w300/jETIjiAfLMpTXxm0jIBAdEqtKW6.jpg'. $movie->poster_path;
        $this->posterPath =  Storage::url('poster'.$movie->poster_path);
        $this->originalLanguage = utf8_encode($movie->original_language);
        $this->releaseDate = $movie->release_date;
        $this->overview = utf8_encode($movie->overview);
        $this->path = utf8_encode($movie->path);
        $this->isWaiting = $movie->waiting;
        $this->collectionId = $movie->collection_id;
        $this->collectionName = utf8_encode($movie->collection_name);
        $this->runtime = $movie->runtime;
        $this->budget = $movie->budget;
        $this->revenue = $movie->revenue;
        $this->rating = $movie->rating;

        foreach($genres as $genre)
        {
            $this->genres[$genre->id] = utf8_encode($genre->name);
        }

        return $this;
    }
}