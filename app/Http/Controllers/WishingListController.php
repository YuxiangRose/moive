<?php
namespace App\Http\Controllers;

use App\lib\Handlers\WishingListQueryHandler;
use App\lib\Model\WishingListViewModel;
use Illuminate\Http\Request;

class WishingListController extends Controller
{
    public function index(
        WishingListQueryHandler $wishingListQueryHandler,
        WishingListViewModel $wishingListViewModel
    )
    {
        $data = $wishingListQueryHandler->getAllWhishingMovies();
        $viewModel = $wishingListViewModel->build($data);

        return view('wishing-list')->with('data', $viewModel);
    }
}