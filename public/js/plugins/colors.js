// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {
	
	var Color = {
		init: function( options, elem ){
			var self = this;

			self.elem = elem;
			self.$elem = $( elem );

			self.options = $.extend( {}, $.fn.colors.options, options );

			self.placeholder();
			self.paint();

			self.display();
			self.choose();
			self.initEvent();
		},
		
		placeholder: function(){
			var self = this;

			if( !self.options.name ){
				self.options.name = $.trim(self.$elem.attr('name'));
			}
			
			if( self.$elem.val()!="" ){
				self.options.selected = self.$elem.val();
			}
			
			if( self.$elem.attr('max') ){
				self.options.max = self.$elem.attr('max')
			}
			
			if( self.$elem.attr('width') ){
				self.options.size[0] = self.$elem.attr('width')
			}
			
			if( self.$elem.attr('height') ){
				self.options.size[1] = self.$elem.attr('height')
			}
			
			// add style
            if ($('head style').hasClass('css-colors')==false) {
            	// add colors

                $('head').append( self.CSS() );
            }
			
			// ul
			self.$ul = $('<ul>').addClass('color-nav');
			
			// 
			self.$input = $('<input>')
							.addClass('hiddenField')
							.attr({
								type: "hidden",
								name: self.options.name
							});
			
			// 
			self.$UI = $('<div>')
				.addClass('UIColors')
				.append( self.$input )
				.append( self.$ul );
		},

		paint: function(){
			var self = this;

			$.each( self.options.colors, function( key, obj ){

				var BG = obj.code || obj.background;
				var BGBorder = obj.border || obj.foreground || BG;
				var val = obj.id || obj.value || key;

				self.$ul.append( $('<li>')
					.css({
						width: self.options.size[0],
						height: self.options.size[1],
					})
					.append( $("<a>")
						.css({
							backgroundColor: BG,
							borderRadius: self.options.size[2]
						})
						.attr('data-color', val)
						
					)
					.append( $("<div>")
						.addClass( "color-border" )
						.css({
							borderColor: BGBorder,
							borderRadius: self.options.size[2]
						})
					)
				);
			});
		},
		
		display: function(){
			this.$elem.replaceWith( this.$UI );
		},
		initEvent: function(){
			var self = this;
			
			
			self.$ul.find('a').click(function(e){
				e.preventDefault();
				
				self.choose( $(this).data('color') );				
			});
		},

		choose: function( val ){
			var self = this;
			
			if( !val ){
				val = self.$ul.find('[data-color]').first().attr('data-color');
			}

			self.$ul.find('[data-color='+val+']').parent().addClass('active').siblings().removeClass('active');
			
			// update input
			self.$input.val( val );
		},

		CSS: function(){
			
			return $('<style class="css-colors" type="text/css">' +
            	'.color-nav li{position:relative;z-index:1;overflow:hidden;background-color:transparent;padding:4px}'+
				'.color-nav li,.color-nav a{display:inline-block}'+
				'.color-nav a{position:relative;box-shadow:inset 0 1px 3px rgba(0,0,0,.3);display: block;width: 100%;height: 100%}'+
				'.color-nav .color-border{position: absolute;top: 0;left: 0;border: 2px solid #08c;bottom:0;right:0;z-index:-1;display:none}'+
				'.color-nav .active .color-border{display:block}'+
            '</style>');
		}

	}

	$.fn.colors = function( options ) {
		return this.each(function() {
			var $this = Object.create( Color );
			$this.init( options, this );
			$.data( this, 'colors', $this );
		});
	};
	// ae113d
	$.fn.colors.options = {
		selected: "",
		max: 1,
		size: { 0: 32, 1: 32, 2: "" },
		colors: [{
			blue: {
				code: "4617b4",
				border: "1f0068",
				title: "สีน้ำเงิน"
			},
			yellow: {
				code: "f4b300",
				border: "252525",
				title: "สีเหลือง"
			},
			red: {
				code: "b01e00",
				border: "4e0000",
				title: "สีแดง"
			},
			green: {
				code: "00c13f",
				border: "15992a",
				title: "สีเขียว"
			},
			grey: {
				code: "e7ebf1",
				border: "aaaaaa",
				title: "สีเทา"
			},
			black: {
				code: "252525",
				border: "000000",
				title: "สีดำ"
			},
			skyblue: {
				code: "1faeff",
				border: "1b58b8",
				title: "สีฟ้า"
			}
		}],
	};
	
})( jQuery, window, document );