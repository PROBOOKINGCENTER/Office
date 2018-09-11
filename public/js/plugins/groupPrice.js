// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var GroupPrice = {
		init: function (options, elem) {
			var self = this;

			self.$elem = $(elem);
			self.$elem.addClass('table-group-price');
			self.$listsbox = self.$elem.find( '[rel=listsbox]' );

			self.options = $.extend({}, $.fn.groupPrice.options, options);

			if( self.options.data.length > 0 ){
				$.each( self.options.data, function(i, obj) {
					self.add( obj );
				});
			}
			else{
				self.add( {} );
			}

			// 
			self.$listsbox.delegate('[data-action=add]', 'click', function(event) {
				self.add( {} );
				self.$listsbox.find('tr').last().find(':input').first().focus();
			});

			self.$listsbox.delegate('[data-action=del]', 'click', function(event) {
				$(this).closest('tr').remove();

				if( self.$listsbox.find('tr').length==0 ){
					self.add( {} );
				}
			});
		},
		add: function ( data ) {
			var self = this;

			var $min = $('<input>', {class: 'inputtext', 'data-name':'min', type: 'number', name: self.options.names[0]}).val( data.min || '' );

			var $tr = $('<tr>').append(
				  $('<td>', {class: 'td-min'}).html( $min )
				, $('<td>').text( '-' )
				, $('<td>', {class: 'td-max'}).html( $('<input>', {class: 'inputtext', 'data-name':'max', type: 'number', name: self.options.names[1]}).val( data.max || '' ) )
				, $('<td>').text( '=' )
				, $('<td>', {class: 'td-price'}).html( $('<input>', {class: 'inputtext', 'data-name':'price', type: 'number', name: self.options.names[2]}).val( data.price || '' ) )
				, $('<td>', {class: 'td-actions'}).append( 

					  $('<button>', {class: 'btn-action add', type: 'button', 'data-action': 'add'}).html( '<i class="icon-plus"></i>' )
					, $('<button>', {class: 'btn-action remove', type: 'button', 'data-action': 'del'}).html( '<i class="icon-remove"></i>' )
				)
			);

			self.$listsbox.append( $tr );
		}
	}
	$.fn.groupPrice = function( options ) {
		return this.each(function() {
			var $this = Object.create( GroupPrice );
			$this.init( options, this );
			$.data( this, 'groupPrice', $this );
		});
	};
	$.fn.groupPrice.options = {
		multiple: false,
		names: ['group_price[min][]', 'group_price[max][]', 'group_price[price][]'],
		data: []
	}
	
})( jQuery, window, document );