// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var PropertySearch = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $(elem);
			self.setElem();
			self.options = $.extend( {}, $.fn.propertySearch.options, options );
			self.searchBox__event();
			self.search();

			self.withlist = {};
			

			self.$property__listsbox.delegate('[data-action=withlist]', 'click', function(event) {
				
				var box = $(this).closest('.property-item');

				var data = box.data();


				if( box.hasClass('has-withlist') ){
					box.removeClass('has-withlist');

					delete self.withlist[data.property_id];
				}
				else{
					box.addClass('has-withlist');
					self.withlist[data.property_id] = data;
				}


				self.checkWithList();
			});


			var $this = Object.create( self.Listing );
			$this.init( {}, self.$drawer[0], self );
		},

		setElem: function () {
			var self = this;

			self.$property__listsbox = self.$elem.find('[ref=property-listsbox]');

			// search Box
			self.$flyout = self.$elem.find('.search-dropdown');
			self.$inputSearch = self.$elem.find('[role=input-search]');
			self.$searchbox = self.$elem.find('[role=searchbox]');
			self.$drawer = self.$elem.find('[role=drawer]');

			self.$searchbox__listsbox = {};
			$.each(self.$elem.find('[data-searchbox-listsbox]'), function(index, el) {
				self.$searchbox__listsbox[ $(this).attr('data-searchbox-listsbox') ] = $(this);
			});

			self.$searchbox__clear = self.$elem.find('[data-searchbox-action=clear]');
		},
		searchBox__event: function () {
			var self = this;

			self.is_loading = false;
			self.is_focus = false;
			self.is_search = '';
			self.is_keycodes = [37,38,39,40,13,27];

			self.searchBox__getData = {
				limit: 10,
				pager: 1,
				q: '',
				more: false,
			}
			self.$inputSearch.focus(function() {
				self.is_focus = true;
			}).blur(function(event) {
				self.is_focus = false;
			}).click(function(event) {
				self.is_focus = true;

				var val = $.trim( $(this).val() );

				if( val=='' ){
					self.searchBox__openRecentSearchs();
				}
				else{

					var li = self.$searchbox.find( '[data-searchbox=item].active' );
					if( li.length==1 && self.$searchbox.hasClass('open') ){
						li.removeClass('active');
					}

					self.searchBox__displyItem();
				}

			}).keyup(function(e) {
				
				var val = $.trim( $(this).val() );

				if( self.is_keycodes.indexOf( e.which )<0 ) {

					if( self.$searchbox.hasClass('has-result') ){
						self.$searchbox.removeClass('has-result');
					}

					clearTimeout( self.is_search );
					if( val==''  ){
						self.searchBox__openRecentSearchs();

						self.$searchbox.removeClass('has-loading');
					}
					else{

						self.searchBox__close();
						self.$searchbox.addClass('has-loading');

						self.searchBox__search();
					}
				}
			}).keydown(function (e) {
				var val = $.trim( $(this).val() ),
					keyCode = e.which;

				if( keyCode==40 || keyCode==38 ){

					self.searchbox__itemUpDown( keyCode==40 ? 'donw':'up' );
					e.preventDefault();
				}

				if( keyCode==13 ){
					var li = self.$searchbox.find( '[data-searchbox=item].active' );
					if( li.length==1 ){
						self.searchbox__chooseItem( li.data() );
						self.searchBox__close();
					}
					else{

						self.$searchbox.removeClass('has-loading');
						clearTimeout( self.is_search );
						self.searchbox__chooseItem( {name: self.$inputSearch.val() } );
						self.searchBox__close();
					}
				}

				if( val=='' && keyCode==27 ){
					self.searchBox__close();
					e.preventDefault();
				}
				else if( keyCode==27 ){

					var li = self.$searchbox.find( '[data-searchbox=item].active' );
					if( li.length==1 ){
						li.removeClass('active');
					}
					self.searchBox__close();
				}
			})


			self.$inputSearch.bind('paste', function(event) {
				// console.log( 'paste' );
			});





			$(window).click(function( event ) {
				
				if ( $(event.target).parents('.search-box-input').length && !self.$searchbox.hasClass('open') ) {

					/*self.$menu.empty();
					self.load();
					self.open();

					self.$inputSearch.focus();*/
				}
				else if( !self.is_focus ){

					self.searchBox__close();
				}
			});

			self.$searchbox.delegate('[data-searchbox=item]', 'mouseenter', function(event) {

				self.$searchbox.find( '[data-searchbox=item]' ).removeClass('active');
				$(this).addClass('active');
			});

			self.$searchbox.delegate('[data-searchbox=item]', 'click', function(event) {
				self.$searchbox.find( '[data-searchbox=item]' ).removeClass('active');

				$(this).addClass('active');
				self.searchbox__chooseItem( $(this).data() );

				self.$inputSearch.focus();
				self.searchBox__close();
			});

			self.$searchbox__clear.click(function() {

				self.$searchbox.removeClass('has-result');
				self.$inputSearch.val('');
				self.$inputSearch.focus();
			});
		},
		searchBox__openRecentSearchs: function () {
			var self = this;

			$.each(self.$searchbox__listsbox, function(index, el) {
				el.empty().parent().hide(0);
			});

			if( self.options.recentsearchs.length > 0 ){
				self.searchBox__setRecentSearchs();

				self.$searchbox__listsbox.recentsearchs.parent().show(0);
				self.$flyout.show(0);
				self.$elem.addClass('open');
			}
			else{
				self.$flyout.hide(0);
			}
		},
		searchBox__setRecentSearchs: function () {
			var self = this;

			self.$searchbox__listsbox.recentsearchs.empty();
			$.each(self.options.recentsearchs, function(i, obj) {
				self.$searchbox__listsbox.recentsearchs.append( self.searchBox__setItem( obj ) );
			});

			self.searchbox__eqItem();
		},
		searchBox__close: function () {
			var self = this;

			self.$searchbox__listsbox.recentsearchs.parent().hide(0);
			self.$flyout.hide(0);
			self.$searchbox.removeClass('open');
		},
		searchBox__search: function () {
			var self = this;

			self.is_search = setTimeout( function () {
				
				self.searchBox__fetch().done(function ( res ) {
					
					if( res.error ){
						self.searchBox__close();
						return false;
					}

					self.$searchbox__listsbox.recentsearchs.empty();
					self.$searchbox__listsbox.recentsearchs.parent().hide(0);

					var count = 0; //parseInt(res.total);

					$.each( res.items, function(key, items) {

						// clear Data
						self.$searchbox__listsbox[key].empty();
						self.$searchbox__listsbox[key].parent().hide(0);

						if( items.length > 0 ){

							count += items.length;
							$.each(items, function(i, obj) {
								self.$searchbox__listsbox[key].append( self.searchBox__setItem( obj ) );
							});
							self.$searchbox__listsbox[key].parent().show(0);
						}

					});

					if( count==0 ){
						self.searchbox__itemUpDown();
					}
					else{
						self.$searchbox.addClass('open').removeClass('has-loading');
						self.$flyout.show(0);

						self.searchbox__eqItem();
					}
					// 
				});
				
			}, 800);
		},
		searchBox__fetch: function () {
			var self = this;

			self.searchBox__getData.q = $.trim( self.$inputSearch.val() );

			return $.ajax({
				url: app.getUri( 'property/propertyList' ),
				type: 'GET',
				dataType: 'json',
				data: self.searchBox__getData,
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("searchBox: complete");
				self.$searchbox.removeClass('has-loading');
			});
		},
		searchBox__setItem: function ( data ) {
			var self = this;

			var $item = $('<a>', {class: 'anchor anchor32 clearfix'});

			if( data.theme=='people' ){
				$item.append( 
					  $('<div>', {class: 'avatar lfloat size32 no-avatar mrm'}).html( '<div class="initials"><i class="icon-user"></i></div>' ) 
					, $('<div>', {class: 'content'}).append(
						  $('<div>', {class: 'spacer'})
						, $('<div>', {class: 'massages'}).append(
							  $('<div>', {class: 'text'}).text( data.text )
							, ( data.category ? $('<div>', {class: 'category'}).html( data.category ): '' )
						)
					)
				);
			}
			else{
				$item.append( $('<span>').text( data.text ) );
			}

			// console.log( data );
			var $li = $('<li>', {class: 'search-dropdown-item', 'data-searchbox': 'item' }).html( $item );

			$li.data( data );

			return $li;
		},
		searchBox__displyItem: function () {
			var self = this;

			if( self.$searchbox.find( '[data-searchbox=item]' ).length==0 ){
				return false;
			}
 
			self.$flyout.show(0);
		},
		searchbox__itemUpDown: function ( active ) {
			var self = this;

			var li = self.$searchbox.find( '[data-searchbox=item].active' );

			if( li.length == 0 ){
				li = self.$searchbox.find( '[data-searchbox=item][item-eq=0]' );
			}
			else{
				var index = parseInt( li.attr('item-eq') ),
					length = self.$searchbox.find( '[data-searchbox=item]' ).length;

				if( active=='up' ){
					index--;
					li = index<0 ? self.$searchbox.find( '[data-searchbox=item][item-eq='+ (length-1) +']' ): self.$searchbox.find( '[data-searchbox=item][item-eq='+ index +']' );

					// li = li.prev().length ? li.prev() : self.$searchbox.find( '[data-searchbox=item]:last-child' );
				}
				else{
					index++;

					li = index>=length ? self.$searchbox.find( '[data-searchbox=item][item-eq=0]' ): self.$searchbox.find( '[data-searchbox=item][item-eq='+ index +']' );

				}

			}

			self.$searchbox.find( '[data-searchbox=item].active' ).removeClass('active');
			li.addClass('active');

			
			if( self.$searchbox.find( '[data-searchbox=item]' ).length > 0 ){
				var h = li.position().top + self.$flyout.scrollTop() + li.outerHeight();

				if( h > self.$flyout.outerHeight() ){
					h -= self.$flyout.outerHeight();

					self.$flyout.scrollTop( h );
				}
				else{
					self.$flyout.scrollTop( 0 );
				}
			}
		},
		searchbox__chooseItem: function ( data ) {
			var self = this;

			self.$searchbox.addClass('has-result');
			self.$inputSearch.val( $.trim(data.name) );

			var recentsearchs = [];
			recentsearchs.push( data );

			var n = 0;
			$.each(self.options.recentsearchs, function(i, obj) {
				if( n <= self.options.recentsearchs_maxLength && data.name!=obj.name ){
					recentsearchs.push( obj );
					n++;
				} 
			});

			self.options.recentsearchs = recentsearchs;
		},
		searchbox__eqItem: function () {
			var self = this;

			$.each(self.$searchbox.find( '[data-searchbox=item]' ), function(index) {
				$(this).attr('item-eq', index);
			});
		},

		search: function () {
			var self = this;

			setTimeout(function () {
				self.fetch().done(function (res) {
					
					$.each( res.items, function(i, obj) {

						self.$property__listsbox.append( setElem__PropertyItem( obj ) );
					});
					
				});
			});
		},
		fetch: function () {
			var self = this;

			self.$property__listsbox.parent().addClass('has-loading');

			return $.ajax({
				url: app.getUri( 'property/search' ),
				type: 'GET',
				dataType: 'json',
				data: {},
			})
			.fail(function() {
				console.log("lists: error");
			})
			.always(function() {
				console.log("lists: complete");
				self.$property__listsbox.parent().removeClass('has-loading');
			});
		},

		Listing: {
			init: function (options, elem, property) {
				var self = this;

				self.$elem = $(elem);

				self.current = {id: 'create', text: 'Create new' }
				self.property = property;

				self.getData = {
					limit: 50,
					pager: 1,
					q: '',
					more: false,
				}

				self.$selector = self.$elem.find('.collection-drawer-header-selector');
				self.$menu = self.$elem.find('.propertyListingMenu__content > .propertyListingList');
				self.$flyout = self.$elem.find('.propertyListingMenu-wrap');
				self.$box = self.$elem.find('.collection-drawer-header-listing-container');
				self.$inputCreate = self.$elem.find('[data-listing-action=inputCreate]');
				self.$inputSearch = self.$elem.find('#search-query');
				self.$formSearch = self.$elem.find('.form-search');
				self.$add = self.$elem.find('.collection-drawer-header-listing-add');
				self.inputSearch_focus = false;
				self.is_loading = false;
				self.has_scroll = false;

				self.$menu.parent().scroll(function() {

					var val = (self.$menu.outerHeight() * 3) /100;
					var pos = $(this).scrollTop() + $(this).outerHeight();
					var hei = self.$menu.outerHeight();
					var lev = hei - pos;
					if( lev < val && !self.is_loading && self.getData.more && self.has_scroll ){

						self.getData.pager ++;
						self.load();
					}
				});


				// Event 
				self.$inputSearch.click(function(e) {
					e.stopPropagation();
				}).focus(function() {
					self.inputSearch_focus = true;
				}).blur(function(event) {
					self.inputSearch_focus = false;
				}).keydown(function (e) {
					var val = $.trim( $(this).val() ),
						keyCode = e.which;

					if( keyCode==13 ){
						// console.log( 'keydown' );
						e.stopPropagation();
					}
				}).keyup(function(event) {
					var val = $.trim($(this).val());

					if( self.getData.q != val && val=='' ){
						self.getData.q = '';
						self.getData.pager = 1;
						self.load( 800 );
					}
				});

				self.$formSearch.submit(function(e) {
					e.preventDefault();

					self.search();
				});

				self.$elem.find('.propertyListingMenu-create').click(function(event) {
					self.current = {id: 'create', text: 'Create new' }
					self.activeSelector();
					self.$inputCreate.val('').focus()
				});

				self.$inputCreate.keyup(function() {
					var val = $.trim( $(this).val() );

					if( val != '' && $(this).parent().hasClass('has-error') ){
						$(this).parent().removeClass('has-error');
					} 
				});

				self.activeSelector();
				$(window).click(function(event) {
					
					if ( $(event.target).parents('.collection-drawer-header-selector').length==1 && !self.$flyout.hasClass('open') ) {


						self.load();
						self.open();
						self.$inputSearch.focus();
					}
					else if( !self.inputSearch_focus ){
						self.close();
					}
				});

				self.$menu.delegate('[data-listing-id]', 'click', function(event) {
					self.active( $(this).data() );
				});

				self.$add.click(function() {
					
					self.addlisting();
				});

				self.$elem.find('.propertyListingMenu-clear').click(function() {
					self.property.clearWithlist();
					self.$inputCreate.parent().removeClass('has-error');
				});
			},

			load: function ( length ) {
				var self = this;

				if( self.getData.pager==1 ){
					self.$menu.empty();
				}

				self.$menu.parent().addClass('has-loading').removeClass('has-empty').removeClass('has-error');
				
				setTimeout(function () {

					self.fetch().done(function( res ) {
						self.getData = $.extend( {}, self.getData, res.options );

						if( res.error ){
							self.$menu.parent().addClass('has-error');
							return false;
						}

						if( res.items ){
							$.each( res.items, function(i, obj) {
								self.display( obj );
							});
						}

						if( self.getData.pager==1 && parseInt(res.total)==0 ){
							self.$menu.parent().addClass('has-empty');
						}

						if( self.getData.pager==1 ){
							self.has_scroll = true;
						}
						
						self.$menu.parent().toggleClass('has-more', self.getData.more);
					});

				}, length || 1);;
			},

			fetch: function () {
				var self = this;

				self.is_loading = true;
				return $.ajax({
					url: app.getUri( 'property/listingList' ),
					type: 'GET',
					dataType: 'json',
					data: self.getData,
				})
				.fail(function() {
					self.$menu.parent().addClass('has-error');
					console.log("listingList: error");
				})
				.always(function() {
					self.is_loading = false;
					self.$menu.parent().removeClass('has-loading');
					console.log("listingList: complete");
				});
			},

			getData: function () {

				return {};
			},
			display: function (data) {
				this.$menu.append( this.setItem( data ) );
			},
			setItem: function (data) {
				var self = this;

				var $li = $('<li>', {class: 'menu-item', 'data-listing-id': data.id});
				var $anchor = $('<div>', {class: 'anchor clearfix'});

				var $avatar = $('<div>', {class: 'code lfloat mrm'}).append(
					  $('<span>', {class: 'fwb', text: '#'+data.code})
					, $('<div>', {class: 'fss', text: '19 Items'})
				);

				var $date = $('<span>', {class: 'date fcg fsm'});

				$date.append( $('<i>', {class: 'date-i icon-clock-o'}), $('<span>', {class: 'date-str mls'}).html( data.created_str ) );
				if( data.user_name ){
					$date.append( ' - ', $('<span>', {class: ''}).html( data.user_name ) );
				}

				/* content */
				var $content = $('<div>', {class: 'content'});
				$content.append(
					  $('<div>', {class: 'spacer'})
					, $('<div>', {class: 'massages'}).append(

						  $('<div>', {class: 'fullname', text: data.name})
						, $('<div>', {class: 'dics'}).append( $date )
					)
				);

				$anchor.append( $avatar, $content );
				$li.data( data );

				return $li.append( $anchor );
			},
			open: function () {
				var self = this;

				self.$inputCreate.parent().removeClass('has-error');
				self.$flyout.css({
					width: self.$box.outerWidth()
				}).show(0, function () {
					$(this).addClass('open');
				});
			},
			close: function() {
				var self = this;

				self.getData.pager = 1;
				self.has_scroll = false;
				self.$flyout.hide(0, function () {
					$(this).removeClass('open');
				});
			},
			active: function ( data ) {
				var self = this;

				self.current = {
					id: data.id,
					text: data.id + ' - ' + data.name
				}

				self.activeSelector();
			},
			activeSelector: function () {
				var self = this;


				self.$inputCreate.parent().toggleClass('hidden_elem', self.current.id=='create' ? false: true);
				self.$selector.find('span').text( self.current.text );
			},
			search: function () {
				var self = this;

				var val = self.$inputSearch.val();
				if( self.getData.q!=val ){

					self.getData.q = val;
					self.getData.pager = 1;

					self.load( 800 );

				}
			},


			addlisting: function () {
				var self = this;

				if( self.current.id == 'create' ){
					var val = $.trim(self.$inputCreate.val());

					self.$inputCreate.val( val );
					if( val=='' ){
						self.$inputCreate.parent().addClass('has-error');
						self.$inputCreate.focus();
					}
					else{

						self.$inputCreate.val('');
						// create
						console.log( 'Create Listing' );
						self.addWithlist();
					}
				}
				else{
					self.addWithlist( self.current.id );
				}
				
				
			},

			addWithlist: function ( id ) {
				var self = this;
				console.log( id, self.property.withlist );

				self.property.clearWithlist();
			}
		},

		checkWithList: function () {
			var self = this;

			var length = Object.keys( self.withlist ).length;
			self.$elem.find( '.data-withlist-countVal' ).text( PHP.number_format( length ) );


			self.$drawer.toggleClass('is-visible', length>0);
		},
		clearWithlist: function () {
			var self = this;


			$.each(self.withlist, function(i, obj) {
				
				self.$property__listsbox.find('[data-id='+ obj.property_id +']').removeClass('has-withlist');
			});


			self.withlist = {};
			self.checkWithList();
		}
	};

	$.fn.propertySearch = function( options ) {
		return this.each(function() {
			var $this = Object.create( PropertySearch );
			$this.init( options, this );
			$.data( this, 'propertySearch', $this );
		});
	};

	$.fn.propertySearch.options = {
		recentsearchs_maxLength: 5, 
	};
	
})( jQuery, window, document );