/**
 * @author Ruo
 * @Date 2013.07.31
 */
$ ( document ).ready ( function ( ) {
	$loadingBar = $ ( "#loading_bar" );
	$contentCover = $ ( "#cover" );
	jQuery.fn.zero_width = function ( $isture , $speed ) {
		if ($isture) {
			$ ( this ).stop ( ).animate ( {
				"opacity" : "0" ,
				"width" : "0px" ,
				"padding" : "0px"
			} , $speed , function ( ) {
				$ ( this ).css ( {
					"display" : "none"
				} )
			} );
		} else {

			$ ( this ).stop ( ).css ( {
				"display" : "block"
			} ).animate ( {
				"width" : "15px" ,
				"opacity" : "1" ,
				"padding" : "0px 5px"
			} , $speed );
		}
	}
	$ ( '#insect form' ).ajaxForm ( {
		beforeSend : function ( ) {
			$loadingBar.css ( {
				"width" : "0%" ,
				"background-color" : "#C40404" ,
				"opacity" : "0.5"
			} );
			$contentCover.stop ( ).css ( {
				"display" : "block"
			} ).animate ( {
				"opacity" : "1"
			} );
		} ,
		uploadProgress : function ( event , position , total , percentComplete ) {
			$loadingBar.animate ( {
				'width' : percentComplete + '%'
			} , 'slow' );
		} ,
		success : function ( data ) {
			$loadingBar.animate ( {
				"background-color" : "#3AC404"
			} ).delay ( 2000 );
			$loadingBar.animate ( {
				"opacity" : "0"
			} , function ( ) {
				$ ( this ).animate ( {
					"width" : "0%"
				} );
			} );
			$ ( '#insect form div' ).empty ( );
			$ ( '#insect form input[type=file]' ).val ( "" );
		} ,
		complete : function ( xhr ) {
			$ ( '#menu dl:last dd:last' ).click ( );
			$ ( '#input' ).show ( 'slow' );
			console.log ( xhr.responseText );
		}
	} );
	$ ( "#insect" ).hide ( );
	$ ( '#file_upload' ).change ( function ( ) {
		var fileObj = this , file;
		if (fileObj.files.length > 11) {
			$ ( '#insect form div img' ).not ( $ ( '#insect form div img:lt(10)' ) ).remove ( );
		}
		if (fileObj.files) {
			$.each ( fileObj.files , function ( i , n ) {
				file = fileObj.files[ i ];
				console.log ( file );
				if (i < 11) {
					if (/[image]\/\w+/.test ( file.type )) {
						var fr = new FileReader;
						fr.onloadend = addimg;
						fr.readAsDataURL ( file );
					} else if (/[audio]\/\w+/.test ( file.type )) {
						$ ( '#insect form div' ).append ( '<img src="sound_icon.png" style="width:100px;height:100px;" />' );
					}
				}
			} );
		}
		$ ( this ).clone ( true ).appendTo ( $ ( this ).parent ( ) );
		$ ( this ).css ( "display" , "none" );
	} );
	$ ( "#input_btn" ).toggle ( function ( ) {
		$ ( "#insect" ).fadeIn ( 'fast' );
		$contentCover.stop ( ).css ( {
			"opacity" : "1" ,
			"z-index" : "1"
		} );
		// $("body").addClass("bg");
	} , function ( ) {
		$ ( "#insect" ).fadeOut ( 'fast' );
		// $("body").removeClass("bg");
		$ ( '#insect form div' ).empty ( );
		$ ( '#insect form input[type=file]' ).val ( "" );
		$ ( '#insect form b' ).css ( 'width' , '0px' );
		$contentCover.stop ( ).css ( {
			"opacity" : "0" ,
			"z-index" : "-1"
		} );
	} );
	$ ( "#input" ).click ( function ( ) {
		$ ( '#insect form .progress_bar' ).css ( {
			"width" : "0px" ,
			"background-color" : "#E20400"
		} , function ( ) {
			$ ( '#insect form' ).submit ( function ( ) {
				$ ( this ).ajaxSubmit ( );
				return false;
			} );
		} );
		$ ( "#input" ).hide ( );
	} );
	$ ( 'dl dt' ).click ( function ( ) {
		$ ( this ).siblings ( 'dd' ).zero_width ( false , 500 );
		$ ( this ).parent ( ).siblings ( "dl" ).children ( "dd" ).zero_width ( true , 400 );
	} );
	$ ( '#menu dd' ).click ( function ( ) {
		$ ( '#menu dd' ).removeClass ( 'active' );
		$this = $ ( this );
		$parent = $this.parent ( );
		menu_animate ( $parent );
		$this.addClass ( 'active' );
		$contentCover.stop ( ).css ( {
			"opacity" : "1" ,
			"z-index" : "1"
		} );
		$.ajax ( {
			type : "POST" ,
			url : "query.php" ,
			data : "month=" + $this.siblings ( 'dt' ).attr ( 'alt' ) + "&day=" + $this.attr ( 'alt' ) ,
			success : function ( json ) {
				var json = eval ( "(" + json + ")" );
				console.log ( json );
				$ ( '#content div' ).empty ( );
				$ ( '#content div' ).append ( "<h2 id='title'><span>2013." + $this.siblings ( 'dt' ).attr ( 'alt' ) + "." + $this.attr ( 'alt' ) + " - " + json.length + "</span></h2>" );
				$.each ( json , function ( i , n ) {
					$ ( '#content div h2' ).append ( '<a class="a' + n[ 0 ] + '">' + ( i + 1 ) + '</a>' );
					$ ( '.a' + n[ 0 ] ).click ( function ( ) {
						$ ( 'html,body' ).stop ( ).animate ( {
							scrollTop : $ ( '#b' + n[ 0 ] ).offset ( ).top - 50 - 38
						} , 'slow' );
						return false;
					} );
					$blockquote = $ ( '<blockquote id="b' + n[ 0 ] + '"></blockquote>' );
					$timestamp = $ ( '<span class="number">NO.' + ( i + 1 ) + ' - ' + ( n[ 4 ][ 0 ] === "" ? 0 : n[ 4 ].length ) + ' - ' + ( n[ 5 ][ 0 ] === "" ? 0 : n[ 5 ].length ) + '</span><span class="time">' + n[ 2 ] + '</span>' );
					$blockquote.append ( $timestamp );
					$imgBox = $ ( '<div class="imgBox"></div>' );
					$soundBox = $ ( '<div class="soundBox"></div>' );
					$img_str = "";
					$sound_str = "";
					$.each ( n[ 4 ] , function ( j , m ) {
						if (/(gif|jpg|jpeg|bmp|png)$/.test ( m ))
							$img_str += '<img src="img/' + m + '" />';
					} );
					$.each ( n[ 5 ] , function ( k , p ) {
						if (/(mp3|wav|wma|ogg|ape|acc)$/.test ( p )) {
							$sound_str += '<audio src="sound/' + p + '" controls="controls" /></audio>';
							$ ( '.a' + n[ 0 ] ).addClass ( 'music' );
						}
					} )
					if ($img_str != "")
						$imgBox.append ( $img_str );
					if ($sound_str != "")
						$soundBox.append ( $sound_str );
					$blockquote.append ( $imgBox ).append ( $soundBox );
					if (n[ 3 ] != "")
						$blockquote.append ( '<p>' + n[ 3 ] + '</p>' );
					$ ( '#content > div' ).append ( $blockquote );
					$ ( 'body' ).scrollTop ( 0 );
					$contentCover.stop ( ).css ( {
						"opacity" : "0" ,
						"z-index" : "-1"
					} );
				} );
				// $ ( '#content' ).mCustomScrollbar ( {
				// scrollButtons : {
				// enable : true
				// }
				// } );
			}
		} );
	} );
} );
function menu_animate ( $dom ) {
	$dom.siblings ( "dl" ).children ( "dd" ).zero_width ( true , 400 );
	$dom.children ( 'dd' ).zero_width ( false , 500 );
}

function addimg ( file ) {
	if ( typeof file === "object") {
		file = file.target.result;
		$img = $ ( '<img/>' );
		$img.css ( {
			"width" : "100px" ,
			"height" : "100px" ,
		} );
		$img.attr ( "src" , file );
		$ ( '#insect form div' ).append ( $img );
	}
}
