<?php
namespace App\lib\Model;

use Illuminate\Support\Facades\Storage;

class ShowDetailsViewModel
{
    public $id;

    public $title;

    public $posterPath;

    public $originalLanguage;

    public $originalTitle;

    public $overview;

    public $firstAirDate;

    public $lastAirDate;

    public $numberOfEpisodes;

    public $numberOfSeason;

    public $inProduction;

    public $rating;

    public $genres;

    public $seasons;

    public function build($data)
    {
        $show = $data['show'];
        $genres = $data['genres'];
        $this->id = $show->id;
        $this->title = utf8_encode($show->title);
        if ( $show->title === $show->original_name) {
            $this->originalTitle = '';
        } else {
            $this->originalTitle = utf8_encode($show->original_name);
        }
        $this->posterPath = Storage::url('poster'.$show->poster_path);
        $this->originalLanguage = utf8_encode($show->language);
        $this->overview = utf8_encode($show->overview);
        $this->firstAirDate = $show->first_air_date;
        $this->lastAirDate = $show->last_air_date;
        $this->numberOfEpisodes = $show->number_of_episodes;
        $this->numberOfSeason = $show->number_of_seasons;
        $this->inProduction = $show->in_production;
        $this->rating = $show->rating;
        foreach($genres as $genre)
        {
            $this->genres[$genre->id] = utf8_encode($genre->name);
        }
        if (isset($data['seasons'])){
            foreach($data['seasons'] as $season)
            {
                $season->overview = utf8_encode($season->overview);
                $season->link = '/index.php/season/'.$season->season_id;
                $season->poster_path = Storage::url('poster'.$season->poster_path);
                $this->seasons[] = $season;
            }
        }
        return $this;
    }
}