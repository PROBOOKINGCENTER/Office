// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var TableBuslist = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $(elem);
			self.options = $.extend({}, $.fn.tablebuslist.options, options);
			self.$listsbox = self.$elem.find('[role=listsbox]');


			if( self.options.items.length==0 ){
				self.add();
			}
			else{
				$.each(self.options.items, function(i, obj) {
					self.add(obj);
				});
			}
		},

		add: function (data) {
			var self = this;

			var item = self.setItem(data||{});

			self.$listsbox.append( item );
		},

		setItem: function ( d, name ) {
			var self = this;

			var data = d || {};
			var name = name || self.options.name;

			if( self.options._nametop ){
				name = self.options._nametop + '['+ name +']';
			}

			data.value = 'undefined'==typeof data.value? '':data.value;
			$li = $( '<tr>' ).append(

				$('<td>', {class: 'td-name td-input'} ).append(

				  	$('<div>', {class: 'input-field'} ).append(
				  	  	  $('<input>', {type: 'text', name: name+'[name][]'} ).val( data.name ).toggleClass('dirty', data.name!='')
				  	  	, $('<label>').text( ( data.labelName ? data.labelName: 'Name' ) ) 
				  	)

				  	, $('<input>', {type: 'hidden', name: name+'[key][]'} ).val( data.key ?data.key: '' )
				  	// '<label class="checkbox"><input type="checkbox"><span>dd</span></label>'
				)
				, $('<td>', {class: 'td-value td-input'} ).append(
					$('<div>', {class: 'input-field'} ).append(
				  	  	  $('<input>', {type: 'text', name: name+'[value][]'} ).val( data.value ).toggleClass('dirty', data.value!='').attr('data-plugin', 'number_format')
				  	  	, $('<label>').text( ( data.labelValue ? data.labelValue: 'Seat' ) ) 
				  	)
				)
				, $('<td>', {class: 'td-action'} ).append(
					$('<div>', {class: ''} ).append(
				  	  	  $('<button>', {type: 'button', class: 'btn', 'data-action': 'up'} ).html( '<i class="icon-arrow-up"></i>' )
				  	  	, $('<button>', {type: 'button', class: 'btn', 'data-action': 'down'} ).html( '<i class="icon-arrow-down"></i>' )
				  	  	, $('<button>', {type: 'button', class: 'btn', 'data-action': 'add'} ).html( '<i class="icon-plus"></i>' )
				  	  	, $('<button>', {type: 'button', class: 'btn', 'data-action': 'remove'} ).html( '<i class="icon-remove"></i>' )
				  	)
				)
			);
			
			return $li;
		}
	};

	$.fn.tablebuslist = function( options ) {
		return this.each(function() {
			var $this = Object.create( TableBuslist );
			$this.init( options, this );
			$.data( this, 'tablebuslist', $this );
		});
	};

	$.fn.tablebuslist.options = {
		multiple: false,
		// size: null,
		items: [],
		selected: [],
		data: [],
		name: 'buslist'
	};
	
})( jQuery, window, document );