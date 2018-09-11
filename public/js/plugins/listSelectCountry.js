// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var ListSelectCountry = {
		init: function ( options, elem ) {
			var self = this;

			self.options =  $.extend( {}, $.fn.listSelectCountry.defaults, options );
			self.$elem = $(elem);

			self.$listbox = self.$elem.find('[role=listsbox]');

			if( self.options.data.length>0 ){

				$.each(self.options.data, function(i, obj) {
					self.add(obj );
				});
			}
			else{
				self.add( {} );
			}


			self.$elem.find('[role=add]').click(function() {

				var is = false, item;
				$.each(self.$listbox.find('>li'), function(index, el) {
					item = $(this).find(':input').first();

					if( $(this).find(':input').first().val()=='' ){
						is = true; return false;
					}
				});

				if( !is ){
					self.add( {}, true );
				}
				else{
					item.focus();
				}
			});

			self.$listbox.delegate('[data-action=remove]', 'click', function() {

				var length = self.$listbox.find('>li').length;

				if( length==1 ){
					$(this).closest('li').find(':input').val('');
					$(this).closest('li').find(':input').first().focus();

					$(this).closest('li').find('.has-data').removeClass('has-data');
				}
				else{
					$(this).closest('li').remove();
				}
				
				self.countryChange();
				self.verifyItem();
			});

			self.$listbox.delegate('[data-action=up], [data-action=down]', 'click', function() {

				var $el = $(this).closest('li'),
					action = $(this).data('action');

				if( action=='up' ){
					var prev = $el.prev();
					if( prev.length==1 ){ $el.after( prev ); self.verifyItem(); }
				}

				else{
					var next = $el.next();
					if( next.length==1 ){ $el.before( next ); self.verifyItem(); }
				}
				
			});
		},

		add: function ( data, autofocus ) {
			var self = this;

			var $item = self.setItme( data );
			self.$listbox.append( $item  );

			if( data.name ){
				self.countrySelected( $item.find('[data-name=city]'), self.sreachItem( data.name ) );
			}

			Event.setPlugin( $item.find('[data-name=country]'), 'inputselect', {
				container: $item.find('.country'),
				items: self.options.items,
				selected: self.options.selected,

				onSelected: function (data) {

					var city = $item.find('[data-name=city]'), items = [];

					self.countrySelected( city, self.sreachItem( $item.find('[data-name=country]').val() ) );
					city.focus();

				},
				onEvent: function () {
					self.countryChange();
				}

			} );

			if( $item.find(':input').first().val()=='' && autofocus ){
				$item.find(':input').first().focus();
			}

			self.verifyItem();
		},
		countryChange: function () {
			var self = this;

			self.options.selected = [];
			// var length = self.$listbox.find('[data-name=country]').length;

		
			$.each( self.$listbox.find('[data-name=country]'), function() {

				
				var txt = $.trim( $(this).val() );
				if( txt!='' ){
					self.options.selected.push(  txt );
				}
			});

			$.each( self.$listbox.find('[data-name=country]'), function() {

				var data = $(this).data();
				data.inputselect.options.selected = self.options.selected;
			});			
		},

		countrySelected: function ( $city, data ) {
			var self = this, items = data || [];

			if( $city.data('inputselect') ){
				var inputselect = $city.data('inputselect');
				inputselect.options.items = items;
			}
			else{

				Event.setPlugin( $city, 'inputselect', {
					items: items,
				});
			}
		},

		sreachItem: function ( key ) {
			var self = this, items = [];

			$.each(self.options.items, function(i, obj) {
				if( obj.name==key ){
					items = obj.items;
					return false;
				}
			});

			return items;
		},

		setItme: function ( data ) {
			var self = this;

			$li = $('<li>'+
                '<div class="country"><input data-name="country" name="country[]" class="inputtext" placeholder="Country" value="'+ (data.name ? data.name:'') +'"></div>'+
                '<div class="city between"><input data-name="city" name="city[]" class="inputtext"  placeholder="City"></div>'+
                '<div class="action between">'+
                	( self.options.multi
                		? '<button type="button" data-action="up"><i class="icon-arrow-up"></i></button>'+
                    	  '<button type="button" data-action="down"><i class="icon-arrow-down"></i></button>'
                		: ''
                	) +
                    
                    '<button type="button" data-action="remove" class="remove"><i class="icon-remove"></i></button>'+
                '</div>'+
            '</li>');

			$li.data( data );

            return $li;
		},



		verifyItem: function () {
			var self = this;

			self.$listbox.find('[data-action=up], [data-action=down]').removeClass('disabled');
			
			self.$listbox.find('[data-action=up]').first().addClass('disabled');
			self.$listbox.find('[data-action=down]').last().addClass('disabled');
			
		}
	}


	$.fn.listSelectCountry = function( options ) {
		return this.each(function() {
			var $this = Object.create( ListSelectCountry );
			$this.init( options, this );
			$.data( this, 'listSelectCountry', $this );
		});
	};

	$.fn.listSelectCountry.defaults = {
		data: [],
		items: [
			  {
				id: 1,
				name: 'Japan'
			}
			, {
				id: 2,
				name: 'Manmar'
			}
			, {
				id: 3,
				name: 'Vietnam'
			}, {
				id: 3,
				name: 'Maldives'
			}
		],

		selected: [],

		multi: true

	};
	
})( jQuery, window, document );