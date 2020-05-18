<?php
namespace App\lib\ApiConnector;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

Class MVDB
{
    const BASEURL = 'https://api.themoviedb.org/3/';
    const APIKEY = 'api_key=5717439484058017a7167c9dcefe24ba&';
    const LANGUAGE = 'language=zh-cn';

    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function getMovieSearchResult($movieName)
    {
        $uri = self::BASEURL . 'search/movie?' . self::APIKEY . self::LANGUAGE . '&query=' . $movieName;
        $res = $this->client->request('GET', $uri, ['verify'=> false]);

        $movieDetails = json_decode($res->getBody());

        return $movieDetails;
    }

    public function getMovieDetailsById($movieId)
    {
        $uri = self::BASEURL . 'movie/'.$movieId.'?' . self::APIKEY . self::LANGUAGE;
        $res = $this->client->request('GET', $uri, ['verify'=> false]);

        $movieDetails = json_decode($res->getBody());

        return $movieDetails;
    }

    public function getGenreMovieList()
    {
        $uri = self::BASEURL . 'genre/movie/list?' . self::APIKEY. self::LANGUAGE;
        $res = $this->client->request('GET', $uri, ['verify'=> false]);

        $genreList = json_decode($res->getBody())->genres;

        return $genreList;
    }

    public function getMoviePosterByLanguage($MovieId, $language)
    {
        $uri = self::BASEURL . 'movie/'.$MovieId.'/images?' . self::APIKEY;
        if ($language) {
            $uri = $uri .'&include_image_language='. $language. ',null,en';
        }

        $res = $this->client->request('GET', $uri, ['verify'=> false]);

        $posters = json_decode($res->getBody())->posters;

        if(count($posters) == 0) {
            $uri = self::BASEURL . 'movie/'.$MovieId.'/images?' . self::APIKEY;
            $res = $this->client->request('GET', $uri, ['verify'=> false]);
            $posters = json_decode($res->getBody())->posters;
        }

        return $posters;
    }

    public function getShowsSearchResult($showName)
    {
        $uri = self::BASEURL . 'search/tv?' . self::APIKEY . self::LANGUAGE . '&query=' . $showName;
        $res = $this->client->request('GET', $uri, ['verify'=> false]);

        $showDetails = json_decode($res->getBody());

        return $showDetails;
    }

    public function getShowsDetailsById($showId)
    {
        $uri = self::BASEURL . 'tv/'.$showId.'?' . self::APIKEY . self::LANGUAGE;
        $res = $this->client->request('GET', $uri, ['verify'=> false]);

        $showDetails = json_decode($res->getBody());

        return $showDetails;
    }
}