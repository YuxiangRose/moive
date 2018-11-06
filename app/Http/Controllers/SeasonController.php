<?php
namespace App\Http\Controllers;

use App\lib\ApiConnector\MVDB;
use App\lib\Handlers\SeasonQueryHandler;
use App\lib\Model\SeasonViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SeasonController extends Controller
{
    private $languages;

    public function __construct()
    {
        $this->languages = [
            'zh' => '中文',
            'en' => '英文',
            'cn' => '粤语'
        ];
    }

    public function index(
        Request $request,
        SeasonQueryHandler $seasonQueryHandler,
        SeasonViewModel $seasonViewModel
    )
    {
        $seasonId = $request->route()->getParameter('id');

        $data = $seasonQueryHandler->getSeasonDetails($seasonId);
        $viewModel = $seasonViewModel->build($data);

        return view('season-details')->with('data', $viewModel);
    }
}