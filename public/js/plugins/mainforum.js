// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var MainForum = {
		init: function (options, elem) {
			var self = this;

			self.$elem = $(elem);
			self.$main = self.$elem.find('[role=main]');

			/*
			$.each( self.$form.find('[data-ref]'), function () {
				var key = $(this).attr('data-ref');
				self['$'+key] = $(this);
			} );

			self.action = {};
			$.each( self.$form.find('[data-action]'), function () {
				var key = $(this).attr('data-action');
				self.action['$'+key] = $(this);
			} );*/


			self.options = $.extend( {}, $.fn.mainforum.options, options );
			self.dataPost = {};

			if( !self.options.tab ){
				self.options.tab = self.$elem.find('[data-action-tab]').eq( 0 ).attr('data-action-tab');
			}

			self.$elem.find('[data-action-tab]').click(function() {

				if( $(this).hasClass('active') ) return false;
				self.options.tab = $(this).attr('data-action-tab');
				
				self.active();
			});

			self.resize();
			self.active();

		},
		resize: function () {
			var self = this;

			self.$elem.find('.forum-profile-sile').css({
				marginTop: $('.forum-toolbar').outerHeight()*-1
			});
		},

		active: function () {
			var self = this;

			setTimeout(function () {
				
				var uri = self.options.load + self.options.tab;

				self.$elem.find('[data-action-tab='+ self.options.tab +']').addClass('active').siblings().removeClass('active');
				self.dataPost = $.parseJSON( self.$elem.find('.active[data-action-tab]').attr('data-options') );

				$.get( uri, self.dataPost, function ( res ) {
					
					self.$main.html( res );
					Event.plugins( self.$main );
				});

			}, 1);
			
		}
	}
	$.fn.mainforum = function( options ) {
		return this.each(function() {
			var $this = Object.create( MainForum );
			$this.init( options, this );
			$.data( this, 'mainforum', $this );
		});
	};
	$.fn.mainforum.options = {
		index: 0,
		tab: '',
	};
	
})( jQuery, window, document );