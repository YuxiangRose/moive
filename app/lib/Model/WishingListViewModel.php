<?php
namespace App\lib\Model;

use Illuminate\Support\Facades\Storage;

class WishingListViewModel
{
    public $movies;

    public function build($data)
    {
        foreach($data['movies'] as $movie) {
            $movie->poster_path =  Storage::url('poster'.$movie->poster_path);
            $movie->title = utf8_encode($movie->title);
            $movie->link = '/index.php/movie-details/'.$movie->id;
        }

        $this->movies = $data['movies'];

        return $this;
    }
}