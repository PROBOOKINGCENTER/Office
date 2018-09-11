// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var TableInsertPicture = {
		init: function ( options, elem ) {
			var self = this;
			self.$elem = $(elem);

			self.options = $.extend({}, $.fn.tableInsertPicture.options, options);

			self.$elem.addClass('table-insert-picture-wrap');

			self.$listsbox = $('<tbody>');
			self.$table = $('<table>').addClass('table-insert-picture').append( $('<tbody>').append($('<tr>').append(
				  $('<th>', {class: 'td-picture', text: 'Picture'})
				, $('<th>', {class: 'td-caption', text: 'Caption'})
				, $('<th>', {class: 'td-actions', text: 'Actions'})
			)), self.$listsbox );

			self.$elem.html( self.$table );
			if( self.options.multiple ){
				self.$elem.append( '<div class="tac mtm"><a class="btn" data-action="add"><i class="icon-plus"></i><span class="mls">Add Picture</span></a></div>' );
			}


			if( self.options.data ){

				if( self.options.data.length==0 ){
					self.add( {} );
				}
				else{
					$.each(self.options.data, function(index, el) {
						self.add( el );
					});
				}
			}
			else{
				self.add( {} );
			}


			// Event
			self.$listsbox.delegate('[data-action=upload]', 'change', function() {
				self.loadFile( $(this).closest('.media-dropzone'), this.files[0] );
			});

			self.$elem.find('[data-action=add]').click( function() {
				self.add( {} );
			});

			self.$listsbox.delegate('[data-action=remove]', 'click', function() {
				$(this).closest('tr').remove();

				if( self.$listsbox.find('>tr').lenght == 0 ){
					self.add( {} );
				}
			});

			self.$listsbox.delegate('[data-action=up]', 'click', function() {
				var $el = $(this).closest('tr');
				var prev = $el.prev();

				if( prev.length==1 ){ $el.after( prev ); self.verify(); }
			});

			self.$listsbox.delegate('[data-action=down]', 'click', function() {
				var $el = $(this).closest('tr');
				var next = $el.next();

				if( next.length==1 ){ $el.before( next ); self.verify(); }
			});
		},

		add: function ( data ) {
			var self = this;

			var $picture = $('<div>', {class: 'media-dropzone'}).append(
				  '<div class="dropzone-preview" role="preview"></div>'
				, '<div class="dropzone-progress"></div>'
				,  $('<div>', {class: 'dropzone-text-container'}).append(
					''
					// , '<div class="dropzone-icon"><i class="icon-picture"></i></div>'
					, '<div class="dropzone-title"><span class="dropzone-text">Upload photos</span></div>'	
				)
				, '<div class="media-upload"><input type="file" data-input="'+self.options.name[0]+'" accept="image/*" data-action="upload"></div>'
			);
			var $actions = $('<td>', {class: 'td-actions'});
			$actions.append(
				  ''
				, ( self.options.multiple ? '<span class="gbtn"><a class="btn" data-action="up"><i class="icon-arrow-up"></i></a></span>': '' )
				, ( self.options.multiple ? '<span class="gbtn"><a class="btn" data-action="down"><i class="icon-arrow-down"></i></a></span>': '' )
				, '<span class="gbtn"><a class="btn" data-action="remove"><i class="icon-remove"></i></a></span>'
			);

			$actions.append( $('<input>', {type: 'hidden', 'data-input': self.options.name[2], value: data.id || ''}) );			

			var $tr = $('<tr>', {class: 'row-content'}).append(
				  $('<td>', {class: 'td-picture'}).html( $picture )
				, $('<td>', {class: 'td-caption'}).html( $('<textarea>', {
					class: 'inputtext',
					'data-input': self.options.name[1],
					value: data.caption || ''
				}) )
				, $actions
			);
			
			self.$listsbox.append( $tr );


			if( data.src ){
				self.loadImageUrl( $picture, data.src);
			}

			self.verify();
		},

		loadFile: function ($el, file) {
			var self = this, reader = new FileReader();
			reader.onload = function (e) {
				self.loadImageUrl($el, e.target.result );
			}

			reader.readAsDataURL( file );
		},

		loadImageUrl: function ($el, src) {
			var self = this;
			
			var image = new Image();
			image.onload = function () {
			   	var img = this;

			   	var w = $el.width();
			   	var h = (w*this.height)/this.width;

			   	if( self.options.size ){
			   		
			   	}

				$el.css({
					width: w,
					height: h
				}).addClass('has-preview').find('[role=preview]').html( img );
			}
			
			/*image.onerror = function(e){

				item.find('.empty-message').text('');
				item.find('.textCaption').attr('disabled', true);
				item.removeClass('has-loading').addClass('has-error');
			};*/

			image.src = src;
		},

		verify: function () {
			var self = this;

			self.$listsbox.find('[data-action=up], [data-action=down]').removeClass('disabled').prop('disabled', false);
			self.$listsbox.find('.row-content').first().find('[data-action=up]').addClass('disabled').prop('disabled', true);


			var last = self.$listsbox.find('.row-content').last();
			last.find('[data-action=down]').addClass('disabled').prop('disabled', true);

			var is = last.hasClass('has-image');
			// self.$elem.find('[data-action=add]').toggleClass('disabled', !is).prop('disabled', !is); 

			$.each(self.$listsbox.find('.row-content'), function(index, el) {

				$.each( $(this).find('[data-input]'), function () {
					$(this).attr('name', $(this).data('input') + '['+ index +']' );
				});
			});
		}
	};

	$.fn.tableInsertPicture = function( options ) {
		return this.each(function() {
			var $this = Object.create( TableInsertPicture );
			$this.init( options, this );
			$.data( this, 'tableInsertPicture', $this );
		});
	};

	$.fn.tableInsertPicture.options = {
		multiple: false,
		// size: null,
		name: ['photo', 'caption', 'photo_id']
	};
	
})( jQuery, window, document );