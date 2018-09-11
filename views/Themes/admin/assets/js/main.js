/*! =========================================================
          .o88
          "888
 .oooo.    888  .oo.    ..o88.     ooo. .oo.    .oooo`88
d88' `8b   88bP"Y88b   888P"Y88b  "888P"Y88b   888' `88b 
888        88b   888   888   888   888   888   888   888
888. .88   888   888   888   888   888   888   888. .880
 8`bo8P'  o888o o888o   8`bod8P'  o888o o888o   .oooo88o
                                                     088`
                                                    .o88
============================================================ */

var __ui = {
	anchorBucketed: function (data) {
		
		var anchor = $('<div>', {class: 'anchor ui-bucketed clearfix'});
		var avatar = $('<div>', {class: 'avatar lfloat no-avatar mrm'});
		var content = $('<div>', {class: 'content'});
		var icon = '';

		if( !data.image_url || data.image_url=='' ){

			icon = 'user';
			if( data.icon ){
				icon = data.icon;
			}
			icon = '<div class="initials"><i class="icon-'+icon+'"></i></div>';
		}
		else{
			icon = $('<img>', {
				class: 'img',
				src: data.image_url,
				alt: data.text
			});
		}

		avatar.append( icon );

		var massages = $('<div>', {class: 'massages'});

		if( data.text ){
			massages.append( $('<div>', {class: 'text fwb u-ellipsis'}).html( data.text ) );
		}

		if( data.category ){
			massages.append( $('<div>', {class: 'category'}).html( data.category ) );
		}
		
		if( data.subtext ){
			massages.append( $('<div>', {class: 'subtext'}).html( data.subtext ) );
		}

		content.append(
			  $('<div>', {class: 'spacer'})
			, massages
		);
		anchor.append( avatar, content );

        return anchor;
	},
	anchorFile: function ( data ) {
		
		if( data.type=='jpg' ){
			icon = '<div class="initials"><i class="icon-file-image-o"></i></div>';
		}
		else{
			icon = '<div class="initials"><i class="icon-file-text-o"></i></div>';
		}
		
		var anchor = $('<div>', {class: 'anchor clearfix'});
		var avatar = $('<div>', {class: 'avatar lfloat no-avatar mrm'});
		var content = $('<div>', {class: 'content'});
		var meta =  $('<div>', {class: 'subname fsm fcg'});

		if( data.emp ){
			meta.append( 'Added by ',$('<span>', {class: 'mrs'}).text( data.emp.fullname ) );
		}

		if( data.created ){
			var theDate = new Date( data.created );
			meta.append( 'on ', $('<span>', {class: 'mrs'}).text( theDate.getDate() + '/' + (theDate.getMonth()+1) + '/' + theDate.getFullYear() ) );
		}

		avatar.append( icon );

		content.append(
			  $('<div>', {class: 'spacer'})
			, $('<div>', {class: 'massages'}).append(
				  $('<div>', {class: 'fullname u-ellipsis'}).text( data.name )
				, meta
			)
		);
		anchor.append( avatar, content );

        return anchor;
	},

};
var PreviewImage = {

	remove: function (el) {
		var $box = $(el).closest('.preview-image');
		var $preview = $box.find('[role=preview]');
		var $input = $box.find(':input[type=file]');

		$input.val('');
		$box.css({
    		height: $box.data('height'),
    	}).addClass('has-empty').removeClass('has-image');
	    $preview.empty();
	},

	trigger: function (el) {
		var $box = $(el).closest('.preview-image');
		var $input = $box.find(':input[type=file]');

		$input.trigger('click');
	},

	change: function (el) {
		var file = el.files[0],
			$image = $('<img/>', {class: 'img img-preveiw',alt: ''});

		var $box = $(el).closest('.preview-image');
		var $preview = $box.find('[role=preview]');

		if( file ){
			$box.removeClass('has-empty').removeClass('has-image').addClass('has-loading');

			var reader = new FileReader();

			reader.readAsDataURL(file);
			reader.onload = function(e){

				var image = new Image();
		        image.src = e.target.result;
		        image.onload = function() {

		        	$box.removeClass('has-loading');
		        	$box.animate({height: (this.height*$box.data('width')) / this.width,}, 200, function () {
		        		$box.addClass('has-image');
		        		$preview.html($image.attr('src', e.target.result));
		        	});
		        	// display
		        }
			}

			var progress = $box.find('.progress-bar>span');
			// console.log( progress.length );
			if( progress.length ){
				progress.css('width', '');
				reader.onprogress = function(data) {
		            if (data.lengthComputable) {                                            
		                progress.css('width', parseInt( ((data.loaded / data.total) * 100), 10 ));
		                // console.log(progress);
		            }
		        }
			}
		}
	},

	load: function (el, url) {
		
		console.log( el, url );
	}
};

