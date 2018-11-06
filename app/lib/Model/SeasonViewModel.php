<?php
namespace App\lib\Model;

use Illuminate\Support\Facades\Storage;

class SeasonViewModel
{
    public $title;

    public $posterPath;

    public $overview;

    public $airDate;

    public $seasonNumber;

    public $episodeCount;

    public $episodes;

    public $link;

    public function build($data)
    {
        $season= $data['season'];
        $this->title = utf8_encode($season->show_name);
        $this->link = '/index.php/show-details/'.$season->show_id;
        $this->posterPath =  Storage::url('poster'.$season->poster_path);
        $this->airDate = $season->air_date;
        $this->overview = utf8_encode($season->overview);
        $this->seasonNumber = $season->season_number;
        $this->episodeCount = $season->episode_count;

        foreach($data['episodes'] as $episode)
        {
            $episode->path = utf8_encode($episode->path);
            $this->episodes[] = $episode;
        }

        return $this;
    }
}