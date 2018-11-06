<?php
namespace App\Http\Controllers;

use App\lib\Handlers\InitialHandler;
use App\lib\Handlers\MainPageQueryHandler;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use App\lib\Model\MainPageViewModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;


class MainPageController extends BaseController
{
    public function __construct(
       InitialHandler $initialHandler
    )
    {
        //$initialHandler->init();
    }

    public function index(
        MainPageQueryHandler $mainPageQueryHandler,
        MainPageViewModel $mainPageViewModel
    ){
//        $url = 'https://image.tmdb.org/t/p/w300/9JyHlYJGpGspSvOC1rXgFk4dySH.jpg';
//        $contents = file_get_contents($url);
//        $name = '9JyHlYJGpGspSvOC1rXgFk4dySH.jpg';
//        Storage::put('public/poster/'.$name, $contents);
//        var_dump('fsdfsd');
//        die;

        //$exists = Storage::exists('poster/fIeU08t8EzDwxOcImSGOrY1AvvN.jpg');
//        $exists = Storage::disk()->exists('public/poster/fIeU08t8EzDwxOcImSGOrY1AvvN.jpg');
//        $size = Storage::size('public/poster/fIeU08t8EzDwxOcImSGOrY1AvvN.jpg');
//        dd($exists);
















        $data = $mainPageQueryHandler->getMainPageData();
        $viewModel = $mainPageViewModel->build($data);

        return view('welcome')->with('data',$viewModel);
    }

//    public function index()
//    {
//        $x = DB::table('foo')->get();
//        var_dump($x);
//        //exec("QQplayer G:\电影天堂\国产\寒战2.mkv");
////        $curl = curl_init();
////
////        curl_setopt_array($curl, array(
////            CURLOPT_URL => "http://api.douban.com/v2/movie/subject/1764796",
////            CURLOPT_RETURNTRANSFER => true,
////            CURLOPT_TIMEOUT => 30,
////            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
////            CURLOPT_CUSTOMREQUEST => "GET",
////            CURLOPT_HTTPHEADER => array(
////                "cache-control: no-cache"
////            ),
////        ));
////
////        $response = curl_exec($curl);
////        $err = curl_error($curl);
////
////        curl_close($curl);
////        $data = json_decode($response);
////        var_dump($data);
////        var_dump($data->rating);
////        $value = Cache::get('movies');
////        if(!$value) {
////            var_dump('34234333');
////            Cache::store('file')->forever('movies', '321321');
////            $value = Cache::get('movies');
////        }
//        //var_dump($value);
//        //return view('welcome', ['data' => json_decode($response)]);
//        return view('welcome');
//    }
}
