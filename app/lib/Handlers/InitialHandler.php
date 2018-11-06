<?php
namespace App\lib\Handlers;

use App\lib\ApiConnector\MVDB;
use Illuminate\Support\Facades\DB;

class InitialHandler
{
    private $mvdb;

    function __construct()
    {
        $this->mvdb = new MVDB();
    }

    public function init()
    {
        $this->initGenreMovieList();
    }

    private function initGenreMovieList()
    {
        $genreList = $this->mvdb->getGenreMovieList();
        foreach($genreList as $genre) {
            DB::table('genres')->insert([
                'id' => $genre->id,
                'name' => $genre->name
            ]);
        }
    }
}