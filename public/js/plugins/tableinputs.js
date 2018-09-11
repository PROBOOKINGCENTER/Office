// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var TableInputs = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $(elem);
			self.options = $.extend({}, $.fn.tableinputs.options, options);
			self.$listsbox = self.$elem.find('[role=listsbox]');

			if( self.options.data ){
				if( self.options.data.length==0 ){
					self.add();
				}
				else{
					$.each(self.options.data, function(i, obj) {
						// console.log( obj );
						self.add( obj );
					});
				}
			}
			else{
				self.add();
			}

			// Event
			self.$elem.delegate(':input.js-input-number', 'keydown', function(e) {

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
		        // Ensure that it is a number and stop the keypress
		        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		            e.preventDefault();
		        }

			});

			// self.$elem.delegate(':input.js-input-number', 'keydown', function(e) {

			// .delegate(':input.js-input-number', 'keydown', function(e) {

			/*$(':input.js-input-number', self.$elem).bind("paste", function(e){
				console.log( 111 );
			});*/


			self.$elem.delegate(':input[data-name=price], :input[data-name=qty]', 'focus', function(e) {
				if( $(this).val()!='' ){
					$(this).select();
				}
			});
			self.$elem.delegate(':input[data-name=price], :input[data-name=qty]', 'keyup', function(e) {
				
				if( $(this).val()!='' ){
					$(this).val( PHP.number_format( $(this).val() ) );
				}
				self.verify();
			});
			self.$elem.delegate(':input[data-name=price], :input[data-name=qty]', 'blur', function(e) {

				if( $(this).val()!='' ){
					$(this).val( PHP.number_format( $(this).val() ) );
				}
				self.verify();
			});


			self.$elem.delegate('[data-item-action=minus], [data-item-action=plus]', 'click', function(e) {
				var type = $(this).attr('data-item-action'),
					input = $(this).closest('td').find(':input'),
					val = parseInt( input.val() ) || 0;

				// console.log( input.val(), val );

				if( type=='minus' ){
					val--;
				}
				else{
					val++;
				}

				if( val<0 ) val = 0;

				input.val( val );
				self.verify();
 			});


			self.$elem.delegate('[data-item-action=delete]', 'click', function(e) {

				var box = $(this).closest('tr');

				if( box.siblings().length==0 ){
					box.find(':input').val('');
					// box.find(':input').first().focus();
					box.find('[data-name=name]').parent().removeClass('has-data');
				}
				else{
					box.remove();
				}
				
				self.nameChange();
				self.verify();
 			});


			self.$elem.delegate('[data-item-action=add]', 'click', function(e) {
				self.add({}, $(this).closest('tr'));
 			});

 			/*self.$elem.delegate('[data-name=name]', 'click', function(e) {
 			});*/
		},

		add: function ( data, $box ) {
			var self = this;

			var $item = self.setItem( data || {} );
			if( $box ){
				$box.after( $item );
				$item.find(':input').first().focus();
			}
			else{
				self.$listsbox.append( $item );
			}


			Event.setPlugin( $item.find('[data-name=name]'), 'inputselect2', {
				items: self.options.items,
				selected: self.options.selected,

				onSelected: function ( a, data ) {

					$item.find(':input[data-name=price]').val( PHP.number_format(data.price) );
					self.verify();
					// console.log( a, data );
				},

				onEvent: function () {
					self.nameChange();
				}
			});
			
			self.verify();
		},
		nameChange: function () {
			var self = this;
			self.options.selected = [];

			$.each( self.$listsbox.find('[data-name=name]'), function() {
				var txt = $.trim( $(this).val() );

				if( txt!='' && $.inArray(txt, self.options.selected)<0 ){
					self.options.selected.push( txt );
				}
			});

			$.each( self.$listsbox.find('[data-name=name]'), function() {

				var s = $(this).data('inputselect2');
				s.options.selected = self.options.selected;
			});
		},

		setItem: function (data) {

			var $actions = $('<td>', {class: 'td-actions'}).append(

				  $('<td>', {class: 'btn', 'data-item-action': 'add', type: 'button'}).html( '<i class="icon-plus"></i>' )
				, $('<td>', {class: 'btn', 'data-item-action': 'delete', type: 'button'}).html( '<i class="icon-remove"></i>' )

				, ( data.id 
					? $('<input>', {type: 'hidden', 'data-id': 'id', name: 'extra[id][]', class: 'inputtext', autocomplete: 'off'}).val( data.id )
					: '' 
				) 
				, ( data.aid 
					? $('<input>', {type: 'hidden', 'data-aid': 'id', name: 'extra[aid][]', class: 'inputtext', autocomplete: 'off'}).val( data.aid )
					: '' 
				) 
			);

			var $tr = $('<tr>', {class: 'item'});
			var number = $('<div>', {class: 'control-group-number'}).append(


				  '<div class="gbtn"><button type="button" class="btn btn-no-padding" data-item-action="minus" data-type="minus"><i class="icon-minus"></i></button></div>'

				, $('<div>', {class: 'number'}).append( $('<input>', {type: 'text', 'data-name': 'qty', name: 'extra[qty][]', class: 'inputtext js-input-number', autocomplete: 'off'}).val( data.qty || data.value || '' ) ) 

				, '<div class="gbtn r"><button type="button" class="btn btn-no-padding" data-item-action="plus" data-type="plus"><i class="icon-plus"></i></button></div>'
			);

			// '<div><input type="text" name="extra[name][]" class="inputtext"></div>'

			$tr.append(

				  $('<td>', {class: 'td-no'})
				, $('<td>', {class: 'td-name'}).html( $('<input>', {type: 'text', 'data-name': 'name', name: 'extra[name][]', class: 'inputtext', autocomplete: 'off'}).val( data.name ) )
				, $('<td>', {class: 'td-price'}).html( $('<input>', {type: 'text', 'data-name': 'price', name: 'extra[price][]', class: 'inputtext js-input-number', autocomplete: 'off'}).val( data.price ) )
				, $('<td>', {class: 'td-qty'}).html( number )
				, $('<td>', {class: 'td-sum'}).html( '<input data-item-ref="sum" type="text" class="inputtext" disabled>' )
				, $actions
			);

			return $tr;
		},

		verify: function () {
			var self = this;

			var items = [], eq = 0, total = 0;
			var numberPattern = /[0-9]+/g;

			$.each(self.$listsbox.find('tr'), function(index, el) {
				eq++;
				$(this).find('.td-no').text( eq + '.' );

				var name = $.trim($(this).find('[data-name=name]').val());
				var qty = $(this).find('[data-name=qty]').val();
				if( qty=='' && (name!='' || $.trim($(this).find('[data-name=price]').val())!='') ){
					$(this).find('[data-name=qty]').val( 1 );
				}

				qty = parseInt( self.get_numbers($(this).find('[data-name=qty]').val()) || 0 );
				var price = parseInt(self.get_numbers($(this).find('[data-name=price]').val()) || 0);

				var amount = price * qty;
				$(this).find('[data-item-ref=sum]').val(amount==0 ? '-': PHP.number_format( amount ));
				total += amount;

				if( name!='' ){
					items.push({
						name: name,
						qty: qty,
						price: price,
						amount: amount,
					});
				}
			});

			self.$elem.find('[data-ref=total]').text( total==0 ? '-': PHP.number_format( total ) );

			if( typeof self.options.onChange === 'function' ){
				self.options.onChange( self, items, total );
			}
		},

		get_numbers: function (input) {
			var number = input.match(/[0-9]+/g);
			if( number ){
				number = number.join([]);
			}
			return number;
		}
	};

	$.fn.tableinputs = function( options ) {
		return this.each(function() {
			var $this = Object.create( TableInputs );
			$this.init( options, this );
			$.data( this, 'tableinputs', $this );
		});
	};

	$.fn.tableinputs.options = {
		multiple: false,
		// size: null,
		items: [],
		selected: [],
		data: []
	};
	
})( jQuery, window, document );