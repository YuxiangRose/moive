<?php
namespace App\Http\Controllers;

use App\lib\ApiConnector\MVDB;
use App\lib\Handlers\ShowDetailsQueryHandler;
use App\lib\Model\ShowDetailsViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShowDetailsController extends Controller
{
    private $languages;

    public function __construct()
    {
        $this->languages =  [
            'zh' => '中文',
            'en' => '英文',
            'cn' => '粤语',
            'fr' => '法语',
            'ko' => '韩语',
            'ja' => '日语',
            'de' => '德语',
            'es' => '西班牙语',
            'it' => '意大利语',
            'hi' => '印地语',
        ];
    }

    public function index(
        Request $request,
        ShowdetailsQueryHandler $showDetailsQueryHandler,
        ShowDetailsViewModel $showDetailsViewModel
    )
    {
        $showId = $request->route()->getParameter('id');
        $data = $showDetailsQueryHandler->getShowDetails($showId);
        if (!$data['show']) {
            $data = $showDetailsQueryHandler->getShowDataFromOnLine($showId);
        }
        $viewModel = $showDetailsViewModel->build($data);

        return view('show-details')->with('data', $viewModel);
    }
}