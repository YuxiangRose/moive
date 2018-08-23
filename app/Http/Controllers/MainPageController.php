<?php

namespace App\Http\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

class MainPageController extends BaseController
{
    public function index()
    {
//        $curl = curl_init();
//
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => "http://api.douban.com/v2/movie/subject/1764796",
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_TIMEOUT => 30,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => "GET",
//            CURLOPT_HTTPHEADER => array(
//                "cache-control: no-cache"
//            ),
//        ));
//
//        $response = curl_exec($curl);
//        $err = curl_error($curl);
//
//        curl_close($curl);
//        $data = json_decode($response);
//        var_dump($data);
//        var_dump($data->rating);
        $value = Cache::get('movies');
        if(!$value) {
            var_dump('34234333');
            Cache::store('file')->forever('movies', '321321');
            $value = Cache::get('movies');
        }
        var_dump($value);
        //return view('welcome', ['data' => json_decode($response)]);
        //return view('welcome')->with('data', json_decode($response));
    }
}
