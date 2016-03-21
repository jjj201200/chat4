/**
 * @author Ruo
 * @Date 2013.07.31
 */
$(document).ready(function () {
    /*
     define class
     */
    sideMenuOpenClass = 'sidemenu_open';
    mainDarkenClass = 'darken';
    mainLoadingClass = 'loading';
    /*
     Global Dom and Variable
     */
    $loadingBar = $("#loading_bar");
    $main = $('#main');
    sideMenu = {
        'category': $('#category'),
        'insect': $('#insect'),
        'sign_up': $('#sign_up'),
        'sign_in': $('#sign_in')
    };
    sideMenuState = {
        'category': false,
        'insect': false,
        'sign_up': false,
        'sign_in': false
    };
    /*
     */
    var openSideMenu = function (type) {
        $main.addClass(mainDarkenClass);
        $.each(sideMenu, function (i, n) {
            if (sideMenu[i].attr("id") != type || sideMenu[i].hasClass(sideMenuOpenClass)) {
                sideMenu[i].removeClass(sideMenuOpenClass);
                sideMenuState[sideMenu[i].attr("id")] = false;
            } else {
                sideMenu[i].find('form')[0].reset();
                sideMenu[i].addClass(sideMenuOpenClass);
                sideMenuState[sideMenu[i].attr("id")] = true;
            }
        });
        //console.log(sideMenuState);
    };
    var closeSideMenu = function () {
        $main.removeClass(mainDarkenClass);
        $.each(sideMenu, function (i, n) {
            n.removeClass(sideMenuOpenClass);
            sideMenuState[i] = false;
        });
    };
    var scrollToTop = function () {
        $main.stop().animate({
            scrollTop: 0
        }, 'slow');
    };
    /*
     */
    $('#top_btn').click(function () {
        scrollToTop();
        return false;
    });
    $(document).on('click', '#main', function () {
        if ($(this).hasClass('darken'))
            closeSideMenu();
    });
    //$('#category,#filelist').mCustomScrollbar({
    //	scrollInertia: 300,
    //	advanced     : {
    //		updateOnContentResize: true,
    //		updateOnBrowserResize: true
    //	}
    //});
    $('body').scroll(function () {
        if ($('body').scrollTop() >= 200) {
            $('#title').addClass('fixed');
        } else {
            $('#title').removeClass('fixed');
        }
    });
    /*�
     */
    //signup
    $('#sign_up_btn').click(function () {
        if (!sideMenuState['sign_up']) {
            openSideMenu('sign_up');
            sideMenuState['sign_up'] = true;
        } else {
            closeSideMenu();
        }
    });
    //sign in
    $('#sign_in_btn').click(function () {
        if (!sideMenuState['sign_in']) {
            openSideMenu('sign_in');
            sideMenuState['sign_in'] = true;
        } else {
            closeSideMenu();
        }
    });
    //sign out
    $('#sign_out_btn').click(function () {
        $.ajax({
            type: "POST",
            url: "sign_out",
            data: "sign_out=true",
            success: function (data) {
                if (data == 0) location.reload();
                console.log(data);
            }
        });
    });
    //Insect
    $('#insect_btn').click(function () {
        if (!sideMenuState['insect']) {
            openSideMenu('insect');
            sideMenuState['insect'] = true;
            $('#filelist').find('dl').remove();
        } else {
            closeSideMenu();
        }
    });
    $('#clear_files').click(function () {
        $('#insect').find('#filelist .del').click();
    });
    $('#close_insect').click(function () {
        closeSideMenu();
    });
    //Category
    //$('#category_btn').click(function () {
    //	if (!sideMenuState['category']) {
    //		openSideMenu('category');
    //		sideMenuState['category'] = true;
    //	} else {
    //		closeSideMenu();
    //	}
    //});
    //$main.click(function () {
    //	closeSideMenu();
    //});


    $('#insect').find('form').ajaxForm({
        beforeSend: function () {
            //var fileObj = $('#file_upload'), file;

        },
        uploadProgress: function (event, position, total, percentComplete) {
            $loadingBar.animate({
                'width': percentComplete + '%'
            }, 'slow');
        },
        success: function (data) {
            $loadingBar.animate({
                "background-color": "#3AC404"
            }).delay(2000);
            $loadingBar.animate({
                "opacity": "0"
            }, function () {
                $(this).animate({
                    "width": "100%"
                });
            });
            $('#insect form div').empty();
            $('#insect form input[type=file]').val("");
        },
        complete: function (xhr) {
            location.reload();
            //$('#menu dl:last dd:last').click();
            //$('#input').show('slow');
            console.log(xhr.responseText);
        }
    });
    $(document).on('change', '#file_upload', function () {
        var t = this;
        $.each(t.files, function (i, n) {
            if (n.size > 67108864) {
                $(t).replaceWith('<input id="file_upload" name="file_upload[]" type="file" multiple>');
                return true;
            }
        });
    });

    $("#upload_files").click(function () {

        $text = $('#insect').find('textarea').val();
        $file = $('#file_upload')[0].files.length;
        if ($text == '' && $file == 0) {
            alert('Everything is empty!');
            return;
        }
        if ($('#insect').find('#code').val() == '') {
            alert('Code is empty!');
            return;
        }
        $('#insect').find('form').submit();
    });

    $(document).on('click', '#menu dl dt', function () {
        $(this).parent().siblings("dl").children("dd").removeClass('menuShow').addClass('menuHide');
        $(this).parent().parent().siblings(".year").find("dd").removeClass('menuShow').addClass('menuHide');
        $(this).parent().parent().siblings(".year").find("dl").removeClass('menuShow').addClass('menuHide');
        $(this).siblings('dd').removeClass('menuHide').addClass('menuShow');
    });
    $(document).on('click', '#menu .year b', function () {
        $(this).parent().siblings('.year').find('dd').removeClass('menuShow').addClass('menuHide');
        $(this).parent().siblings('.year').find('dl').removeClass('menuShow').addClass('menuHide');
        $(this).siblings('dl').removeClass('menuHide').addClass('menuShow');
    });
    var ajax;
    $(document).on('click', '#menu dd', function () {
        if (ajax != null)ajax.abort();
        $this = $(this);
        $this.siblings('dt').click();
        $('#menu').find('dd').removeClass('active');
        $this.addClass('active');
        $main.addClass(mainLoadingClass);
        closeSideMenu();
        insectCoverLayerState = false;
        ajax = $.ajax({
            type: "get",
            url: "query_chat",
            data: "year=" + $this.parent('dl').parent('.year').attr('alt') + "&month=" + $this.siblings('dt').attr('alt') + "&day=" + $this.attr('alt'),
            success: function (json) {
                var j = eval("(" + json + ")");
                console.log(j);
                $('#content').find('div').empty().append("<h2 id='title'><span>" + $this.parent('dl').parent('.year').attr('alt') + "." + $this.siblings('dt').attr('alt') + "." + $this.attr('alt') + " - " + json.length + "</span></h2>");
                $.each(j, function (i, n) {

                    $('#content').find('div h2').append('<a class="a' + n['cid'] + '" href="#b' + n['cid'] + '">' + ( i + 1 ) + '</a>');

                    $blockquote = $('<blockquote id="b' + n['cid'] + '"></blockquote>');
                    $timestamp = $('<span class="number">NO.' + ( i + 1 ) + ' - ' + ( n['tucao'] === "" ? 0 : n['tucao'].length ) + ' - ' + ( n['imgJson'][0] === "" ? 0 : n['imgJson'].length ) + '</span><span class="time">' + n['date'] + '</span>');
                    $username = $('<span class="username">-- ' + n['username'] + '</span>');
                    $blockquote.append($timestamp, $username);
                    $imgBox = $('<div class="imgBox"></div>');
                    $soundBox = $('<div class="soundBox"></div>');
                    $img_str = "";
                    $sound_str = "";
                    $.each(n['imgJson'], function (j, m) {
                        if (/(gif|jpg|jpeg|bmp|png)$/.test(m)) {
                            $img_str += '<img src="img/' + m + '" />';
                            $('.a' + n['cid']).addClass('img');
                        }
                    });
                    $.each(n['soundJson'], function (k, p) {
                        if (/(mp3|wav|wma|ogg|ape|acc|m4a)$/.test(p)) {
                            $sound_str += '<audio src="sound/' + p + '" controls="controls" /></audio>';
                            $('.a' + n['cid']).addClass('music');
                        }
                    });
                    if ($img_str != "")
                        $imgBox.append($img_str);
                    if ($sound_str != "")
                        $soundBox.append($sound_str);
                    $blockquote.append($imgBox).append($soundBox);
                    if (n['tucao'] != "")
                        $blockquote.append('<p>' + n['tucao'] + '</p>');
                    $('#content > div').append($blockquote);
                    scrollToTop();
                    $main.removeClass(mainLoadingClass);
                });
            }
        });
    });
});