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
        <script type="text/javascript" src="assets/js/circle-progress.min.js"></script>
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
                    @if ($data->isWaiting == 0)
                    <div class="play-btn" id="{{$data->path}}">
                        播放
                        <i class="fas fa-play"></i>
                    </div>
                    @elseif ($data->isWaiting == 2)
                    <div class="add-to-btn" id="{{$data->id}}">
                        添加到愿望清单
                    </div>
                    @elseif ($data->isWaiting == 1)
                        <div class="in-wishing-list" id="{{$data->id}}">
                            等待下载
                        </div>
                    @endif
                </div>
                <div class="left detail-container">
                    <div class="movie-name">
                        {{$data->title}}
                    </div>
                    <div class="rating">
                        <div class="left score-title">平均得分：</div>
                        <div class="left score">{{$data->rating * 10}}</div>
                        <div class="left rating-circle" id="circle">
                        </div>
                    </div>
                    <div class="language">
                        {{$data->originalLanguage}}
                    </div>
                    <div class="original-name">
                        <i>{{$data->originalTitle}}</i>
                    </div>
                    <div class="release-date">
                        上映日期：{{$data->releaseDate}}
                    </div>
                    <div class="release-date">
                        @if($data->budget)
                            <span class="budget">投资：$ {{$data->budget}}</span>
                        @endif
                        @if($data->revenue)
                        <span class="revenue">票房：$ {{$data->revenue}}</span>
                        @endif
                    </div>
                    @if($data->runtime > 0)
                    <div class="release-date">
                        影片时长： {{$data->runtime}} 分钟
                    </div>
                    @endif
                    @if($data->collectionId)
                        <div id="{{$data->collectionId}}" class="collection">
                            {{$data->collectionName}}
                        </div>
                    @endif
                    <div class="overview">
                        <label>剧情简介：</label>
                        <p>
                           {{$data->overview}}
                        </p>
                    </div>
                    <div class="tags">
                        <ul class="tag-list">
                            @if($data->genres)
                                @foreach($data->genres as $key => $genre)
                                    <li class="movie-tag" id="{{$key}}">
                                        {{$genre}}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </main>
        <script type="text/javascript" src="assets/js/rating.js"></script>
    </body>
</html>
{{--<div class="content-container">--}}
    {{--<div class="background-image">--}}
        {{--<img src="https://image.tmdb.org/t/p/w1400_and_h450_face/zjOj2gnDJYFdYt6R7FtuHn7yrPr.jpg">--}}
    {{--</div>--}}
{{--</div>--}}