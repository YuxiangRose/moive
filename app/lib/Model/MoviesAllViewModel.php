<?php
namespace App\lib\Model;

use Illuminate\Support\Facades\Storage;

class MoviesAllViewModel
{
    public $movies;

    public $status;

    public $total;

    public function build($data)
    {
        foreach($data['movies'] as $movie) {
            $movie->poster_path =  Storage::url('poster'.$movie->poster_path);
            $movie->title = utf8_encode($movie->title);
            $movie->link = '/index.php/movie-details/'.$movie->id;
        }

        $this->movies = $data['movies'];
        $this->status = utf8_encode($data['status']);
        $this->total = count($data['movies']);

        return $this;
    }
}