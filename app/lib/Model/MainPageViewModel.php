<?php
namespace App\lib\Model;

use Illuminate\Support\Facades\Storage;

class MainPageViewModel
{
    public $movies;

    public $tvShows;

    public $waitingList;

    public function build($data)
    {
        foreach($data['movies'] as $movie) {
            $movie->poster_path = Storage::url('poster'.$movie->poster_path);
            $movie->title = utf8_encode($movie->title);
            $movie->link = '/index.php/movie-details/'.$movie->id;
        }

        foreach($data['tvShows'] as $show) {
            $show->poster_path = Storage::url('poster'.$show->poster_path);
            $show->title = utf8_encode($show->title);
            $show->link = '/index.php/show-details/'.$show->id;
        }

        foreach($data['waitingList'] as $movie) {
            $movie->poster_path = Storage::url('poster'.$movie->poster_path);
            $movie->title = utf8_encode($movie->title);
            $movie->link = '/index.php/movie-details/'.$movie->id;
        }

        $this->movies = $data['movies'];
        $this->tvShows = $data['tvShows'];
        $this->waitingList = $data['waitingList'];

        return $this;
    }
}