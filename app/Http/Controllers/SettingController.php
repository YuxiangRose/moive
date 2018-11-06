<?php
namespace App\Http\Controllers;

use App\lib\Handlers\SettingQueryHandler;
use App\lib\Model\SettingViewModel;
use Illuminate\Routing\Controller as BaseController;

class SettingController extends BaseController
{
    public function index(
        SettingQueryHandler $settingQueryHandler,
        SettingViewModel $settingViewModel
    ){
        $data = $settingQueryHandler->getAllResourceFolderStatus();
        $viewModel = $settingViewModel->build($data);
        return view('setting')->with('data',$viewModel);
    }
}