var Input = {
	num: {
		init: function (options, elem) {
			var self = this;
			self.options = options;
			self.$elem = $(elem);

			self.$elem.attr('type', 'text').addClass('inputnum');
			self.set();

			// Event
			self.$elem.keydown( function(e) {

				// Allow: backspace, delete, tab, escape, enter and .
		        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
		             // Allow: Ctrl/cmd+A
		            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
		             // Allow: Ctrl/cmd+C
		            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
		             // Allow: Ctrl/cmd+X
		            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
		             // Allow: home, end, left, right
		            (e.keyCode >= 35 && e.keyCode <= 39)) {
		                 // let it happen, don't do anything
		                 return;
		        }

		        if( e.ctrlKey && e.keyCode==86 ){
		        	
		        }
		        // Ensure that it is a number and stop the keypress
		        else if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		            e.preventDefault();
		        }

			}).click(function () {

				if( self.$elem.val()!='' ){ 
					self.$elem.select();
				}
				
			}).keyup(function(e) {
				self.set();
				
			}).blur('blur', function(e) {
				self.set();
			});
		},
		set: function () {
			var self = this;

			if( self.$elem.val()!='' ){ 
				self.$elem.val( PHP.number_format( self.$elem.val() ) ); 
			}
		},

		get_numbers: function (input) {
			var number = input.match(/[0-9]+/g);
			if( number ){
				number = number.join([]);
			}
			return number;
		}
	},

	image: {
		init: function (options, elem) {
			var self = this;

			self.$elem = $(elem);
			self.$input = self.$elem.find(':input[type=file]');
			self.$preview = self.$elem.find('[role=preview]');
			self.options = $.extend( {}, {
				width: 230,
				height: 230,
				name: 'file1',
			}, options );

			if( self.options.src ){

				self.$inputHas = $('<input type="hidden" name="_'+ self.options.name +'" value="1" autocomplete="off" >');
				self.$elem.append(self.$inputHas);

				self.$refresh =  $('<button type="button" class="uiCoverImage_refresh" data-action="refresh">').html( '<i class="icon-refresh"></i>' );
				self.$elem.append( self.$refresh );

				self.loadImage( self.options.src );
			}

			self.$input.change( function () {
				var file = this.files[0];

				if( file ){
					self.reader( file );
				}
			} );

			self.$elem.find('[data-action=remove]').click(function() {
				
				self.$input.val('');
				self.$elem.css({
		    		height: self.options.height,
		    	}).addClass('has-empty').removeClass('has-image');
			   	self.$preview.empty();

			   	if( self.$inputHas ){
			   		self.$inputHas.val( 0 );
			   		self.$refresh.show(1);
			   	}
			});

			self.$elem.find('[data-action=trigger]').click(function() {
				self.$input.trigger('click');
			});

			if( self.$refresh ){
				self.$refresh.click(function() {
					self.$inputHas.val( 1 );
					self.$input.val('');
					self.loadImage( self.options.src );
				});
			}
		},
		reader: function ( file ) {
			var self = this;

			var reader = new FileReader();

			reader.readAsDataURL(file);
			reader.onload = function(e){

				self.loadImage(e.target.result)
			}
		},

		loadImage: function ( src ) {
			var self = this;

			self.$elem.removeClass('has-empty').removeClass('has-image').addClass('has-loading');
			var $image = $('<img/>', {class: 'img img-preveiw',alt: ''});
			var image = new Image();
	        image.src = src;
	        image.onload = function() {

	        	self.$elem.removeClass('has-loading');
	        	self.$elem.animate({height: (this.height* self.options.width) / this.width,}, 200, function () {
	        		self.$elem.addClass('has-image');
	        		self.$preview.html($image.attr('src', src));
	        	});

	        	if( self.$refresh ){


	        		self.$refresh.hide(1);
	        	}


	        	if( self.$input.val()!='' && self.$inputHas ){
	        		self.$inputHas.val( 0 );
	        		self.$refresh.show(1);
	        	}

	        	// display
	        }
		},
	},

	mask: function () {
		
	}
};
var Tour = {
	lists: function (data) {
		return $.ajax({url: app.getUri( 'ajax_lists/seriesList' ), type: 'GET', dataType: 'json', data: data || {}});
	},
	form: {
		init: function (options, elem) {
			var self = this;
			self.options = options;
			self.$elem = $(elem);

			self.countryChange();
			self.$elem.find('[name=country_id]').change(function() {
				self.countryChange();
			});

		},

		countryChange: function () {
			var self = this;

			var val = self.$elem.find('[name=country_id]').val();

			if( val=='' ){
				self.$elem.find('[name=city_id]').empty();
				self.$elem.find('[name=city_id]').append( $('<option>', {value: '', text: '-' }) );
				self.$elem.removeClass('has-city');
				return false;
			}


			Tour.Location.cityList({country: val}).done(function (resp) {

				self.$elem.find('[name=city_id]').empty();
				self.$elem.find('[name=city_id]').append( $('<option>', {value: '', text: '-' }) );

				if( resp.length==0 ){
					self.$elem.removeClass('has-city');
				}
				else{
					self.$elem.addClass('has-city');
					var selected = '';
					$.each(resp, function(key, value) {

						var opt = $('<option>', {value: value.id, text: value.name });
						if( self.options.city_id==parseInt(value.id) ){	
							selected = parseInt(value.id);
						}

						self.$elem.find('[name=city_id]').append( opt );
					});

					self.$elem.find('[name=city_id]').val(selected);
				}
			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
		}
	},
	PeriedForm: {
		init: function (options, elem) {
			var self = this;

			self.settings = $.extend( {}, $.fn.peried__form.settings, options );
			self.$form = $(elem);


			self.$bus = self.$form.find('[role=buslist]');
			self.$tableprice = self.$form.find('[role=tableprice]');



			if( self.settings.bus.length==0 ){
				self.addBus(1);
			}
			



			self.$form.find('[data-bus-action=add]').click(function() {

				var next = true;
				$.each(self.$bus.find('input.inputbus'), function(index, el) {
					
					if( $.trim( $(this).val() )=='' ){
						next = false;
						$(this).focus();
						return false;
					}
				});

				if( next ){
					self.addBus( self.busVal+1 );
					self.$bus.find('input.inputbus').last().focus();
				}
				
			});

			self.$form.delegate('[data-bus-action=remove]', 'click', function(event) {
				
				if( self.busVal > 1){
					$(this).closest('tr').remove();
					self.update();
				}
			});
		},

		addBus: function ( seq, val ) {
			var self = this;

			self.$bus.append( $('<tr>').append(
				  $('<td>', {class: 'name'}).html( $('<label>', {for: '', text: 'Bus ' + seq}) )
				, $('<td>', {class: 'price'}).html( $('<input>', {
					type: 'text', 
					class: 'inputbus inputtext',
					// id: 'bus_'+seq,
					name: 'bus['+seq+'][seat]',
					value: val || '',
					autocomplete: 'off',
					'data-plugin': 'input__num',
				}) )
				, $('<td>', {class: 'action'}).append( 
					$('<button>', {type: 'button', class: 'btn', 'data-bus-action':'remove'}).html( '<i class="icon-remove"></i>' )
				)
			) );

			Event.plugins( self.$bus );

			self.update();
			// Event.plugins( self.$tableprice );
		},

		update: function () {
			var self = this;
			self.busVal = self.$bus.find('tr').length;

			self.$tableprice.find('.title2').empty();
			self.$tableprice.find('.title1 .name').prop('rowspan', 2);
			self.$tableprice.find('.title1 .price').prop('colspan', self.busVal);

			for (var i=1; i <= self.busVal; i++) {
				self.$tableprice.find('.title2').append( $('<td>', {class: 'bus', text: 'Bus '+i}) );
			}

			
			$.each( self.$tableprice.find('tbody>tr'), function() {

				var seq = 0;
				$.each( $(this).find('td.price'), function() {
					seq++;
					if( seq > self.busVal ){
						$(this).remove();
					}
				});


				if( seq < self.busVal ){

					$(this).append( $('<td>', {class: 'price'}).html( $('<input>', {
						type: 'text', 
						class: 'inputtext',
						// id: 'price_'+seq,
						name: 'bus['+(seq+1)+']['+$(this).data('name')+']',
						value: $(this).find('td.price').first().find(':input').val(),
						autocomplete: 'off',
						'data-plugin': 'input__num',
					}) ) );

					Event.plugins( $(this) );
				}

				/*var Val = $(this).find('td.price').first();
				$(this).find('td.price').remove();

				for (var i=1; i <= self.busVal; i++) {
					
				}*/
			});

		}
	},

	Location: {
		countryList: function (data) {
			return $.ajax({url: app.getUri( 'tour/location/country/list' ), type: 'GET', dataType: 'json', data: data});
		},

		cityList: function (data) {
			return $.ajax({url: app.getUri( 'tour/location/city/list' ), type: 'GET', dataType: 'json', data: data});
		}
	}
};

var _Plugins = {
	chooseBookbank: {
		init: function (options, elem) {
			var self = this;

			self.$elem = $(elem);
			self.$form = self.$elem.closest('form');
			self.inputs = ['branch', 'name', 'code']

			self.$elem.change(function() {
				var id = $(this).val();

				var is = false;
				$.each( options.items, function(i, obj) {
					if( obj.id = id ){ self.change(obj); is=true }
				});

				if( !is ){
					self.change({});
				}
			});
		},

		change: function (data) {
			var self = this;

			$.each( self.inputs, function(i, key) {
				var val = '';
				if( typeof data[key] !== 'undefined' ){
					val = data[key];
				}
				self.$form.find(':input[name='+ key +']').val( val );
			});
		}
	}
};

var _bindPlugins = function () {
	
	$.fn.series__form = function( options ) {
		return this.each(function() {
			var $this = Object.create( Tour.form );
			$this.init( options, this );
			$.data( this, 'series__form', $this );
		});
	};
	$.fn.input__num = function( options ) {
		return this.each(function() {
			var $this = Object.create( Input.num );
			$this.init( options, this );
			$.data( this, 'input__num', $this );
		});
	};
	$.fn.number_format = function( options ) {
		return this.each(function() {
			var $this = Object.create( Input.num );
			$this.init( options, this );
			$.data( this, 'number_format', $this );
		});
	};

	$.fn.peried__form = function( options ) {
		return this.each(function() {
			var $this = Object.create( Tour.PeriedForm );
			$this.init( options, this );
			$.data( this, 'peried__form', $this );
		});
	};
	$.fn.peried__form.settings = {
		// busVal: 1,
		bus: []
	}
	$.fn.input__image = function( options ) {
		return this.each(function() {
			var $this = Object.create( Input.image );
			$this.init( options, this );
			$.data( this, 'input__image', $this );
		});
	};
	$.fn.peried__form = function( options ) {
		return this.each(function() {
			var $this = Object.create( Tour.PeriedForm );
			$this.init( options, this );
			$.data( this, 'peried__form', $this );
		});
	};

	$.fn.choose__bookbank = function( options ) {
		return this.each(function() {
			var $this = Object.create( _Plugins.chooseBookbank );
			$this.init( options, this );
			$.data( this, 'choose__bookbank', $this );
		});
	};
};

(function( $, window, document, undefined ) {
	_bindPlugins();


	

})( jQuery, window, document );


$(document).ready(function() {

	function pageResize() {

		if( $(window).width()<1024 && $('body').hasClass('is-pushed-left') ){
			$('body').removeClass('is-pushed-left');
		}

		$('body').toggleClass('is-overlay-left', $(window).width()<1024 );
	}

	function closeShowImage() {
		var leyer = $('body').find('.leyer-show-image');

		if( leyer.length == 1 ){
			var data = leyer.data();
			is_leyerShowImage = setTimeout(function () {
				leyer.remove();
			}, data.delay || 1);
		}
	}

	function vBell() {
		$('.peepr-drawer-container').toggleClass('open');
			
		if( $('body').hasClass('peepr') ){

			var scroll = parseInt($('#doc').css('top'));
			if( $('#doc').hasClass('fixed_elem') && !$('html').hasClass('hasModel') ){
				$('#doc').removeClass('fixed_elem')
				$('html, body').scrollTop(scroll*-1);
			}

			$('body').removeClass('peepr');
			$('.ui_peepr_glass').remove();
		}
		else{

			var scroll = $(window).scrollTop();
			if( !$('#doc').hasClass('fixed_elem') ){
				$('#doc').addClass('fixed_elem').css('top', scroll*-1);
			}


			$('.peepr-drawer-container').before('<div class="ui_peepr_glass"></div>');
			$('body').addClass('peepr');
		}
	}

	pageResize();
	$(window).load(function(){

			
		$('#doc').addClass('on');
		pageResize();	

		$(".navigation-trigger").click(function(e){
			e.preventDefault();
			$("body").toggleClass("is-pushed-left",!$("body").hasClass("is-pushed-left"));
			$.get( app.getUri("me/navTrigger"), {status:$("body").hasClass("is-pushed-left")?1:0} );
		});

		if( isMobile.any() ){
			$("body").addClass('touch').removeClass('is-pushed-left');
		}

		$('[data-global-action=bell]').click(function() {
			vBell();
		});

		$('body').delegate('.ui_peepr_glass', 'click', function(event) {
			vBell();
		});


		var is_leyerShowImage;
		
		$('body').delegate('.js-show-image', 'mouseenter', function(event) {
			var data = $(this).data('img');

			clearTimeout( is_leyerShowImage );
			var max_width = data.max_width || 300;
			var container = data.container || 'body';

			if( data.src ){

				var image = new Image();
				image.onload = function(){
				    // image.src = this.src;
				    var size = {
				    	width: this.width,
				    	height: this.height,
				    } 
				    if( size.width > max_width ){
				    	size.height = (this.height * max_width) / this.width;
				    	size.width = max_width;
				    }

				    var leyer = $('body').find('.leyer-show-image');
					if( leyer.length == 0 ){
						leyer = $('<div>', {class: 'leyer-show-image'}).append( $('<div>', {class: 'pic'}) );
						leyer.data( data );

						$( container ).append( leyer );
					}

					leyer.find( '.pic' ).css( size ).html( $('<img>', {class: 'img', src: data.src, width: size.width, height: size.height}) );
				    
				};
				image.src = data.src;
			}
		});

		$('body').delegate('.js-show-image', 'mouseleave', function(event) {
			closeShowImage();
		});
		$('body').delegate('.js-show-image', 'click', function(event) {
			closeShowImage();
		});

		$('body').delegate('.input-field :input', 'keyup', function(event) {
	
			$(this).toggleClass('dirty', $.trim( $(this).val() )!='' );
		});

	}), $(window).resize(function() {
		pageResize();
	}), $(window).scroll(function() {

		// $('.global-nav').toggleClass('_scrolling', $(this).scrollTop() > 0);
		
	}), $(window).on(function() {

		// console.log('on');
	});
});