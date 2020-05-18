<?php
namespace App\Http\Controllers;

use App\lib\Handlers\MoviesAllQueryHandler;
use App\lib\Model\MoviesAllViewModel;
use Illuminate\Http\Request;

class MoviesController extends Controller
{
    public function index(
        MoviesAllQueryHandler $moviesAllQueryHandler,
        MoviesAllViewModel $moviesAllViewModel
    )
    {
        $data = $moviesAllQueryHandler->getAllMovies();
        $viewModel = $moviesAllViewModel->build($data);

        return view('movie-all')->with('data', $viewModel);
    }

    public function searchByName(
        Request $request,
        MoviesAllQueryHandler $moviesAllQueryHandler,
        MoviesAllViewModel $moviesAllViewModel
    )
    {
        $movieName  = $request->input('title');
        $data = $moviesAllQueryHandler->getMoviesByName($movieName);
        $viewModel = $moviesAllViewModel->build($data);

        return view('movie-all')->with('data', $viewModel);
    }

    public function searchByTag(
        Request $request,
        MoviesAllQueryHandler $moviesAllQueryHandler,
        MoviesAllViewModel $moviesAllViewModel
    )
    {
        $movieTag  = $request->input('tag');
        $data = $moviesAllQueryHandler->getMoviesByTag($movieTag);
        $viewModel = $moviesAllViewModel->build($data);

        return view('movie-all')->with('data', $viewModel);
    }

    public function searchByCollection(
        Request $request,
        MoviesAllQueryHandler $moviesAllQueryHandler,
        MoviesAllViewModel $moviesAllViewModel
    )
    {
        $collectionId  = $request->input('collection');
        $data = $moviesAllQueryHandler->getMoviesByCollection($collectionId);
        $viewModel = $moviesAllViewModel->build($data);

        return view('movie-all')->with('data', $viewModel);
    }

    public function searchByLanguage(
        Request $request,
        MoviesAllQueryHandler $moviesAllQueryHandler,
        MoviesAllViewModel $moviesAllViewModel
    )
    {
        $language  = trim($request->input('language'));
        $data = $moviesAllQueryHandler->getMoviesByLanguage($language);
        $viewModel = $moviesAllViewModel->build($data);

        return view('movie-all')->with('data', $viewModel);
    }
}