/**
 * @author Administrator
 */
function scrollY ( container , contentBox , scrollBar , scrollSpeed , contentBoxSpace , scrollBarSpace ) {
		/*
		 * contentBoxTopSpace = contentBoxSpace[0];
		 * contentBoxBottomSpace = contentBoxSpace[1];
		 * scrollBarTopSpace = scrollBarSpace[0];
		 * scrollBarBottomSpace = scrollBarSpace[1];
		 * container.height();
		 * contentBox.height();
		 * scrollBarLength= ((container.height()-contentBoxTopSpace-contentBoxBottomSpace)/contentBox.height())*(container.height()-scrollBarTopSpace-scrollBarBottomSpace);
		 * contentBoxTop = contentBox.position().top-contentBoxTopSpace;
		 */
		var contentBoxTopSpace = contentBoxSpace[ 0 ];
		var contentBoxBottomSpace = contentBoxSpace[ 1 ];
		var scrollBarTopSpace = scrollBarSpace[ 0 ];
		var scrollBarBottomSpace = scrollBarSpace[ 1 ];

		var containerHeight = container.height ( );
		var contentBoxHeight = contentBox.height ( );
		var contentBoxTopMoreSpace = containerHeight / 5;
		var contentBoxBottomMoreSpace = - contentBoxHeight + containerHeight * 4 / 5 - contentBoxBottomSpace;
		var scrollBarTopMoreSpace = scrollBar.position ( ).top < scrollBarTopSpace / 2;
		var scrollBarBottomMoreSpace = containerHeight - scrollBar.height ( ) - scrollBarBottomSpace;
		var scrollHeight = scrollBar.height ( ( ( containerHeight - contentBoxTopSpace - contentBoxBottomSpace ) / contentBoxHeight ) * ( containerHeight - scrollBarTopSpace - scrollBarBottomSpace ) );
		$ ( window ).resize ( function ( ) {
			containerHeight = container.height ( );
			contentBoxHeight = contentBox.height ( );
			contentBoxTopMoreSpace = containerHeight / 5;
			contentBoxBottomMoreSpace = - contentBoxHeight + containerHeight * 4 / 5 - contentBoxBottomSpace;
			scrollBarTopMoreSpace = scrollBar.position ( ).top < scrollBarTopSpace / 2;
			scrollBarBottomMoreSpace = containerHeight - scrollBar.height ( ) - scrollBarBottomSpace;
			scrollHeight = scrollBar.height ( ( ( containerHeight - contentBoxTopSpace - contentBoxBottomSpace ) / contentBoxHeight ) * ( containerHeight - scrollBarTopSpace - scrollBarBottomSpace ) );
		} );

		function scrollOver ( ) {
			if (contentBox.position ( ).top > contentBoxTopMoreSpace || scrollBar.position ( ).top < scrollBarTopMoreSpace) {
				contentBox.stop ( ).animate ( {
					'top' : contentBoxTopSpace
				} , false );
				scrollBar.stop ( ).animate ( {
					'top' : scrollBarTopSpace
				} , false );
			} else if (contentBox.position ( ).top < contentBoxBottomMoreSpace || scrollBar.position ( ).top > scrollBarBottomMoreSpace) {
				contentBox.stop ( ).animate ( {
					'top' : - contentBoxHeight + containerHeight - contentBoxTopSpace - contentBoxBottomSpace
				} )
				scrollBar.stop ( ).animate ( {
					'top' : containerHeight - scrollBar.height ( ) - scrollBarBottomSpace
				} , false );
			}
		}

		scrollBar.draggable ( {
			axis : "y" ,
			containment : container ,
			drag : function ( ) {
				contentBox.stop ( ).animate ( {
					'top' : - ( ( scrollBar.position ( ).top - scrollBarTopSpace ) / ( containerHeight - scrollBar.height ( ) - scrollBarTopSpace - scrollBarBottomSpace ) * ( contentBoxHeight - containerHeight + contentBoxTopSpace ) )
				} );
			} ,
			stop : scrollOver
		} );

		contentBox.mousewheel ( function ( event , delta , deltaX , deltaY ) {
			var contentBoxtargetTop = contentBox.position ( ).top - deltaY * ( containerHeight - contentBoxTopSpace - contentBoxBottomSpace ) * 3 / 4;
			contentBox.stop ( ).animate ( {
				'top' : contentBoxtargetTop
			} , {
				duration : scrollSpeed ,
				queue : false ,
				specialEasing : 'easeInOutQuart' ,
				step : scrollOver
			} );
			scrollBar.stop ( ).animate ( {
				'top' : Math.abs ( contentBoxtargetTop ) / ( contentBoxHeight - contentBoxTopSpace - contentBoxBottomSpace ) * ( containerHeight - scrollBarTopSpace - scrollBarBottomSpace )
			} , {
				duration : scrollSpeed ,
				queue : false ,
				specialEasing : 'easeInOutQuart'
			} );
		} );
	}