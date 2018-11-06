<?php
namespace App\lib\Handlers;

use Illuminate\Support\Facades\DB;

class SettingQueryHandler
{

    function __construct()
    {
    }

    public function getAllResourceFolderStatus()
    {
        return [
            'movieResourceFolders' => $this->getMovieFolders(),
            'tvShowResourceFolders' => $this->getShowFolders()
        ];
    }

    public function getMovieFolders()
    {
        $movieFolders = DB::table('resource_folder')
            ->where('type','movie')
            ->get();

        return $movieFolders;
    }

    public function getShowFolders()
    {
        $showFolders = DB::table('resource_folder')
            ->where('type','show')
            ->get();

        return $showFolders;
    }
}