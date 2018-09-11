// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {
	
	var DataForum = {
		init: function (settings, elem) {
			var self = this;

			self.$elem = $( elem );
			self.$listsbox = self.$elem.find('[data-ref=listsbox]');
			self.$content = self.$elem.find('[data-ref=content]');

			self.$scrollbar = self.$listsbox.parent();


			/*self.$scrollbar.parent().addClass('overflow');
			setTimeout( function () {
				
				self.$scrollbar.scrollbar({
					// scrolly: self.$scrollbar.parent(),
				});
			}, 1);*/
			


			self.settings = $.extend( {}, {
				options: {
					pager: 1
				}
			}, settings );

			self.is_first = true;

			if ( !self.settings.load_uri ){
				return false;
			}

			self.data = {
				total: 0,
				options: self.settings.options,
			};

			self.currKeyword = $.trim( self.$elem.find('[data-action=search]').find(':input#keywords').val() );

			self.currActionTab = self.$elem.find('.active[data-action-tab]').attr('data-action-tab');
			if( !self.currActionTab ){
				self.currActionTab = self.$elem.find('[data-action-tab]').first().attr('data-action-tab');

				self.$elem.find('[data-action-tab]').first().addClass('active');
			}
			
			self.refresh( 1 );
			self.Events();
		},
		Events: function () {

			var self = this;
			self.$elem.find('[data-action=refresh]').click(function (e) {

				self.refresh( 200 );
				e.preventDefault();
			});

			self.$elem.find(':input[data-filter]').change(function() {
				self.clearData();
				self.refresh( 200 );
			});


			self.$listsbox.parent().scroll(function( e ) {
				
				if( ((self.$listsbox.outerHeight() - $(this).scrollTop()) <= ($(this).outerHeight()+300)) && !self.$listsbox.parent().hasClass('has-loading') && self.data.options.more ){

					self.data.options.pager = parseInt(self.data.options.pager);
					self.data.options.pager += 1;

					self.refresh();
				}
			});


			self.$listsbox.delegate('[data-id]', 'click', function(event) {
				self.active( $(this).data() );
			});


			self.$elem.find('[data-action-tab]').click(function(event) {
				
				if( $(this).hasClass('active') ) return false;
				self.currActionTab = $(this).attr('data-action-tab');
				$(this).addClass('active').siblings().removeClass('active');
				self.actionTab();
			});

			self.$elem.find('[data-action=search]').submit(function(event) {
				event.preventDefault();

				self.clearData();

				self.currKeyword = $.trim( self.$elem.find('[data-action=search]').find(':input#keywords').val() );
				self.data.options.q = self.currKeyword;
				self.refresh( 1 );
			});


			self.$elem.find('[data-action=search] :input#keywords').keyup(function() {
				
				var val = $.trim( $(this).val() );
				if( self.currKeyword!=val && val=='' ){

					self.currKeyword = '';
					self.clearData();
					self.refresh( 100 );
				}
			});

			self.$elem.find('[data-action-profile=delete]').click(function() {
				if( !self.currItem ) return false;
				var href = $(this).data('href');

				Dialog.load( href + self.currItem.id, {
					callback: 1
				}, {
					onClose: function () {
						console.log('onClose');
					},
					onSubmit: function ( $d ) {

						$form = $d.$pop.find('form');

						Event.inlineSubmit( $form ).done(function( result ) {

							result.url = '';
							Event.processForm($form, result);

							self.delProfile();
						});

						
					}
				} );
			});


			// self.$elem.find('[data-action-company=create]').click(function(e) {
			// 	e.preventDefault();

			// 	var href = $(this).data('href');
			// 	var dataPost = {
			// 		callback: 1,
			// 	};
				
			// 	Dialog.load( href, dataPost, {
			// 		onClose: function () {
			// 			console.log('onClose');
			// 		},
			// 		onSubmit: function ( $d ) {

			// 			$form = $d.$pop.find('form');
			// 			console.log( 'onSubmit' );
			// 			/*Event.inlineSubmit( $form ).done(function( result ) {

			// 				result.url = '';
			// 				Event.processForm($form, result);

			// 				self.delProfile();
			// 			});*/
			// 		}
			// 	});
			// });
			
			
			/* -- Contact -- */
			self.$elem.delegate('[data-action-contact=search]', 'submit', function(event) {
				event.preventDefault();
			});
			// self.$elem.delegate('[data-action-contact=add], [data-action-contact=edit]', 'click', function(event) {
			// 	event.preventDefault();
				
			// 	if( !self.currItem ) return false;
			// 	var href = $(this).data('href');

			// 	var dataPost = {
			// 		callback: 1,
			// 		companyId: self.currItem.id,
			// 	};

			// 	Dialog.load( href, dataPost, {
			// 		onClose: function () {
			// 			console.log('onClose');
			// 		},
			// 		onSubmit: function ( $d ) {

			// 			$form = $d.$pop.find('form');
			// 			console.log( 'onSubmit' );
			// 			/*Event.inlineSubmit( $form ).done(function( result ) {

			// 				result.url = '';
			// 				Event.processForm($form, result);

			// 				self.delProfile();
			// 			});*/
			// 		}
			// 	});
			// });
		},
		clearData: function () {
			var self = this;

			self.$listsbox.parent().removeClass('has-data');
			self.$listsbox.empty();
			self.data.options.pager = 1;


			if( self.data.options.q ){
				delete self.data.options.q;
			}
		},
		
		refresh: function (length)  {
			var self = this;

			// if( self.$listsbox.parent().hasClass('has-loading') ) return false;
			if( self.is_loading ) clearTimeout( self.is_loading );
			/*if( self.$listsbox.parent().hasClass('has-error') ){
				self.$listsbox.parent();
			}*/
			// self.$listsbox.parent();

			self.$listsbox.parent()
				.removeClass('has-error')
				.removeClass('has-empty')
				.addClass('has-loading');

			// if( !self.settings.url ) return false;
			self.is_loading = setTimeout(function () {
				self.fetch().done(function( results ) {

					self.data.options = $.extend( {}, self.data.options, results.options );
					if( results.error || results.total == 0 ){

						if( results.total == 0 ){
							self.$listsbox.parent().addClass('has-empty');
						}

						return false; 
					}
					// console.log( self.data.options );

					if( !self.$listsbox.parent().hasClass('has-data') ){
						self.$listsbox.parent().addClass('has-data');
					}

					self.$elem.find('[data-text=total]').text( PHP.number_format(results.total) ).parent().show();
					self.buildFrag(results.items);

					if( self.is_first ){
						self.is_first = false;

						if( self.settings.currId ){
							var item = self.$listsbox.find('[data-id='+ self.settings.currId +']');
							if( item.length ){
								self.active( item.data() );
							}
						}
						else{
							self.active( self.$listsbox.find('[data-id]').first().data() );
						}
					}

					var _data = self.$elem.data();
					if( _data.main ){
						_data.main.resize();
					}
					

					
				});
			}, length || 1500);
		},
		fetch: function() {
			var self = this;

			if( self.is_search ) self.$elem.find('.search-input').attr('disabled', true);

			$.each(self.$elem.find(':input[data-filter]'), function(index, el) {
				
				var name = $(this).attr('data-filter'),
					val = $(this).val();
				if(name=='sort'){

					self.data.options.dir = val=="company.name" ? 'ASC':"DESC";
				}
				self.data.options[ name ] = val;
			});
			// console.log( self.data.options );


			return $.ajax({
				url: self.settings.load_uri,
				data: self.data.options,
				dataType: 'json'
			}).always(function () {

				self.$listsbox.parent().removeClass('has-loading');

				if( self.is_search ){
					self.$elem.find('.search-input').attr('disabled', false);
					self.$elem.find('.search-input').focus();

					self.is_search = false;
				}
				
			}).fail(function() { 
				self.$listsbox.parent().addClass('has-error');
			});
		},

		buildFrag: function (results) {
			var self = this;

			$.each(results, function(index, obj) {
				self.$listsbox.append( self.setItem(obj) );
			});
		},
		setItem: function (data) {
			var self = this;

			var $item = $('<div>', {'data-id': data.id, class: "item status-"+data.status});
			$item.data( data );

			if( data.flag ){
				$item.append('<div class="flag"><i class="icoflag-'+ data.flag +' icoflag-squared"></i></div>')
			}

			if( data.image ){
				var avatar = $('<div>', {class: 'itemAvatar'}).html( $('<img>', {src: data.image}) );
				$item.addClass('has-avatar').append( avatar );

				$item.addClass('js-show-image').data('img', {
					src: data.image,
					container: '.forum-left'
				});
			}
			else{
				$item.addClass('has-avatar').append( $('<div>', {class: 'itemAvatar avatar no-avatar'}).html( '<div class="initials"><i class="icon-image"></i></div>' ) );
			}

			$item.append( $('<div>', {class: 'itemContent '}).append(
			 	 ''

			 	, $('<div>', {class: 'text fwb'}).text( data.text )
			 	, $('<div>', {class: 'title'}).append(
			 		''
			 		, $('<span>', {class: 'top-code code'}).append( data.code )

			 		, $('<span>', {class: 'air'}).append(  
			 			''
				 		, ( data.air_code ? $('<i>', {class: 'air-code icoair-'+ data.air_code }): '' )
				 		, ( data.air_name ? $('<span>', {class: 'air-name', text: data.air_name}): '' )
			 		)
			 	)

				
				, ( data.subtext ? $('<div>', {class: 'subtext'}).text( data.subtext ): '' )
				, ( data.category ? $('<div>', {class: 'category'}).text( data.category ): '' )
				, ( data.category ? $('<div>', {class: 'time'}).text( data.time ): '' )
			) );

			/*$item.append(
				$('<div>', {class: 'clearfix'}).append(  )
			);*/

			return $item;
		},


		active: function (data) {
			var self = this;

			var $item = self.$listsbox.find('[data-id='+ data.id +']');
			if( $item.hasClass('active') ) return false;

			$item.addClass('active').siblings().removeClass('active');

			self.currItem = data;
			self.setProfile();

			self.actionTab();
		},
		setProfile: function () {
			var self = this;

			var $content = self.$elem.find('[role=content]');
			$content.addClass('has-loading');

			// setTimeout(function () {
				
				$content.removeClass('has-empty').removeClass('has-loading');

				$.each(self.currItem, function(index, val) {
					// console.log( index, val );
					var $el = $content.find('[data-profile='+ index +']');

					if( $el.length ){

						var type = $el.data('type') || 'text';
						if( type=='image' ){

							var $img = '<i class="icon-image"></i>';
							if( val ){
								$img = $('<img>', {src: val});
							}
							
							$el.html( $img );
						}
						else if( type=='flag' ){

							if( val ){
								val = '<i class="icoflag-'+ val +'"></i>';
							}

							$el.html( val );
						}
						else{
							$el.parent().toggleClass('hidden_elem', !val || val=='' || val==0);

							if( $el.length && val ){

								var tagName = $el.prop("tagName");
								if( tagName=='' ){

								}
								else{
									$el.html( val );
								}
							}
						}
					}
				});

				// console.log( self.currActionTab, self.currItem );

			// }, 800);
		},
		delProfile: function () {
			var self = this;

			var $content = self.$elem.find('[role=content]');
			$content.addClass('has-empty');
			
			self.$listsbox.find('[data-id='+self.currItem.id+']').remove();
			self.currItem = null;
		},

		actionTab: function () {
			var self = this;

			if( !self.currItem ) return false;

			//  Event.URL + 'companies/getTab/'
			self.$content.parent().addClass('has-loading');
			self.$elem.find('[role=main]').scrollTop(0);
			$.get( self.settings.tab_uri + self.currActionTab, {id: self.currItem.id}, function ( res ) {

				self.$content.parent().removeClass('has-loading');
				var $html = $(res);
				self.$content.html( $html );


				// resize role="main"
				var $main = self.$elem.find('[role=main]');
				$main.height( $(window).height() - $main.offset().top );
				

				Event.plugins( $html );
			});
		}
	}

	$.fn.dataforum = function( options ) {
		return this.each(function() {
			var $this = Object.create( DataForum );
			$this.init( options, this );
			$.data( this, 'dataforum', $this );
		});
	};
	$.fn.dataforum.options = {};
	
})( jQuery, window, document );