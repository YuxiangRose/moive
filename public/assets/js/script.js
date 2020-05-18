$(document).ready(function () {
  // $('.title').click(function () {
  //     console.log("QQplayer G:\\电影天堂\\国产\\寒战2.mkv");
  //     $.ajax({
  //         type: 'POST',
  //         url: 'test.php',
  //         data: { "name": "QQplayer G:\\电影天堂\\国产\\寒战2.mkv"},
  //         success: function(data) {
  //            console.log('3423432432');
  //         }
  //     });
  // })

  $(".play-btn").click(function () {
    var path = $(this).attr("id");
    $.ajax({
      type: "POST",
      url: "script.php",
      data: { path: path },
      success: function (data) {
        console.log(data);
      },
    });
    // $.ajax({
    //     type: 'POST',
    //     url: 'index.php/api/play_video',
    //     data: { "path": path},
    //     success: function(data) {
    //         console.log(data);
    //     }
    // });
  });

  $(".episode-play-btn").click(function () {
    var path = $(this).attr("id");
    $(".episode-play-btn").removeClass("last-watch");
    $(this).addClass("last-watch");
    $.ajax({
      type: "GET",
      url: "index.php/api/update_last_watch",
      data: { path: path },
      success: function (data) {
        console.log(data);
      },
    });
    $.ajax({
      type: "POST",
      url: "script.php",
      data: { path: path },
      success: function (data) {},
    });
  });

  $(".add-to-btn").click(function () {
    var id = $(this).attr("id");
    $.ajax({
      type: "GET",
      url: "index.php/api/add_movie_to_wishing_list",
      data: {
        id: id,
      },
      success: function (data) {
        if (data > 0) {
          var url = "index.php/wishing-list";
          $(location).attr("href", url);
        }
      },
    });
  });

  $(".add-show-btn").click(function () {
    var id = $(this).attr("id");
    $.ajax({
      type: "GET",
      url: "index.php/api/add_show_to_library",
      data: {
        id: id,
      },
      success: function (data) {
        if (data > 0) {
          var url = "index.php/shows";
          $(location).attr("href", url);
        }
      },
    });
  });

  $(".fa-search").click(function (event) {
    event.preventDefault();
    let type = $(".search-type").val();
    let key = $(".search-content").val();
    var url = "index.php/" + type + "/searchByName?title=" + key;
    $(location).attr("href", url);
  });

  $(".search-content").keypress(function (e) {
    if (e.which == 13) {
      $(".fa-search").click();
    }
  });

  $(".movie-tag").click(function (event) {
    event.preventDefault();
    var tagId = $(this).attr("id");
    console.log(tagId);
    var url = "index.php/movies/searchByTag?tag=" + tagId;
    $(location).attr("href", url);
  });

  $(".language").click(function(event){
    event.preventDefault();
    var language = $(this).html();
    var url = "index.php/movies/searchByLanguage?language=" + language;
    $(location).attr("href", url);
  })

  $(".collection").click(function (event) {
    event.preventDefault();
    var collectionId = $(this).attr("id");
    console.log(collectionId);
    var url = "index.php/movies/searchByCollection?collection=" + collectionId;
    $(location).attr("href", url);
  });

  $(".show-tag").click(function (event) {
    event.preventDefault();
    var tagId = $(this).attr("id");
    console.log(tagId);
    var url = "index.php/shows/searchByTag?tag=" + tagId;
    $(location).attr("href", url);
  });
});
