// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var InputSelect2 = {
		init: function ( options, elem ) {
			var self = this;

			self.options = $.extend( {}, $.fn.inputselect2.defaults, options );;
			self.is_focus = false;
			self._focus = true;
			self.selected = '';

			self.$elem = $('<div>', {class: "inputselect-wrapper"});
			self.$remove = $('<div>', {class: "inputselect-remove"}).html( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 224.512 224.512" xml:space="preserve"><g><polygon style="fill:#010002;" points="224.507,6.997 217.521,0 112.256,105.258 6.998,0 0.005,6.997 105.263,112.254    0.005,217.512 6.998,224.512 112.256,119.24 217.521,224.512 224.507,217.512 119.249,112.254"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>' );

			self.$inputField = $(elem);
			self.setFlyout();

			self.$inputField.wrap( self.$elem );
			self.$inputField.addClass('inputselect-input');
			self.$inputField.parent()
				.append( '<div class="inputselect-loader loader-spin-wrap"><div class="loader-spin"></div></div>' )
				.append( self.$remove );

			// Event
			self.$inputField.focus(function() {
				self.search();

			}).blur(function() {
				
				if( !self.is_focus ){
					self.hide();
				}

				self.$inputField.val( $.trim( $(this).val() ) )

			}).keyup(function() {

				if( typeof self.options.onEvent === 'function' ){
					self.options.onEvent();
				}

				if( $.trim( $(this).val() )=='' ){
					self.search();
					self.$inputField.parent().removeClass('has-data');
				}
				else{
					self.search();
				}
			});

			self.$remove.click(function() {				
				self.$inputField.val('').focus().parent().removeClass('has-data');

				if( typeof self.options.onEvent === 'function' ){
					self.options.onEvent();
				}
			});


			self.$listbox.mouseenter(function(){
				self.is_focus = true;
			}).mouseleave(function(event) {
				self.is_focus = false;
			});

			self.$listbox.delegate('>li', 'mouseenter', function(event) {
				$(this).addClass('active').siblings().removeClass('active');
			});

			self.$listbox.delegate('>li', 'click', function(event) {
				var data = $(this).data();
				self.$inputField.val( data.text ).parent().addClass('has-data');

				if( typeof self.options.onEvent === 'function' ){
					self.options.onEvent();
				}

				if( typeof self.options.onSelected === 'function' ){
					self.options.onSelected(self, data);
				}
				self.hide();
			});
			
			if( self.$inputField.val()!='' ){
				self.$inputField.parent().addClass('has-data');
			}
		},

		setFlyout: function () {
			var self = this;

			self.$flyout = $('<div>', {class: 'ui-flyout'});
			self.$listbox = $('<ul>', {class: 'list-menu'});

			self.$flyout.append( self.$listbox );
		},

		search: function () {
			var self = this;
			var keyword = $.trim( self.$inputField.val() );
			setTimeout(function () {
				self.$listbox.empty();

				$.each( self.options.items, function(i, obj) {

					obj.text = $.trim( obj.name || obj.text );

					if( (((obj.text.toUpperCase().search( keyword )>=0 || obj.text.toLowerCase().search( keyword )>=0) && keyword!='') || keyword=='') && $.inArray(obj.text, self.options.selected)<0 ){

						self.$listbox.append( self.setItem( obj ) );
					}
					
				});

				self.show();

			}, 1);
		},

		getOffset: function () {
			var self = this, 
				style = {}, 
				offset = self.$inputField.offset();

			var style = {
				top: offset.top + self.$inputField.outerHeight(),
				left: offset.left
			};

			self.$flyout.css( style );
		},

		show: function () {
			var self = this;

			$( self.options.container ).append( self.$flyout );
			self.getOffset();

			self.$flyout.toggleClass('open', self.$listbox.find('>li').length>0);
		},
		getOffset: function () {
			var self = this, 
				style = {}, 
				offset = self.$inputField.offset();

			if( self.options.container=='body' ){
				style = {
					top: offset.top + self.$inputField.outerHeight(),
					left: offset.left
				};
			}

			style.minWidth = self.$inputField.outerWidth();
			self.$flyout.css( style );
		},

		setItem: function (data) {
			var self = this;

			var li = $('<li>').append( 
				  $('<div>', {class: 'text'}).text( data.text )
				, (data.category ? $('<div>', {class: 'category'}).text( data.category ): '' ) 
			);

			li.data( data );

			return li;
		},
		hide: function () {
			var self = this;

			if( self.$flyout ){

				self.$flyout.removeClass('open');
				// self.$flyout.remove();
			}
		},
	};


	$.fn.inputselect2 = function( options ) {
		return this.each(function() {
			var $this = Object.create( InputSelect2 );
			$this.init( options, this );
			$.data( this, 'inputselect2', $this );
		});
	};

	$.fn.inputselect2.defaults = {
		container: 'body',
		items: [],
		selected: []
	};
	
})( jQuery, window, document );

