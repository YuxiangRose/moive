<?php
namespace App\lib\Model;

class SettingViewModel
{
    public $movieFolders;

    public $showFolders;

    public $allFolders = [];

    public function build($data)
    {
        foreach ($data['movieResourceFolders'] as $folder) {
            $folder->needUpdate = false;
            $this->getAllFolders($folder->path);
            foreach ($this->allFolders as $subFolder){
                $stat = stat($subFolder);
                if ($stat['mtime'] > $folder->updated_at) {
                    $folder->needUpdate = true;
                    break;
                }
            }
            $this->allFolders = [];
            //$stat = stat($folder->path);
            //$folder->needUpdate = ($stat['mtime'] != $folder->updated_at) ? true: false;
            if ($folder->init > 0)
            {
                $folder->needUpdate = true;
            }
        }
        $this->movieFolders = $data['movieResourceFolders'];

        foreach ($data['tvShowResourceFolders'] as $folder)
        {
            $folder->needUpdate = false;
            $this->getAllFolders($folder->path);
            foreach ($this->allFolders as $subFolder){
                $stat = stat($subFolder);
                if ($stat['mtime'] > $folder->updated_at) {
                    $folder->needUpdate = true;
                    break;
                }
            }
            $this->allFolders = [];
            //$stat = stat($folder->path);
            //$folder->needUpdate = ($stat['mtime'] != $folder->updated_at) ? true: false;
            if ($folder->init > 0)
            {
                $folder->needUpdate = true;
            }
        }
        $this->showFolders = $data['tvShowResourceFolders'];
        return $this;
    }

    public function getAllFolders($folder){
        $this->allFolders[] = $folder;
        $folders = glob($folder . '\*', GLOB_ONLYDIR);
        foreach ($folders as $folder)
        {
            $subfolders= glob($folder . '\*', GLOB_ONLYDIR);
            if (count($subfolders)) {
                $this->getAllFolders($folder);
            } else {
                $this->allFolders[] = $folder;
            }
        }
    }
}