$(document).ready(function(){
    var foldersWithFiles = [];
    var folderNames = [];
    var syncIsOn = false;
    $('.add-movie-folder').click(function(){
        let lastOne = $('#movie').last();
        let newFolderInput = $('<div>').addClass('folderContainer single-folder');
        let inputFiled = $('<input>').attr({type:'text'}).addClass('folderName');
        let validateButton = $('<span>').addClass('validation').text('验证');
        newFolderInput.append(validateButton);
        newFolderInput.append(inputFiled);
        lastOne.append(newFolderInput);
        $('.add-movie-folder').hide();
    })
    $('.add-show-folder').click(function(){
        let lastOne = $('#show').last();
        let newFolderInput = $('<div>').addClass('folderContainer single-folder');
        let inputFiled = $('<input>').attr({type:'text'}).addClass('folderName');
        let validateButton = $('<span>').addClass('validation').text('验证');
        newFolderInput.append(validateButton);
        newFolderInput.append(inputFiled);
        lastOne.append(newFolderInput);
        $('.add-show-folder').hide();
    })

    $('#movie .single-folder:odd').addClass('odd');
    $('#show .single-folder:odd').addClass('odd');
    $('.movie-syncing.status').hide();
    $('.movie-syncing').hide();
    $('#show .status').hide();
    $('#show .bargif').hide();
    let movieFolderNeedToSync = $('#movie .fa-sync').length;
    if (movieFolderNeedToSync > 0) {
        $('#sync-movie').addClass('sync');
        $('#sync-movie').removeClass('sync-fake');
    } else {
        $('#sync-movie').removeClass('sync');
        $('#sync-movie').addClass('sync-fake');
    }

    let showFolderNeedToSync = $('#show .fa-sync').length;
    if (showFolderNeedToSync > 0) {
        $('#sync-show').addClass('sync');
        $('#sync-show').removeClass('sync-fake');
    } else {
        $('#sync-show').removeClass('sync');
        $('#sync-show').addClass('sync-fake');
    }

    $('.content-container').on('click', '.validation', function () {
        //let folderContainers = $('.folderContainer');
        let type = $(this).closest('.content-container').attr('id');
        let folderName = $(this).parent().find('.folderName').val();
        // console.log(folderName);return;
        // $.each( folderContainers, function() {
        //     let folderName = $(this).find('.folderName').val();
        //     folderNames.push(folderName);
        // });
        $.ajax({
            type: 'GET',
            url: 'index.php/api/validate_folder',
            data: {
                "folderName": folderName,
                "type": type,
            },
            success: function(data) {
                if (data == 'exist') {
                    alert('路径已经存在');
                    $('.folderName').val('');
                    $('.folderName').focus();
                }
                if (data == 'invalidate') {
                    alert ('无效路径，请检查');
                    $('.folderName').val('');
                    $('.folderName').focus();
                }
                else {
                    let newOne = $('<div>').addClass('single-folder');
                    newOne.append($('<label>').text(data));
                    newOne.append($('<span>').append($('<i>').addClass('fas fa-sync')));
                    $('#'+type).append(newOne);
                    $('.folderContainer').remove();
                    $('#sync-' +type).addClass('sync');
                    $('#sync-' +type).removeClass('sync-fake');
                    $('.fa-folder-plus #' +type).show();
                }
                // $.each(folderNames,function(key, value){
                //     if(data[value] != undefined) {
                //         folderContainers[key].append('Validate')
                //     } else {
                //         folderContainers[key].append('UnValidate');
                //     }
                // })
                // foldersWithFiles = data;
            }
        });
    });

    $('#sync-movie').click(function () {
        if($(this).hasClass('sync')){
            $('.movie-syncing').show();
            $('#sync-movie').removeClass('sync');
            $('#sync-movie').addClass('sync-fake');
            let type = 'movie';
            $.ajax({
                type: 'GET',
                url: 'index.php/api/sync_folders',
                data: {
                    "type": type,
                },
                success: function(data) {
                    $('.movie-syncing').hide();
                    $('.movie-syncing.status').show();
                    $('.syncedNumber').text('本次成功 ' + data.synced +' 部电影');
                    $('.syncedIgnore').text('忽略重复 '+ data.ignore +' 部电影');
                    $('.syncedFailed').text('本次失败 '+ data.failed +' 部电影');
                    //$(".status").delay(3000).fadeOut(1000,"linear");
                    if(data.failed == 0){
                        $('#movie .fa-sync').hide();
                    }
                }
            });
        }
    })

    $('#sync-show').click(function () {
        if($(this).hasClass('sync')){
            $('#show .bargif').show();
            $('#sync-show').removeClass('sync');
            $('#sync-show').addClass('sync-fake');
            let type = 'show';
            $.ajax({
                type: 'GET',
                url: 'index.php/api/sync_show',
                data: {
                    "type": type,
                },
                success: function(data) {
                    $('#show .bargif').hide();
                    $('#show .status').show();
                    $('.showSynced').text('同步成功 ' + data.showSynced +' 部电视剧');
                    $('.seasonSynced').text('同步成功 '+ data.seasonSynced +' 季');
                    $('.episodeSynced').text('同步成功 '+ data.episodeSynced +' 集');
                    //$(".status").delay(3000).fadeOut(1000,"linear");
                    if(data.showSyncedFailed == 0){
                        $('#show .fa-sync').hide();
                    }
                }
            });
        }
    })
});

