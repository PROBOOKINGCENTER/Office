// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var Main2 = {
		init: function ( options, elem ) {
			var self = this;
			
			self.$elem = $(elem);
			self.$elem.addClass('off');
			self.setElem();

			self.options = $.extend( {}, $.fn.main2.options, options );
			// console.log( self.tab );

			self.tab = self.options.tab;
			self.tab.cache = {};
			// console.log( self.tab );

			self.resize();
			$( window ).resize(function () {
				self.resize();
			});

			self.Events();

			//  loadTab
			if( self.$el.$tabs && self.tab.current ){
				self.$el.$tabs.find('[data-tab-action='+ self.tab.current +']').trigger('click');
			}
		},
		setElem: function () {
			var self = this;
			
			self.$el = [];
			self.$elem.find('[role]').each(function () {
				var role = "$" + $(this).attr('role');
				self.$el[role] = $(this);

				$(this).removeAttr('role');
			});
		},
		resize: function () {
			var self = this;

			var outer = $( window );
			var offset = self.$elem.offset();
				
			// console.log( self.$el );
			/*self.$elem.css({
				paddingTop: self.$el.$header.outerHeight()
			});*/

		},

		Events: function () {
			var self = this;

			$('.navigation-trigger').click(function () {
				setTimeout(function () {
					self.resize();
				}, 1);
			});


			self.$elem.find('[data-control-action=refresh]').click(function() {
				// console.log( self.tab.current );
				tab = self.$el.$tabs.find('[data-tab-action='+ self.tab.current +']');
				if( tab.length ){
					li = tab.parent();

					self.updateTap( self.tab.current, tab.attr('href'), tab.attr('title') );
				}

			});

			if( self.$el.$tabs ){

				self.$el.$tabs.find('[data-tab-action]').click(function(e) {
					e.preventDefault();

					li = $(this).parent();

					if( li.hasClass('active') ){ return false; }

					li.addClass('active').siblings().removeClass('active');
					self.activeTab( $(this).data('tabAction'), $(this).attr('href'), $(this).attr('title') );
				});
			}
		},

		refresh: function ( length ) {
			var self = this;

			if( self.is_loading ) clearTimeout( self.is_loading ); 

			if ( self.$load.hasClass('has-error') ){
				self.$load.removeClass('has-error')
			}

			if ( self.$load.hasClass('has-empty') ){
				self.$load.removeClass('has-empty')
			}

			self.$load.addClass('has-loading');

			self.is_loading = setTimeout(function () {
				
				self.fetch().done(function( results ) {

					self.data = $.extend( {}, self.data, results.settings );
					self.$load.toggleClass( 'has-empty', parseInt(self.data.total)==0 ? true: false );
					
					self.setMore();

					/*if( results.selector ){
						self.setSelector( results.selector );
					}*/

					self.display( results.body );
				});
			}, length || 1);
		},
		fetch: function () {
			var self = this;

			var options = {};
			$.each( self.data.options, function (name, value) {
				if( value ) options[name] = value;
			} );
			self.data.options = options;

			var req = $.param( options );
			if( req && self.options.pushState ){
				var returnLocation = history.location || document.location,
					href = self.data.url+"?"+req,
					title = "";

				history.pushState('', title, href);
				document.title = title;
			}
			if( self.is_search ){
				self.$load.find('.search-input').attr('disabled', true);
			}
			return $.ajax({
				url: self.data.url,
				data: self.data.options,
				dataType: 'json'
			}).always(function () {

				self.$load.removeClass('has-loading');

				if( self.is_search ){
					self.$load.find('.search-input').attr('disabled', false);
					self.$load.find('.search-input').focus();

					self.is_search = false;
				}
				
			}).fail(function() { 
				self.$load.addClass('has-error');
			});
		},
		display: function( item ) {
			var self = this;

			$item = $( item );
			self.$tableBody.html( item );
			Event.plugins( self.$tableBody );

			if( self.$load.hasClass('offline') ){
				self.$load.removeClass('offline');
			}
			
			self._resize();

			// console.log( $item  );
			/*if ( self.options.transition === 'none' || !self.options.transition ) {
				self.$elem.html( self.tweets ); // that's available??
			} else {
				self.$elem[ self.options.transition ]( 500, function() {
					$(this).html( self.tweets )[ self.options.transition ]( 500 );
				});
			}*/
		},


		activeTab: function (key, link, title) {
			var self = this;

			if( self.tab.cache[ key ] && 1==2 ){
				self.displayTab(key);
			}
			else{
				self.updateTap(key, link, title)
			}
			
		},
		updateTap: function (key, link, title) {
			var self = this;

			setTimeout(function () {
				self.fetchTab(link).done(function( result ) {
					
					self.tab.cache[ key ] = {
						data: result,
						// options: data,
						link: link,
						title: title || '',
						timestamp: new Date().getTime()
					};

					self.displayTab( key );
				});

			}, 1);
		},

		fetchTab: function ( link, data ) {
			var self = this;

			return $.ajax({
				url: link,
				type: 'GET',
				// dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
				data: data || {},
			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
		},

		displayTab: function (key) {
			var self = this;

			// console.log( 'displayTab', key );
			self.tab.current = key;
			var $el = $(self.tab.cache[key].data);
			history.pushState('', key, self.tab.cache[key].link );
			self.$el.$content.html( $el );


			Event.plugins( $el );
		},


	};

	$.fn.main2 = function( options ) {
		return this.each(function() {
			var $this = Object.create( Main2 );
			$this.init( options, this );
			$.data( this, 'main2', $this );
		});
	};

	$.fn.main2.options = {
		pushState: false
	};
	
})( jQuery, window, document );