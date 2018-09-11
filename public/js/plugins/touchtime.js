// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var TouchTime = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $(elem);
			self.$elem.addClass('input-touchtime');
			self.options = $.extend( {}, $.fn.touchtime.options, options );;

			self.$wrap = $('<div>', {class: 'touchtime-wrap'});
			self.$dropdown = self.setItem(); // self.setItem[ self.options.type ];

			self.is_focus = false;
			self.$elem.wrap( self.$wrap );
			self.$elem.after( self.$dropdown );

			self.active();

			self.$elem.keydown(function (e) {

		        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
		            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
		            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
		            (e.keyCode >= 35 && e.keyCode <= 39)) {
		                 return;
		        }

		        // Ensure that it is a number and stop the keypress
		        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		            e.preventDefault();
		        }
		    });

			self.$elem.focus(function (e) {
				self.$dropdown.parent().addClass('open');
				e.preventDefault();
			}).blur(function() {
				
				if( !self.is_focus ){
					self.$dropdown.parent().removeClass('open');
					$(this).val( $.trim( $(this).val() ) );
					self.active();
				}
			});

			self.$dropdown.find('[data-value]').click(function () {
				
				self.$elem.val( $(this).attr('data-value') );
				self.active();
			} );

			self.$dropdown.mouseenter(function() {
				self.is_focus = true;
			}).mouseleave(function() {
				self.is_focus = false;
			});

			self.$dropdown.click(function() {
				self.$dropdown.parent().removeClass('open');

				if( self.options.next && $.trim( self.$elem.val() )!='' ){
				    if( $(self.options.next).length ){
				    	if( $(self.options.next).val()=='' ){
				    		$(self.options.next).focus();
				    	}
				    }
				}
			});
		},

		active: function () {
			var self = this;

			self.curr = $.trim( self.$elem.val() );
			self.$dropdown.find('[data-value='+ self.curr +']').addClass('active').siblings().removeClass('active');
		},

		setItem: function () {
			var self = this;

			var $ul = $('<div>', {class: 'list-touchtime'});

			if( self.options.type=='hour' ){
				for (var i = 0; i < 24; i++) {
					var hour = i<10 ? "0"+i: i;
					$ul.append( $('<div>', {class: 'list-touchtime-item', 'data-value': hour}).append( hour ) );
				}
			}

			if( self.options.type=='minute'  ){

				for (var i = 0; i < 12; i++) {
					var minute = (i*5);

					minute = minute<10 ? "0"+minute: minute;
					$ul.append( $('<div>', {class: 'list-touchtime-item', 'data-value': minute}).append( minute ) );
				}
			}

			return $ul;
		},

	};

	$.fn.touchtime = function( options ) {
		return this.each(function() {
			var $this = Object.create( TouchTime );
			$this.init( options, this );
			$.data( this, 'touchtime', $this );
		});
	};

	$.fn.touchtime.options = {
		speed: 500,
		// wrapEachWith: '<div></div>',
		auto: true,
		refresh: 13000,
		random: true,

		type: 'hour'
	};
	
})( jQuery, window, document );