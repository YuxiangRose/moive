<!DOCTYPE html>
<html>
    <head>
        <base href="/">
        <title>Setting</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        {{--<link rel="stylesheet" type="text/css" href="assets/css/normalize.css">--}}
        <link rel="stylesheet" type="text/css" href="assets/css/common.css">
        <link rel="stylesheet" type="text/css" href="assets/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/imagehover.min.css">
        <script type="text/javascript" src="assets/js/jq.js"></script>
        <script type="text/javascript" src="assets/js/script.js"></script>
    </head>
    <body>
        <header>
        <div class="header-top">
            <div class="menu-container">
                <div class="left logo">
                    <a href="/"><img src="assets/img/logo.svg"></a>
                </div>
                <div class="left menu">
                    <ul class="primary">
                        <li>
                            <a href="index.php/movies">
                                电影
                            </a>
                        </li>
                        <li>
                            <a href="index.php/shows">
                                电视剧
                            </a>
                        </li>
                        <li>
                            <a href="index.php/setting">
                                设置
                            </a>
                        </li>
                        <li>
                            <a href="index.php/wishing-list">
                                愿望清单
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="search-container right">
                    <div class="search-input-box">
                        <select class="search-type">
                            <option value="movies">电影</option>
                            <option value="shows">电视剧</option>
                        </select>
                        <input class="search-content" type="text">
                        <button>
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="movie-container">
            <div class="title-container">
                <div class="left title">
                    电影同步目录：
                </div>
            </div>
            <div class="add-folder-container add-movie-folder">
                <i class="fas fa-folder-plus"></i>
            </div>
            <div class="content-container" id="movie">
                @foreach($data->movieFolders as $movieFolder)
                    <div class="single-folder">
                        <label >{{utf8_encode ($movieFolder->path)}}</label>
                        @if($movieFolder->needUpdate)
                            <span>
                           <i class="fas fa-sync"></i>
                       </span>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="single-folder status">
                <span class="syncedIgnore status-span"></span>
                <span class="syncedFailed status-span"></span>
                <span class="syncedNumber status-span"></span>
            </div>
            <div class="bargif movie-syncing">
                <img src="assets/img/PEPloading.gif">
            </div>
            <div id="sync-movie"><span>同步</span></div>
        </div>
        <div class="show-container">
            <div class="title-container">
                <div class="left title">
                    电视剧同步目录：
                </div>
            </div>
            <div class="add-folder-container add-show-folder">
                <i class="fas fa-folder-plus"></i>
            </div>
            <div class="content-container" id="show">
                @foreach($data->showFolders as $showFolder)
                    <div class="single-folder">
                        <label >{{utf8_encode ($showFolder->path)}}</label>
                        @if($showFolder->needUpdate)
                            <span>
                           <i class="fas fa-sync"></i>
                       </span>
                        @endif
                    </div>
                @endforeach
                <div class="single-folder status">
                    <span class="showSynced status-span"></span>
                    <span class="seasonSynced status-span"></span>
                    <span class="episodeSynced status-span"></span>
                </div>
                <div class="bargif">
                    <img src="assets/img/PEPloading.gif">
                </div>
            </div>
            <div id="sync-show"><span>同步</span></div>
        </div>
        {{--<div class="movie-container">--}}
            {{--<div class="title-container">--}}
                {{--<div class="left title">--}}
                    {{--电视剧--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        </main>
        <script type="text/javascript" src="assets/js/setting.js"></script>
    </body>
</html>


{{--<div class="container" id="movie">--}}
    {{--<div class="folderContainer">--}}
        {{--<input type="text" class="folderName">--}}
    {{--</div>--}}
    {{--<div class="folderContainer">--}}
        {{--<input type="text" class="folderName">--}}
    {{--</div>--}}
    {{--<div class="validation">验证</div>--}}
    {{--<div class="sync">同步</div>--}}
{{--</div>--}}