<!DOCTYPE html>
<html>
<head>
    <base href="/">
    <title>Setting</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
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
    <div class="single-movie">
        <div class="left poster-container">
            <img src={{$data->posterPath}}>
        </div>
        <div class="left detail-container">
            <div class="show-name">
                <a class="season-show-name" href="{{$data->link}}">{{$data->title}}</a> 第 {{$data->seasonNumber}} 季
            </div>
            <div class="release-date">
                <span>首播：{{$data->airDate}}</span>
            </div>
            <div class="release-date">
                <span>共 {{$data->episodeCount}} 集</span>
            </div>
            <div class="overview">
                <label>剧情简介：</label>
                <p>
                    {{$data->overview}}
                </p>
            </div>
        </div>
    </div>
    <div class="episodes-container">
        @if ($data->episodes)
            @foreach($data->episodes as $episode)
                @if($episode->latest_watch >0)
                    <div class="episode-play-btn last-watch" id="{{$episode->path}}">
                        {{$episode->name}}
                    </div>
                @else
                    <div class="episode-play-btn" id="{{$episode->path}}">
                        {{$episode->name}}
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</main>
</body>
</html>
{{--<div class="content-container">--}}
{{--<div class="background-image">--}}
{{--<img src="https://image.tmdb.org/t/p/w1400_and_h450_face/zjOj2gnDJYFdYt6R7FtuHn7yrPr.jpg">--}}
{{--</div>--}}
{{--</div>--}}