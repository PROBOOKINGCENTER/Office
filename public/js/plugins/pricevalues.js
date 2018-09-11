// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var PriceValues = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $( elem );
			self.options = $.extend({}, $.fn.pricevalues.options, options);

			self.$elem.empty();


			// set first default
			$.each(self.options.items, function(i, obj) {
				
				self.$elem.append( $('<thead>').html( $('<tr>').html( $('<td>', {class: 'td-head', colspan: 3}).text( obj.label ) ) ) );

				var $body = $('<tbody>', {class: 'listsbox'});
				self.$elem.append( $body );
				$body.data( obj );


				var is_action = obj.actions=='disabled' ? false: true;

				if( obj.items.length==0 ){

					self.addItem( $body, {
						is_action: is_action,
						labelValue: obj.labelValue || 'Price',
					}, obj.name );
				}
				else{
					$.each(obj.items, function(key, data) {
						data.is_action = is_action
						data.labelValue = obj.labelValue || 'Price';
						self.addItem( $body, data, obj.name );
					});
				}

			});

			
			// event 
			self.$elem.delegate('[data-action]', 'click', function(event) {
				var type = $(this).attr('data-action');
				$box = $(this).closest('tr');
				$listsbox = $(this).closest('.listsbox');

				if( type=='add' ){

					var item = self.setItem( {}, $listsbox.data('name') );
					$box.after( item );
					Event.plugins( item );
					item.find(':input[type=text]').first().focus();
				}

				else if(type=='remove'){

					if( $box.siblings().length==0 ){
						$box.find(':input[type=text]').val( '' );
						$box.find(':input[type=text]').first().focus();
					}
					else{
						$box.remove();
					}
				}
				else if(type=='up'){

					$box.prev().insertAfter($box);
				}
				else if(type=='down'){
					$box.next().insertBefore($box);
				}

			}); 
		},

		setItemDefault:function () {
				
		},

		/*verify: function () {
				
		},*/

		addItem: function ($box, data, name) {
			var self = this;

			$box.append( self.setItem( data, name ) );
			Event.plugins( $box );
		},

		setItem: function ( d, name ) {
			var self = this;

			var data = d || {};
			var name = name || 'input';

			if( self.options._nametop ){
				name = self.options._nametop + '['+ name +']';
			}

			var $action = data.is_action===false 
				? ''
			  	: $('<div>', {class: ''} ).append(
			  	  	  $('<button>', {type: 'button', class: 'btn', 'data-action': 'up'} ).html( '<i class="icon-arrow-up"></i>' )
			  	  	, $('<button>', {type: 'button', class: 'btn', 'data-action': 'down'} ).html( '<i class="icon-arrow-down"></i>' )
			  	  	, $('<button>', {type: 'button', class: 'btn', 'data-action': 'add'} ).html( '<i class="icon-plus"></i>' )
			  	  	, $('<button>', {type: 'button', class: 'btn', 'data-action': 'remove'} ).html( '<i class="icon-remove"></i>' )

			  	);


			var $name = $('<input>', {type: 'text', name: name+'[name][]'} ).val( data.name ).toggleClass('dirty', data.name!='');


			if( data.is_action===false  || data.key ){

				$name.prop('disabled', true).addClass('disabled');

			}

			data.value = 'undefined'==typeof data.value? '':data.value;
			$li = $( '<tr>' ).append(

				$('<td>', {class: 'td-name td-input'} ).append(

				  	$('<div>', {class: 'input-field'} ).append(
				  	  	  $name
				  	  	, $('<label>').text( ( data.labelName ? data.labelName: 'Name' ) ) 
				  	)

				  	, $('<input>', {type: 'hidden', name: name+'[key][]'} ).val( data.key ?data.key: '' )
				  	, ( data.key
				  		? $('<input>', {type: 'hidden', name: name+'[name][]'} ).val( data.name )
				  		: ''
				  	)
				  	// '<label class="checkbox"><input type="checkbox"><span>dd</span></label>'
				)
				, $('<td>', {class: 'td-value td-input'} ).append(
					$('<div>', {class: 'input-field'} ).append(
				  	  	  $('<input>', {type: 'text', name: name+'[value][]'} ).val( data.value ).toggleClass('dirty', data.value!='').attr('data-plugin', 'number_format')
				  	  	, $('<label>').text( ( data.labelValue ? data.labelValue: 'Value' ) ) 
				  	)
				)
				, $('<td>', {class: 'td-action'} ).append( $action )
			);
			
			return $li;
		}
	};

	$.fn.pricevalues = function( options ) {
		return this.each(function() {
			var $this = Object.create( PriceValues );
			$this.init( options, this );
			$.data( this, 'pricevalues', $this );
		});
	};

	$.fn.pricevalues.options = {
		items: []
	};
	
})( jQuery, window, document );