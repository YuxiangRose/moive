<?php
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

    private function getMovieSearchResaut($movieName)
    {
        $uri = BASEURL.'search/movie?'.APIKEY.LANGUAGE.'query='.$movieName;
        $res = $this->client->request('GET', $uri);

        return $res;
    }

    private function getGenreMoiveList()
    {
        $uri = BASEURL.'/genre/movie/list?'.APIKEY.LANGUAGE;
        $res = $this->client->request('GET', $uri);

        return $res;
    }
}