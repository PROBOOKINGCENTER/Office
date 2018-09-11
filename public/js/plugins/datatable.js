// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var DataTable = {
		init: function ( options, elem ) {
			var self = this;

			self.settings = $.extend({}, $.fn.datatable.settings, options);

			self.$elem = $( elem );
			self.$elem.addClass('off');
			self.$container = $( self.settings.container );

			self.$el = {};
			$.each( self.$elem.find('[role]'), function(index, el) {
				var key = $(this).attr('role');
				self.$el[ key ] = $(this);
			});
			
		
			self.data = {
				total: 0,
				options: self.settings.options,
				url: self.settings.url
			};

			if( self.$el.filter ){

				// set Data
				$.each(self.$el.filter.find('[data-action=change]'), function() {
					var val = $.trim($(this).val());

					if( val ){
						self.data.options[ $.trim($(this).attr('name')) ] = val;
					}
				});
				$.each(self.$el.filter.find('[data-action=checked]'), function() {
					
					var name = $(this).attr('name');
					if( $(this).prop('checked') ){
						self.data.options[ name ] = 1;
					}
					
				});
				$.each(self.$el.filter.find('[data-action=multichecked]'), function() {
					
					var name = $(this).attr('name');
					// var length = self.$el.filter.find('[data-action=multichecked][name='+name+']').length;

					self.data.options[ name ] = [];
					$.each(self.$el.filter.find('[data-action=multichecked][name='+name+']'), function(index, el) {
						if( $(this).prop('checked') ){
							self.data.options[ name ].push($(this).val());
						}
					});
				});
				
				
				// Event
				self.$el.filter.find('[data-action=multichecked]').change(function(event) {
					self.resetData();

					var name = $(this).attr('name');
					self.data.options[ name ] = [];
					$.each(self.$el.filter.find('[data-action=multichecked][name='+name+']'), function(index, el) {
						if( $(this).prop('checked') ){
							self.data.options[ name ].push($(this).val());
						}
					});

					self.refresh(100);

				});
				self.$el.filter.find('[data-action=change]').change(function(event) {
					self.resetData();

					self.data.options[ $.trim($(this).attr('name')) ] = $.trim($(this).val());
					self.refresh(100);

				});
				self.$el.filter.find('[data-action=checked]').change(function(event) {
					self.resetData();

					self.data.options[ $.trim($(this).attr('name')) ] = $(this).prop('checked') ? 1:0;
					self.refresh(100);

				});


				self.$el.filter.find('form[data-action=form]').submit(function(event) {
					event.preventDefault();
					var val = $.trim($(this).find('#search-query').val());

					if( val!='' ){
						self.resetData();

						self.data.options.q = val;
						self.refresh(100);
					}
					
				});

				self.$el.filter.find(':input#search-query').keyup(function() {
					var val = $.trim($(this).val());

					if( val=='' && val!=self.data.options.q){
						self.resetData();
						
						self.data.options.q = '';
						self.refresh(100);
					}
				});

				
				$('a.link-sort', self.$el.tabletitle).click( function (e) {

					var val = $(this).attr('data-sort') || 'asc';

					val = val=='asc' ? 'desc' : 'asc';
					$(this).attr('data-sort', val);
					self.resetData();


					self.data.options.dir = val;
					self.data.options.sort = $(this).attr('data-sort-val');
					self.refresh( 200 );

					// set elem
					self.$el.tabletitle.find( '.sorttable.asc' ).removeClass('asc');
					self.$el.tabletitle.find( '.sorttable.desc' ).removeClass('desc');
					$(this).parent().addClass( val );

					e.preventDefault();
				});
			}

			self.resize();
			$(window).resize(function(event) {
				self.resize();
			});

			self.refresh();


			// Event
			self.$elem.find('[data-action=tryagain]').click(function(event) {
				self.refresh(200);
			});
			$('.navigation-trigger').click(function(event) {

				setTimeout(function () {
					self.resize();
				},1);
			});

			self.$elem.find('[data-action=refresh]').click(function(event) {
				self.resetData();

				self.refresh(200);
			});

			$(window).scroll(function(event) {
				
				if( !$('html').hasClass('hasModel') ){
					self.$elem.toggleClass('is-scrolling', $(this).scrollTop() > 60 );

					var h = self.$el.tablelists.outerHeight() - $(this).outerHeight() + (self.$el.header.outerHeight() + self.$el.tabletitle.outerHeight());

					if( h - $(this).scrollTop() <= 200 && !self.$el.container.hasClass('has-loading') && !self.has_loading && self.data.options.more && !self.$el.container.hasClass('has-error') ){
						self.loadMore();
					}
				}
			});

			self.$elem.find('[data-action=more]').click(function(event) {
				self.loadMore();
			});

			self.$elem.find('[data-action=scrolltop]').click(function(event) {
				$('body,html').animate({scrollTop: 0},400);
			});


		},

		resize: function () {
			var self = this;

			var offset = self.$elem.offset();
			var left = offset.left;
			var right = 0, overHeight = 0;

			var headerHeight = self.$el.header.outerHeight();
			if( self.$el.header.css('display')=='none' ){
				headerHeight = 0;
			}

			var bg = $('#page-topbar').css('background') || $('body').css('background');

			self.$el.header.css({
				position: 'fixed',
				left: left,
				top: offset.top,
				right: right,
				background: bg,
				zIndex: 200,
			});

			// left;
			// right;

			self.$el.tabletitle.css({
				position: 'fixed',
				left: left,
				top: offset.top + headerHeight,
				// right: right,
				background: bg,
				zIndex: 200,

				paddingLeft: parseInt(self.$el.container.css('padding-left')),
				paddingRight: parseInt(self.$el.container.css('padding-right')),
			});


			var h = $(window).height() - (offset.top+self.$el.tabletitle.outerHeight());
			if( self.$el.footer ){

				var css = {
					position: 'fixed',
					left: left + parseInt(self.$el.container.css('padding-left')),
					// right: right+  parseInt(self.$el.container.css('padding-right')),
					bottom: 0,
					background: bg,
					zIndex: 200,
					top: 'auto',
					width: self.$el.tablelists.find('>table').outerWidth()
				};


				if( self.$el.tablelists.outerHeight() < h ){
					self.$el.footer.removeClass('over').removeAttr('style');
				}
				else{
					self.$el.footer.addClass('over').css(css);
				}

				
				self.$elem.css({
					paddingBottom: self.$el.footer.outerHeight()-1,
				});
			}

			self.$elem.css({
				minHeight: h,
				paddingTop: headerHeight,
				
			});


			self.$el.tabletitle.find('>table tr th').removeAttr('style');
			self.$el.tablelists.find('>table tr th').removeAttr('style');

			
			// self.resizeTable();
			/*self.$el.tabletitle.find('>table tr th.hidden_elem').removeClass('hidden_elem');
			self.$el.tablelists.find('>table tr td.hidden_elem').removeClass('hidden_elem');
			var tableWidth = 0, fullWidth = self.$elem.outerWidth();
			// console.log(tableWidth, fullWidth );
			self.$el.tablelists.find('>table tr:first>td').each(function ( i ) {
				var td = $(this);
				var th = self.$el.tabletitle.find('>table th[data-col='+i+']');

				tableWidth += td.outerWidth();
				if( tableWidth>fullWidth ){

					$.each( self.$el.tablelists.find('>table tr'), function() {
						$(this).find('>td').eq(i).addClass('hidden_elem');
					});

					th.addClass('hidden_elem');
					
					// self.$el.tablelists.find('>table td').eq(i).addClass('hidden_elem');
					self.resizeTable();
				}
			});*/

			self.resizeTable();
		},

		resizeTable: function () {
			var self = this;

			self.$el.tabletitle.find('>table').css({
				width: self.$el.tablelists.find('>table').outerWidth()
			});

			self.$el.tablelists.find('>table tr:first>th[data-col]').each(function ( i ) {

				var td = $(this);
				var th = self.$el.tabletitle.find('>table th[data-col='+i+']');

				if( !td.hasClass('name') && !td.hasClass('hidden_elem') ){

					var w = td.outerWidth();

					if( th.outerWidth() > w ){
						w = th.outerWidth();
					}

					td.width( w ); 
					th.width( w );
				}
			});
		},

		refresh: function ( length, callback ) {
			var self = this;

			if( self.is_loading ) clearTimeout( self.is_loading ); 

			self.$el.container
						.removeClass('has-error')
						.removeClass('has-empty')
						.removeClass('has-more')
						.addClass('has-loading');

			if( !self.data.url ) return false;
			self.is_loading = setTimeout(function () {
				self.fetch().done(function( results ) {

					if( results.error ){
						self.$el.container.addClass('has-error');
						return false;
					}
					
					Event.hideMsg();
					self.data = $.extend( {}, self.data, results.settings );

					if( self.data.total===false ){
						self.$el.container.addClass('has-error');
						return false;
					}

					self.$el.footer.find( '[ref=totalVal]' ).text( PHP.number_format( self.data.total ) ) 
					if( results.selector ){
						self.setSelector( results.selector );
					}

					if( self.data.options.sort && self.data.options.dir ){

						var dir = self.data.options.dir.toLowerCase();

						sort = self.data.options.sort.split(',');
						sort = sort[0].split(' ');

						var $sort = self.$el.tabletitle.find('[data-sort-val=' + sort[0] + ']');

						if( $sort.length ){
							self.$el.tabletitle.find('[data-sort-val=' + sort[0] + ']').attr('data-sort', self.data.options.dir).parent().removeClass( dir=='desc' ? 'asc':'desc' ).addClass( dir );
						}

					}

					self.$el.container.toggleClass('has-more', self.data.options.more);

					self.display( results.body );

					if( parseInt(self.data.total)==0 && parseInt(self.data.options.pager)==1 ){
						self.$el.container.addClass( 'has-empty' );
						self.$elem.addClass( 'off' ).removeClass('on');
					}	

					if( typeof callback === 'function' ){
						callback();
					}

				});
			}, length || 1);
		},
		fetch: function() {
			var self = this;

			self.$el.filter.find(':input').not('.disabled').prop('disabled', true);

			return $.ajax({
				url: self.data.url,
				data: self.data.options,
				dataType: 'json'
			}).always(function () {
				self.$el.filter.find(':input').not('.disabled').prop('disabled', false);
				self.$el.container.removeClass('has-loading');
			}).fail(function() { 
				self.$el.container.addClass('has-error');
			});
		},
		display: function( item ) {
			var self = this;

			var $table = $(item);
			if( self.data.options.pager==1 ){
				self.$el.listsbox.html( $table.find('tr') );
			}
			else{
				
	    		self.$el.listsbox.append( $table.find('tr') );
			}
			

			if( self.$elem.hasClass('off') ){
				self.$elem.removeClass('off').addClass('on');
			}

			self.resize();
			Event.plugins( self.$el.tablelists );
		},


		loadMore: function () {
			var self = this;

			var pager = parseInt( self.data.options.pager ), 
    		limit = parseInt(self.data.options.limit);

	    	self.data.options.pager = pager + 1;
	    	self.data.options.seq = (self.data.options.pager*limit)-limit;
	    	
			self.refresh(200);
		},

		resetData: function () {
			var self = this;

			self.data.options.pager = 1;
			self.data.options.seq = 0;

			$('body,html').scrollTop(0);
			// self.$elem.removeClass('on').addClass('off');
			self.$el.listsbox.empty();

		},
	};

	$.fn.datatable = function( options ) {
		return this.each(function() {
			var $this = Object.create( DataTable );
			$this.init( options, this );
			$.data( this, 'datatable', $this );
		});
	};

	$.fn.datatable.settings = {
		container: 'html, body',
		options: {
			limit: 30,
			pager: 1,
			seq: 0,

			more: true,
			q: ''
		}
	};
	
})( jQuery, window, document );