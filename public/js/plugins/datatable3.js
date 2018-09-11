// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var DataTable3 = {
		init: function ( options, elem ) {
			var self = this;
			self.settings = $.extend({}, $.fn.datatable3.settings, options);

			self.$elem = $( elem );
			self.$el = {};
			$.each( self.$elem.find('[role]'), function(index, el) {
				var key = $(this).attr('role');
				self.$el[ key ] = $(this);
			});

			self.$listsbox = self.$elem.find('[role=listsbox]');
			self.$countVal = self.$elem.find('[role=countVal]');
			self.$filter = self.$elem.find('[role=filter]');
			self.$elem.addClass('off');
			self.$container = $( self.settings.container );

			if( self.settings.is_float ){
				self.$elem.addClass('is_float');
			}

			// set Data
			self.url = self.settings.url;
			self.options = {
				limit: self.$elem.find('[data-page-action=limit]').val() || 10,
				page: self.$elem.find('[data-page-action=page]').val() || 1,
				seq: 0,

				more: false,
				q: ''
			};
			self.total = 0;

			$.each(self.$elem.find('[data-action=change]'), function() {
				var val = $.trim($(this).val());
				var name = $.trim($(this).attr('name'));
				if( name && val!='' ){
					self.options[name]=val;
				}
			});
			$.each(self.$el.filter.find('[data-action=checked]'), function() {	
				var name = $(this).attr('name');
				if( $(this).prop('checked') ){
					self.options[ name ] = 1;
				}
			});
			$.each(self.$el.filter.find('[data-action=multichecked]'), function() {
					
				var name = $(this).attr('name');
				// var length = self.$el.filter.find('[data-action=multichecked][name='+name+']').length;

				self.options[ name ] = [];
				$.each(self.$el.filter.find('[data-action=multichecked][name='+name+']'), function(index, el) {
					if( $(this).prop('checked') ){
						self.options[ name ].push($(this).val());
					}
				});
			});


			self.resize();
			$(window).resize(function() {
				self.resize();	
			});


			self.refresh( 1 );
			self.events();
		},
		resize: function () {
			var self = this;


			return false;
			if( !self.settings.is_float ) return false;

			if( $(window).width() < 720 ){

				self.$el.header.find('table.tb-table').remove();

				if( self.$el.header ){
					self.$el.header.removeAttr('style');
				}

				if( self.$el.footer ){
					self.$el.footer.removeAttr('style');
				}

				self.$elem.removeAttr('style');

			}else{

				var offset = self.$elem.offset();

				self.$el.header.css({
					position: 'fixed',
					top: offset.top,
					left: offset.left,
					width: self.$elem.parent().outerWidth()
				});

				if( self.$el.header.find('table.tb-table').length==0 ){
					self.$el.header.append( $('<table>', {class: 'tb-table'}).html(self.$el.tabletitle.clone()) );
				}

				var headerHeight = self.$el.header.outerHeight() - self.$el.header.find('table.tb-table').outerHeight();
				// console.log( headerHeight );

				self.$elem.css({
					paddingTop: headerHeight
				});

				if( self.$el.tabletitle.length ){

					var w = self.$el.tabletitle.closest('table').outerWidth();
					
					self.resizeFooter();

				
					self.$el.tabletitle.find('tr:first>th[data-col]').removeAttr('style');
					self.$el.header.find('.tb-table th[data-col]').removeAttr('style');

					self.$el.header.find('.tb-table').css({
						width: w
					});


					self.$el.tabletitle.find('tr:first>th[data-col]').each(function ( i ) {
						var td = $(this);
						var th = self.$el.header.find('.tb-table th[data-col='+i+']');
						
						if( !td.hasClass('td-name') && !td.hasClass('hidden_elem') ){

							var w = td.outerWidth();

							if( th.outerWidth() > w ){
								w = th.outerWidth();
							}

							td.outerWidth( w ); 
							th.outerWidth( w );
						}
					});
				}
			}
		},
		resizeFooter: function () {
			var self = this;

			if( !self.$el.footer || !self.settings.is_float ) return false;

			var windowH = $(window).height();
			var val = $(window).scrollTop();

			var offset = self.$el.footer.parent().offset();
			var c = (offset.top+self.$el.footer.outerHeight()) - val;

			return false;
			if( c < windowH || $(window).width() < 720 ){
				self.$el.footer.removeAttr('style').removeClass('over');
				self.$elem.css({
					paddingBottom: 0
				});
			}
			else{

				self.$el.footer.css({
					position: 'fixed',
					// top: offset.top,
					bottom: 0,
					left: self.$elem.offset.left,
					width: self.$el.tabletitle.closest('table').outerWidth()
					// paddingTop: headerHeight
				}).addClass('over');



				self.$elem.css({
					paddingBottom: self.$el.footer.outerHeight()
				});
			}
		},
		events: function () {
			var self = this;

			self.$elem.find('[data-page-action=page]').change(function() {
				
				var page = parseInt( $(this).val() ) || 1;

				if( page!=self.options.page && page>0 ){

					var limit = parseInt( self.options.limit );
					var q = page*limit;

					if( q<self.total || limit*(page-1)<self.total ){
						self.options.page = page;
						self.refresh(200);
					}
					else{
						$(this).val( self.options.page );
					}
				}
				else{
					$(this).val( self.options.page );
				}
			});
			self.$elem.find('[data-page-action=limit]').change(function() {
				self.options.limit = parseInt( $(this).val() );
				self.options.page = 1;
				self.refresh(200);
			});
			
			self.$elem.find('[data-page-action=prev]').click(function() {
				self.options.page--;
				self.refresh(200);
			});
			self.$elem.find('[data-page-action=next]').click(function() {
				self.options.page++;
				self.refresh(200);
			});

			self.$elem.find('[data-action=formsearch]').submit(function(evt) {
				evt.preventDefault();

				var val = $.trim(self.$elem.find(':input#search-query').val());
				$(this).val( val );

				if( val!='' ){
					self.is_search = true;
					self.options.page = 1;
					self.options.q = val;

					self.refresh(200);
				}
			});

			self.$elem.find(':input#search-query').keyup(function() {
				var val = $.trim($(this).val());

				if( val=='' && val!=self.options.q){

					self.options.page = 1;
					self.options.q = '';
					self.refresh(100);
				}
			});

			self.$elem.find('[data-action=change]').change(function() {
				var val = $.trim($(this).val()),
					name = $.trim($(this).attr('name'));

				if( val!='' ){
					self.options[ name ] = val;
				}
				else{
					delete self.options[ name ];
				}

				self.options.page = 1;
				self.refresh(100);
			});

			$('.navigation-trigger').click(function(event) {
				setTimeout(function () {
					self.resize();
				}, 1);
			});


			// 
			self.$elem.find('[data-control-action=showsidebar]').click(function() {
				
				self.$elem.toggleClass('is-showsidebar');
				self.resize();
			});

			self.$elem.find('[data-control-action=refreshList]').click(function() {
				self.refresh(100);
			});
			

			self.$el.filter.find('[data-action=multichecked]').change(function(event) {
				self.options.page = 1;

				var name = $(this).attr('name');
				self.options[ name ] = [];
				$.each(self.$el.filter.find('[data-action=multichecked][name='+name+']'), function(index, el) {
					if( $(this).prop('checked') ){
						self.options[ name ].push($(this).val());
					}
				});

				self.refresh(100);
			});


			var windowH = $(window).height();
			$(window).scroll(function() {
				
				var val = $(this).scrollTop();
				self.$elem.toggleClass('is-scrolling', val > 60 );
				self.resizeFooter();

			});

			self.$elem.find('.entity-list').scroll(function(event) {
				var scrollLeft = $(this).scrollLeft();


				if( scrollLeft>0 ){
					self.$el.header.find('.tb-table').hide(1);
				}
				else{
					self.$el.header.find('.tb-table').show(1);
				}
				self.$el.header.find('.tb-table').css({
					left: scrollLeft*-1,
					position: 'relative',
				});

				// console.log( scrollLeft );
			});
		},
		refresh: function ( length, callback ) {
			var self = this;

			if( self.settings.is_float ){
				
				self.$container.animate({scrollTop: self.$elem.offset().top - 50},500);
			}

			self.$listsbox.empty();

			self.$elem
				.addClass('has-loading')
				.removeClass('has-error')
				.removeClass('has-empty')
				.removeClass('has-more');;

			// console.log( self.is_search );

			setTimeout(function () {

				self.fetch().done(function( results ) {

					self.options = $.extend({}, self.options, results.options); 

					if( results.error ){
						self.$elem.addClass('has-error');
						return false;
					}

					var total = parseInt(results.total) || 0;
					var page = parseInt(results.options.page) || 0;

					if( total == 0 && page==1){
						self.$elem.addClass('has-empty');

						return false;
					}

					self.$listsbox.html( results.$items );
					Event.plugins( self.$listsbox );


					self.$elem.find('[data-page-action=page]').val( self.options.page );

					if( self.$countVal ){

						var limit = parseInt( self.options.limit );
						var page = parseInt( self.options.page );
						var total = parseInt( results.total );

						var end = limit*page;
						var end = total < end ? total: end;


						self.$countVal.find('.start').text( ((limit*page) - limit)+1 );
						self.$countVal.find('.end').text( PHP.number_format(end)  );
						self.$countVal.find('.total').text( PHP.number_format(total)  );
						self.total = total;

						self.$elem.find('[data-page-action=next]').toggleClass('disabled', !self.options.more).prop('disabled', !self.options.more);
						self.$elem.find('[data-page-action=prev]').toggleClass('disabled', page==1).prop('disabled', page==1);

						self.$countVal.removeClass('hidden_elem');

					}

					self.resize();
					// console.log( self.options );
				});
			}, length, 1 );
		},
		fetch: function() {
			var self = this;

			// self.$el.filter.find(':input').not('.disabled').prop('disabled', true);
			return $.ajax({
				url: self.url,
				data: self.options,
				dataType: 'json'
			}).always(function () {
				// self.$el.filter.find(':input').not('.disabled').prop('disabled', false);
				self.$elem.removeClass('has-loading');

				if( self.is_search ){
					self.is_search = false;
				}

				if(self.$elem.hasClass('off')){
					self.$elem.removeClass('off');
				}

				self.resize();

			}).fail(function() {
				self.$elem.addClass('has-error');
			});
		},
	};

	$.fn.datatable3 = function( options ) {
		return this.each(function() {
			var $this = Object.create( DataTable3 );
			$this.init( options, this );
			$.data( this, 'datatable', $this );
		});
	};

	$.fn.datatable3.settings = {
		container: 'html, body',
		is_float: false
		// options: 
	};
	
})( jQuery, window, document );