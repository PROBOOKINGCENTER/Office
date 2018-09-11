// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var PageProfile = {
		init: function (options, elem) {
			var self = this;


			// set Data
			self.options = $.extend( {}, $.fn.pageProfile.options, options );

			if( !self.options.href ){
				var returnLocation = history.location || document.location;
				self.options.href = returnLocation.href + '/';
			}
			

			// set Elem
			self.$elem = $(elem);
			self.$header = self.$elem.find('.profile-header');
			self.$left = self.$elem.find('.profile-left');
			self.$left = self.$elem.find('.profile-left');
			self.$toolbar = self.$elem.find('.profile-actions-toolbar');
			self.$content = self.$elem.find('.profile-content');
			self.$main = self.$elem.find('.profile-main');


			// 
			/*var title = $('.page-title').html();
			$('.global-nav').css({'opacity':0});
			$('.page-title').html( $('<div>', {class: 'ellipsis'}).html( title ) );*/

			if( !self.options.tab ){
				self.options.tab = self.$toolbar.find('[data-tab-action]').first().attr('data-tab-action');
			}
			else if(self.$toolbar.find('[data-tab-action='+ self.options.tab +']').length==0){
				self.options.tab = self.$toolbar.find('[data-tab-action]').first().attr('data-tab-action');
			}



			if( !self.options.ajax_tab ){
				self.options.ajax_tab = app.getUri('operation/tour/');
			}

			self.loadTab(200);

			// Event
			setTimeout(function () { self.resize(); }, 1);
			$(window).resize(function() {
				self.resize();
			});

			$('.navigation-trigger').click(function() {
				setTimeout(function () {
					self.resize();
				}, 1);
			});

			self.$elem.find('[data-tab-action]').click(function() {

				if( $(this).hasClass('active') ){
					return false;
				}

				self.options.tab = $(this).attr('data-tab-action');
				self.loadTab();
			});

			self.$content.find('[data-action=tryagain]').click(function() {
				self.loadTab( 200 );
			});
		},

		resize: function () {
			var self = this;

			var left = self.$left.outerWidth() + $('.navigation-main').outerWidth(),
				right = 0;

			self.$header.css({
				position: 'fixed',
				left: $('.navigation-main').outerWidth(),
				paddingLeft: self.$left.outerWidth(),
				right: right,
				zIndex: 250,
				top: $('.page-topbar').outerHeight(),
				background: $('#page-topbar').css('background'),
			});
			self.$left.css({
				position: 'fixed',
				top: $('.page-topbar').outerHeight(),
				left: $('.navigation-main').outerWidth(),
				bottom: 0,
				zIndex: 280,
			});


			setTimeout(function () {
				// console.log( self.$left, self.$left.outerHeight(), self.$left.find('.profile-left-header').outerHeight() );	

				self.$content.css({
					marginLeft: self.$left.outerWidth(),
					paddingTop: self.$header.outerHeight()
				});

				self.$left.find('.profile-left-content').css({

					height: self.$left.outerHeight() - self.$left.find('.profile-left-header').outerHeight(),
					overflowY: 'auto'
				});

			}, 100);
		},


		loadTab: function ( length ) {
			var self = this;

			var curr = self.$elem.find('[data-tab-action='+ self.options.tab +']');

			curr.addClass('active').siblings().removeClass('active');
			self.$toolbar.find('.active-bar').animate({
				width: curr.outerWidth(),
				left: curr.position().left
			}, 200);

			self.$content.removeClass('has-emtyp').removeClass('has-error');
			// self.$content.addClass('has-loading');

			setTimeout(function () {
				
				self.fetchTab().done(function (html) {

					history.pushState('', self.options.tab, self.options.href + self.options.tab );

					self.$main.html( html ); 
					Event.plugins( self.$main );

					self.resize();
				});
			}, length|| self.options.delay);
		},

		fetchTab: function () {
				var self = this;

			return $.ajax({
				url: self.options.ajax_tab + self.options.id +'/'+ self.options.tab,
				type: 'GET',
				// dataType: 'json',
				data:  self.options.get_data || {},
			})
			.fail(function() {
				self.$content.removeClass('has-error');
				console.log("error");
			})
			.always(function() {
				self.$elem.removeClass('off');

				self.$content.removeClass('has-loading');

				var curr = self.$elem.find('[data-tab-action='+ self.options.tab +']');
				self.$toolbar.find('.active-bar').animate({
					width: curr.outerWidth(),
					left: curr.position().left
				}, 1);

				// console.log("complete");
			});
		},
	};

	$.fn.pageProfile = function( options ) {
		return this.each(function() {
			var $this = Object.create( PageProfile );
			$this.init( options, this );
			$.data( this, 'pageProfile', $this );
		});
	};

	$.fn.pageProfile.options = {
		delay: 1
	};
	
})( jQuery, window, document );