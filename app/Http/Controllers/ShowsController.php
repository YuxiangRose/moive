<?php
namespace App\Http\Controllers;

use App\lib\Handlers\ShowsAllQueryHandler;
use App\lib\Model\ShowsAllViewModel;
use Illuminate\Http\Request;

class ShowsController extends Controller
{
    public function index(
        ShowsAllQueryHandler $showsAllQueryHandler,
        ShowsAllViewModel $showsAllViewModel
    )
    {
        $data = $showsAllQueryHandler->getAllShows();
        $viewModel = $showsAllViewModel->build($data);
        return view('shows-all')->with('data', $viewModel);
    }

    public function searchByName(
        Request $request,
        ShowsAllQueryHandler $showsAllQueryHandler,
        ShowsAllViewModel $showsAllViewModel
    )
    {
        $showName  = $request->input('title');
        $data = $showsAllQueryHandler->getShowsByName($showName);
        $viewModel = $showsAllViewModel->build($data);

        return view('shows-all')->with('data', $viewModel);
    }

    public function searchByTag(
        Request $request,
        ShowsAllQueryHandler $showsAllQueryHandler,
        ShowsAllViewModel $showsAllViewModel
    )
    {
        $showTag  = $request->input('tag');
        $data = $showsAllQueryHandler->getShowsByTag($showTag);
        $viewModel = $showsAllViewModel->build($data);

        return view('shows-all')->with('data', $viewModel);
    }
}