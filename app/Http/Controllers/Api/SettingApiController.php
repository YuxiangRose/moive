<?php
namespace App\Http\Controllers\Api;

use App\lib\ApiConnector\MVDB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingApiController extends BaseController
{
    private $avaliableFormat;

    private $mvdb;

    private $languages;

    private $allFolders = [];

    public function __construct()
    {
        $this->avaliableFormat = array('mp4', 'rmvb', 'mkv', 'avi', 'flv');
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
        $this->mvdb = new MVDB();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function validateFolder(Request $request)
    {
//        $folderNames = $request->input('folderNames');
//        $foldersWithFiles = [];
        $folderName = $request->input('folderName');
        $type = $request->input('type');
//        foreach ($folderNames as $folderName) {
//            $files = self::rglob($folderName.'/*', 0);
//
//            foreach ($files as $key => $file) {
//                $ext = pathinfo($file, PATHINFO_EXTENSION);
//                if (is_dir($file) || !in_array($ext, $this->avaliableFormat)) {
//                    unset($files[$key]);
//                }
//            }
//            if(count($files) > 0 ){
//                $foldersWithFiles[$folderName] = array_values($files);
//            }
//        }

        $files = self::rglob($folderName . '\*', 0);
        foreach ($files as $key => $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (is_dir($file) || !in_array($ext, $this->avaliableFormat)) {
                unset($files[$key]);
            }
        }
        if (count($files) > 0) {
            //$foldersWithFiles[$folderName] = array_values($files);
            if (!DB::table('resource_folder')->where('path', $folderName)->first()) {
                DB::table('resource_folder')->insert([
                    'path' => $folderName,
                    'type' => $type,
                    'created_at' => new \DateTime('now'),
                    'updated_at' => stat($folderName)["mtime"],
                    'init' => 1
                ]);

                return $folderName;
            } else {
                return 'exist';
            }
        }

        return 'invalidate';


//        foreach ($foldersWithFiles as $folderName => $files) {
//            if(!DB::table('resource_folder')->where('path', $folderName)->first()){
//                DB::table('resource_folder')->insert([
//                    'path' => $folderName,
//                    'type' => $type,
//                    'created_at' => new \DateTime('now'),
//                    'updated_at' => stat($folderName)["mtime"],
//                ]);
//            }
//        }
//
//        return $foldersWithFiles;
    }

    public function syncFolders(Request $request)
    {
        $updatedTime = [];
        $type = $request->input('type');
        $folderObjects = DB::table('resource_folder')
            ->where('type', 'movie')
            ->get();
        $folders = array();
        foreach ($folderObjects as $folderObject) {
            $stat = stat($folderObject->path);
            if ($folderObject->init > 0) {
                $folders[] = $folderObject->path;
                $updatedTime[$folderObject->path] = $stat['mtime'];
            } else {
                $this->getAllFolders($folderObject->path);
                foreach ($this->allFolders as $subFolder){
                    $stat = stat($subFolder);
                    if ($stat['mtime'] > $folderObject->updated_at) {
                        $folders[] = $folderObject->path;
                        $updatedTime[$folderObject->path] = $stat['mtime'];
                        break;
                    }
                }
                $this->allFolders = [];
            }
        }
        $movies = [];
        $synced = 0;
        $failed = 0;
        $ignore = 0;
        if (count($folders) > 0) {
            foreach ($folders as $folder) {
                $files = self::rglob($folder . '/*', 0);
                foreach ($files as $key => $file) {
                    $ext = pathinfo($file, PATHINFO_EXTENSION);
                    if (is_dir($file) || !in_array($ext, $this->avaliableFormat)) {
                        unset($files[$key]);
                    }
                }
                if (count($files) > 0) {
                    $files = array_values($files);
                    $movies = array_merge($movies, $files);
                }
            }

            /**
             * debug code for looking un match number folder and movies
             */
//            $count = 0;
//            var_dump(count($movies));
//
//            foreach ($movies as $movie) {
//                $movie = str_replace('/', '\\', $movie);
//                if(!DB::table('movies')->where('path', $movie)->first()) {
//                    var_dump($movie);
//                }
//                $count++;
//            }
//
//            dd($count);
            /**
             *
             */

            DB::table('fail_sync_movies')->truncate();
            foreach ($movies as $movie) {
                $movie = str_replace('/', '\\', $movie);
                $name = $this->getFileName($movie);
                $year = '';
                if (!DB::table('movies')->where('path', $movie)->first()) {
    
                    if(strpos($name,'_')) {
                        $year = substr($name, strpos($name,'_')+1, 4);
                        $name = substr($name, 0, strpos($name,'_'));
                    }

                    $results = $this->mvdb->getMovieSearchResult($name)->results;
                    if (isset($results[0])) {
                        $posterPath = false;
                        $movieId = $results[0]->id ? $results[0]->id : NULL;

                        if ($results[0]->title != $name) {
                            foreach ($results as $result) {
                                if ($result->title == $name) {
                                    $movieId = $result->id;
                                    break;
                                }
                            }
                        }

                        if($year != '') {
                            foreach ($results as $result){
                                if(strpos($result->release_date, $year) !== false){
                                    $movieId = $result->id;
                                    break;
                                }
                            }
                        }


//                        if ($results[0]->title != $name){
//                            foreach ($results as $result){
//                                if($result->title == $name){
//                                    $movieId = $result->id;
//                                    break;
//                                }
//                            }
//                        } else {
//                            if($year != '') {
//                                foreach ($results as $result){
//                                    if(strpos($result->release_date, $year) !== false){
//                                        $movieId = $result->id;
//                                        break;
//                                    }
//                                }
//                            }
//                        }

                        if ($movieId) {
                            $movieDetails = $this->mvdb->getMovieDetailsById($movieId);
                        } else {
                            $movieDetails = $results[0];
                        }
                        if ($movieId) {
                            $posterPath = $this->getPosterAndStore($movieId, $movieDetails->original_language);
                        }
                        $movieId = $movieDetails->id ? $movieDetails->id : NULL;
                        $title = $movieDetails->title ? $movieDetails->title : NULL;
                        $originalLanguage = isset($this->languages[$movieDetails->original_language]) ? $this->languages[$movieDetails->original_language] : $movieDetails->original_language;
                        if ($movieDetails->belongs_to_collection) {
                            $collection_name = $movieDetails->belongs_to_collection->name;
                            $collection_id = $movieDetails->belongs_to_collection->id;
                        } else {
                            $collection_name = null;
                            $collection_id = null;
                        }
                        $genreIds = $movieDetails->genres;
                        if ($posterPath) {
                            $waitingForSync = DB::table('movies')->where('id', $movieId)->first();
                            if (!$waitingForSync) {
                                DB::table('movies')->insert([
                                    'id' => $movieId,
                                    'title' => $title,
                                    'path' => $movie,
                                    'original_title' => $movieDetails->original_title,
                                    'overview' => $movieDetails->overview,
                                    'release_date' => $movieDetails->release_date,
                                    'original_language' => $originalLanguage,
                                    'poster_path' => $posterPath,
                                    'waiting' => 0,
                                    'collection_name' => $collection_name,
                                    'collection_id' => $collection_id,
                                    'runtime' => $movieDetails->runtime ? $movieDetails->runtime : Null,
                                    'budget' => $movieDetails->budget ? $movieDetails->budget : Null,
                                    'revenue' => $movieDetails->revenue ? $movieDetails->revenue : Null,
                                    'rating' => $movieDetails->vote_average ? $movieDetails->vote_average : Null
                                ]);

                                if (!DB::table('movie_genres')->where('movie_id', $movieId)->first()) {
                                    foreach ($genreIds as $genreId) {
                                        DB::table('movie_genres')->insert([
                                            'movie_id' => $movieId,
                                            'genre_id' => $genreId->id,
                                        ]);
                                    }
                                }
                                $synced++;
                            } else {
                                if ($waitingForSync->waiting) {
                                    DB::table('movies')->where('id', $movieId)
                                        ->update([
                                            'waiting' => 0,
                                            'path' => $movie,
                                        ]);
                                    $synced++;
                                } else {
                                    $ignore++;
                                    $failedMovie = DB::table('fail_sync_movies')->where('path',$movie)->first();
                                    if(!$failedMovie) {
                                        DB::table('fail_sync_movies')->insert([
                                            'path' => $movie,
                                            'name' => $name
                                        ]);
                                    }
                                }
                            }
                        } else {
                            throw new Exception('Please try again');
                        }
                    } else {
                        $failed++;
                        $failedMovie = DB::table('fail_sync_movies')->where('path',$movie)->first();
                        if(!$failedMovie) {
                            DB::table('fail_sync_movies')->insert([
                                'path' => $movie,
                                'name' => $name
                            ]);
                        }
                    }
                    usleep(500000);
                } else {
                    $ignore++;
//                    $failedMovie = DB::table('fail_sync_movies')->where('path',$movie)->first();
//                    if(!$failedMovie) {
//                        DB::table('fail_sync_movies')->insert([
//                            'path' => $movie,
//                            'name' => $name
//                        ]);
//                    }
                }
            }
            if ($failed == 0) {
                foreach ($folders as $folder) {
//                $stat = stat($folder);
                    if(isset($updatedTime[$folder])){
//                    dd('423432432');
                        $lastSynced = $updatedTime[$folder];
                        DB::table('resource_folder')
                            ->where('path', $folder)
                            ->update([
                                'init' => 0,
                                'updated_at' => $lastSynced
                            ]);
                    }
                }
            }

            return [
                'synced' => $synced,
                'failed' => $failed,
                'ignore' => $ignore
            ];
        }
    }

    public function syncShow()
    {
        $updatedTime = [];
        $folderObjects = DB::table('resource_folder')
            ->where('type', 'show')
            ->get();
        $folders = array();
        foreach ($folderObjects as $folderObject) {
            $stat = stat($folderObject->path);
            if ($folderObject->init > 0) {
                $folders[] = $folderObject->path;
                $updatedTime[$folderObject->path] = $stat['mtime'];
            } else {
                $this->getAllFolders($folderObject->path);
                foreach ($this->allFolders as $subFolder){
                    $stat = stat($subFolder);
                    if ($stat['mtime'] > $folderObject->updated_at) {
                        $folders[] = $folderObject->path;
                        $updatedTime[$folderObject->path] = $stat['mtime'];
                        break;
                    }
                }
                $this->allFolders = [];
            }
        }
        $showSynced = 0;
        $seasonSynced = 0;
        $episodeSynced = 0;
        $showSyncedFailed = 0;
        if (count($folders) > 0) {
            foreach ($folders as $folder) {
                $shows = glob($folder . '\*', GLOB_ONLYDIR);
                
                foreach ($shows as $show) {
                    $posterPath = false;
                    $name = $this->getShowName($show);
                    $year = '';
                    if(strpos($name,'_')) {
                        $year = substr($name, strpos($name,'_')+1, 4);
                        $name = substr($name, 0, strpos($name,'_'));
                    }
                    $results = $this->mvdb->getShowsSearchResult($name)->results;
                    
                    if (isset($results[0])) {
                        $showId = $results[0]->id ? $results[0]->id : NULL;

                        if ($results[0]->name != $name){
                            foreach ($results as $result){
                                if($result->name == $name){
                                    $showId = $result->id;
                                    break;
                                }
                            }
                        } else {
                            if($year != '') {
                                foreach ($results as $result){
                                    if(strpos($result->first_air_date, $year) !== false){
                                        $showId = $result->id;
                                        break;
                                    }
                                }
                            }
                        }
                        $showDetails = $this->mvdb->getShowsDetailsById($showId);
                        $showInDB = DB::table('shows')->where('id', $showDetails->id)->first();
                        if (!$showInDB) {
                            if ($showDetails->id == $showId) {
                                $posterPath = $this->getShowPosterAndStore($showDetails->poster_path);
                            }
                            if ($posterPath) {
                                DB::table('shows')->insert([
                                    'id' => $showDetails->id,
                                    'first_air_date' => $showDetails->first_air_date,
                                    'last_air_date' => $showDetails->last_air_date,
                                    'in_production' => $showDetails->in_production,
                                    'title' => $showDetails->name,
                                    'number_of_episodes' => $showDetails->number_of_episodes,
                                    'number_of_seasons' => $showDetails->number_of_seasons,
                                    'language' => isset($this->languages[$showDetails->original_language]) ? $this->languages[$showDetails->original_language] : $showDetails->original_language,
                                    'poster_path' => $posterPath,
                                    'original_name' => $showDetails->original_name,
                                    'overview' => $showDetails->overview,
                                    'rating' => $showDetails->vote_average
                                ]);
                                $showSynced++;
                                if (!DB::table('movie_genres')->where('movie_id', $showDetails->id)->first()) {
                                    foreach ($showDetails->genres as $genreId) {
                                        DB::table('movie_genres')->insert([
                                            'movie_id' => $showDetails->id,
                                            'genre_id' => $genreId->id,
                                        ]);
                                    }
                                }
                                if ($showDetails->seasons && ($showDetails->number_of_seasons !=$seasonSynced)) {
                                    foreach ($showDetails->seasons as $season) {
                                        $seasonInDB = DB::table('seasons')->where('season_id', $season->id)->first();
                                        if (!$season->poster_path) {
                                            $seasonPoster = '/pic-404.jpg';
                                        }else {
                                            $seasonPoster = $this->getShowPosterAndStore($season->poster_path);
                                        }
                                        if (!$seasonInDB && $season->season_number > 0 ) {
                                            //var_dump('9504-3950-439543-0');
                                            //($showDetails->id,$season->air_date,$season->overview,$seasonPoster,$season->season_number,$season->id,$season->episode_count,$showDetails->name);
                                            DB::table('seasons')->insert([
                                                'show_id' => $showDetails->id,
                                                'air_date' => $season->air_date,
                                                'overview' => $season->overview,
                                                'poster_path' => $seasonPoster,
                                                'season_number' => $season->season_number,
                                                'season_id' => $season->id,
                                                'episode_count' => $season->episode_count,
                                                'show_name' => $showDetails->name
                                            ]);
                                            //dd('fsdfdsf');

                                            $seasonSynced++;
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($showInDB->number_of_episodes != $showDetails->number_of_episodes) {
                                DB::table('shows')
                                    ->where('id', $showDetails->id)
                                    ->update([
                                        'id' => $showDetails->id,
                                        'first_air_date' => $showDetails->first_air_date,
                                        'last_air_date' => $showDetails->last_air_date,
                                        'in_production' => $showDetails->in_production,
                                        'title' => $showDetails->name,
                                        'number_of_episodes' => $showDetails->number_of_episodes,
                                        'number_of_seasons' => $showDetails->number_of_seasons,
                                        'language' => isset($this->languages[$showDetails->original_language]) ? $this->languages[$showDetails->original_language] : $showDetails->original_language,
                                        'original_name' => $showDetails->original_name,
                                        'overview' => $showDetails->overview,
                                        'rating' => $showDetails->vote_average
                                    ]);
                                $showSynced++;
                                //$seasonSynced = DB::table('seasons')->where('show_id', $showDetails->id)->get();
                                foreach ($showDetails->seasons as $season) {
                                    $checkSynced = DB::table('seasons')->where('season_id', $season->id)->get();
                                    if (count($checkSynced) == 0 && $season->season_number > 0 && $season->air_date && $season->air_date < date('Y-m-d')) {
                                        $seasonPoster = false;
                                        if (!$season->poster_path) {
                                            $seasonPoster = '/pic-404.jpg';
                                        } else {
                                            $seasonPoster = $this->getShowPosterAndStore($season->poster_path);
                                        }
                                        DB::table('seasons')->insert([
                                            'show_id' => $showDetails->id,
                                            'air_date' => $season->air_date,
                                            'overview' => $season->overview,
                                            'poster_path' => $seasonPoster,
                                            'season_number' => $season->season_number,
                                            'season_id' => $season->id,
                                            'episode_count' => $season->episode_count,
                                            'show_name' => $showDetails->name
                                        ]);
                                        $seasonSynced++;
                                    } else {
                                        DB::table('seasons')
                                            ->where('season_id', $season->id)
                                            ->update([
                                                'show_id' => $showDetails->id,
                                                'air_date' => $season->air_date,
                                                'overview' => $season->overview,
                                                'season_number' => $season->season_number,
                                                'season_id' => $season->id,
                                                'episode_count' => $season->episode_count
                                            ]);
                                    }
                                    $seasonSynced++;
                                }
                            }
                        }
                        $seasonIds = DB::table('seasons')->where('show_id', $showDetails->id)->get();
                        $seasonFolders = glob($show . '\*', GLOB_ONLYDIR);
                        if (count($seasonFolders) > 0) {
                            foreach ($seasonFolders as $seasonFolder) {
                                $seasonNumber = $this->getSeasonNumber($seasonFolder);
                                $seasonId = null;
                                foreach ($seasonIds as $data) {
                                    if ($data->season_number == $seasonNumber) {
                                        $seasonId = $data->season_id;
                                    }
                                }
                                $episodes = glob($seasonFolder . '\*', 0);
                                foreach ($episodes as $episode) {
                                    $ext = pathinfo($episode, PATHINFO_EXTENSION);
                                    if (in_array($ext, $this->avaliableFormat)) {
                                        $episodeInDB = DB::table('episodes')->where('path', $episode)->first();
                                        if (!$episodeInDB) {
                                            DB::table('episodes')->insert([
                                                'path' => $episode,
                                                'season_number' => $seasonNumber,
                                                'season_id' => $seasonId,
                                                'name' => basename($episode),
                                                'show_id' => $showDetails->id,
                                                'latest_watch' => 0
                                            ]);
                                            $episodeSynced++;
                                        }
                                    }
                                }
                            }
                        }
                    } else{
                        $showSyncedFailed++;
                        $failed = DB::table('fail_sync_movies')->where('path',$show)->first();
                        if(!$failed) {
                            DB::table('fail_sync_movies')->insert([
                                'path' => $show,
                                'name' => $name
                            ]);
                        }
                    }
                    usleep(500000);
                }
            }
        }

        foreach ($folders as $folder) {
            $stat = stat($folder);
            if(isset($updatedTime[$folder])){
                $lastSynced = $updatedTime[$folder];
                DB::table('resource_folder')
                    ->where('path', $folder)
                    ->update([
                        'init' => 0,
                        'updated_at' => $lastSynced
                    ]);
            }
        }

        return [
            'showSynced' => $showSynced,
            'seasonSynced' => $seasonSynced,
            'episodeSynced' => $episodeSynced,
            'showSyncedFailed' =>$showSyncedFailed
        ];
    }

    public function addMovieToWishingList(Request $request)
    {
        $posterPath = '';
        $id = $request->input('id');
        $movieDetails = $this->mvdb->getMovieDetailsById($id);
        $movieId = $movieDetails->id ? $movieDetails->id : NULL;
        $title = $movieDetails->title ? $movieDetails->title : NULL;
        $originalLanguage = isset($this->languages[$movieDetails->original_language]) ? $this->languages[$movieDetails->original_language] : $movieDetails->original_language;
        $genreIds = $movieDetails->genres;
        if ($id) {
            $posterPath = $this->getPosterAndStore($id, $movieDetails->original_language);
        }
        if ($movieDetails->belongs_to_collection) {
            $collection_name = $movieDetails->belongs_to_collection->name;
            $collection_id = $movieDetails->belongs_to_collection->id;
        } else {
            $collection_name = null;
            $collection_id = null;
        }
        DB::table('movies')->insert([
            'id' => $movieId,
            'title' => $title,
            'path' => NULL,
            'original_title' => $movieDetails->original_title,
            'overview' => $movieDetails->overview,
            'release_date' => $movieDetails->release_date,
            'original_language' => $originalLanguage,
            'poster_path' => $posterPath,
            'waiting' => 1,
            'collection_name' => $collection_name,
            'collection_id' => $collection_id,
            'runtime' => $movieDetails->runtime ? $movieDetails->runtime : Null,
            'budget' => $movieDetails->budget ? $movieDetails->budget : Null,
            'revenue' => $movieDetails->revenue ? $movieDetails->revenue : Null,
            'rating' => $movieDetails->vote_average ? $movieDetails->vote_average : Null
        ]);

        if (!DB::table('movie_genres')->where('movie_id', $movieId)->first()) {
            foreach ($genreIds as $genreId) {
                DB::table('movie_genres')->insert([
                    'movie_id' => $movieId,
                    'genre_id' => $genreId->id,
                ]);
            }
        }

        return count(DB::table('movies')->where('id', $movieId)->first());
    }

    private function rglob($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        //dd($files);
        //dd(glob(dirname($pattern).'/*'));
        //dd(glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT));
        foreach (glob(dirname($pattern) . '\*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            //dd($dir . '/' . basename($pattern));
            //dd(self::rglob($dir . '/*' , $flags));

            //$files = array_merge($files, self::rglob($dir . '/' . basename($pattern), $flags));
            $files = array_merge($files, self::rglob($dir . '\\*', $flags));
        }
        return $files;
    }

    private function getFileName($path)
    {
        $indexOfFileStart = strrpos($path, '\\');
        $indexOfFileEnd = strrpos($path, '.');
        $name = substr($path, $indexOfFileStart + 1, $indexOfFileEnd - $indexOfFileStart - 1);

        return $name;
    }

    private function getShowName($path)
    {
        $dirName = dirname($path);
        $dirName = str_replace($dirName, '', $path);
        $name = str_replace('\\', '', $dirName);

        return $name;

        //return basename(dirname($path));
    }

    private function getPosterAndStore($movieId, $language)
    {
        if ($language == 'zh' || $language == 'cn') {
            $language = 'zh';
        }
        if (!in_array($language, $this->languages)) {
            $language = null;
        }
        $posters = $this->mvdb->getMoviePosterByLanguage($movieId, $language);
        if(isset($posters[0])){
            $filePath = $posters[0]->file_path;
            $exists = Storage::disk()->exists('public/poster' . $filePath);
            if (!$exists) {
                $url = 'https://image.tmdb.org/t/p/w300/' . $filePath;
                $contents = file_get_contents($url);
                Storage::put('public/poster' . $filePath, $contents);
                $exists = Storage::disk()->exists('public/poster' . $filePath);
            }
        } else {
            $filePath = '/pic-404.jpg';
            $exists = Storage::disk()->exists('public/poster' . $filePath);
        }

        if ($exists) {
            return $filePath;
        }

        return false;
    }

    private function getShowPosterAndStore($filePath)
    {
        $exists = Storage::disk()->exists('public/poster' . $filePath);
        if (!$exists) {
            $url = 'https://image.tmdb.org/t/p/w300/' . $filePath;
            $contents = file_get_contents($url);
            Storage::put('public/poster' . $filePath, $contents);
            $exists = Storage::disk()->exists('public/poster' . $filePath);
        }

        if ($exists) {
            return $filePath;
        }

        return false;
    }

    private function getSeasonNumber($folderName)
    {
        $index = stripos($folderName, 'season');
        $name = substr($folderName, $index);
        $name = str_ireplace("season", "", $name);
        $number = trim($name);

        return $number;
    }

    public function updateLastWatch(Request $request)
    {
        $path = $request->input('path');
        $seasonId = DB::table('episodes')
            ->select('season_id')
            ->where('path', $path)
            ->first()
            ->season_id;

        DB::table('episodes')
            ->where('season_id', $seasonId)
            ->update([
                'latest_watch' => 0
            ]);

        DB::table('episodes')
            ->where('path', $path)
            ->update([
                'latest_watch' => 1
            ]);

    }

    public function addShowToLibrary(Request $request)
    {
        $id = $request->input('id');
        $showDetails = $this->mvdb->getShowsDetailsById($id);
        $showInDB = DB::table('shows')->where('id', $showDetails->id)->first();
        if (!$showInDB){
            DB::table('shows')->insert([
                'id' => $showDetails->id,
                'first_air_date' => $showDetails->first_air_date,
                'last_air_date' => $showDetails->last_air_date,
                'in_production' => $showDetails->in_production,
                'title' => $showDetails->name,
                'number_of_episodes' => $showDetails->number_of_episodes,
                'number_of_seasons' => $showDetails->number_of_seasons,
                'language' => isset($this->languages[$showDetails->original_language]) ? $this->languages[$showDetails->original_language] : $showDetails->original_language,
                'poster_path' => $showDetails->poster_path,
                'original_name' => $showDetails->original_name,
                'overview' => $showDetails->overview,
                'rating' => $showDetails->vote_average
            ]);
            if (!DB::table('movie_genres')->where('movie_id', $showDetails->id)->first()) {
                foreach ($showDetails->genres as $genreId) {
                    DB::table('movie_genres')->insert([
                        'movie_id' => $showDetails->id,
                        'genre_id' => $genreId->id,
                    ]);
                }
            }
        }
        foreach ($showDetails->seasons as $season) {
            $seasonInDB = DB::table('seasons')->where('season_id', $season->id)->first();
            $seasonPoster = false;
            $seasonPoster = $this->getShowPosterAndStore($season->poster_path);
            if ($seasonPoster && !$seasonInDB) {
                DB::table('seasons')->insert([
                    'show_id' => $showDetails->id,
                    'air_date' => $season->air_date,
                    'overview' => $season->overview,
                    'poster_path' => $seasonPoster,
                    'season_number' => $season->season_number,
                    'season_id' => $season->id,
                    'episode_count' => $season->episode_count,
                    'show_name' => $showDetails->name
                ]);
            }
        }
        return count(DB::table('shows')->where('id', $showDetails->id)->first());
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

    public function clearBuff(){
        $moviePostersInUse = DB::table('movies')->pluck('poster_path');
        $seasonPostersInUse = DB::table('seasons')->pluck('poster_path');
        $showPostersInUse = DB::table('shows')->pluck('poster_path');
        $postersInUse = $moviePostersInUse->merge($seasonPostersInUse)->merge($showPostersInUse);
        $files = Storage::allFiles('public/poster');
        $counter = 0;
        foreach($files as $file){
            $fileName = \str_replace('public/poster', '', $file);
            $inUse = $postersInUse->contains($fileName);
            if (!$inUse && $fileName != 'pic-404.jpg'){
                Storage::delete($file);
                $counter++;
            }
        }

        return $counter;
    }
}
