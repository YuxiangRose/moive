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
                @if ($data->status === 'all')
                    <div class="left title">
                        电视剧
                    </div>
                @elseif ($data->status === 'local_search')
                    <div class="left title">
                        本地搜索结果：
                    </div>
                @elseif ($data->status === 'online_search')
                    <div class="left title">
                        网络搜索结果：
                    </div>
                @elseif ($data->status === 'non')
                    <div class="left title">
                        未找到相关结果：
                    </div>
                @else
                    <div class="left title">
                        {{$data->status}}
                    </div>
                @endif
                <div class="right total-movies">
                    共 {{$data->total}} 部电视剧
                </div>
            </div>
            <div class="content-container">
                @foreach($data->shows as $show)
                    <a href={{$show->link}}>
                        <div class="single-content-container">
                            <div class="poster">
                                <img src={{$show->poster_path }}>
                            </div>
                            <div class="video-title">
                                {{$show->title}}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </main>
</body>
</html>