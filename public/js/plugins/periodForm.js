// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var PeriodForm = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $( elem );
			self.$buslist = self.$elem.find('[role=buslist]');
			self.$buslistnav = self.$elem.find('[role=buslistnav]');
			self.$buslist.empty();
			self.buslistIndex = 0;

			self.options = $.extend({}, $.fn.periodForm.options, options);

			$.each(self.options.buslist, function(index, el) {
				self.buslistIndex++;
				self.addBuslist();
			});

			if( self.options.buslist.length==0 ){
				self.buslistIndex++;
				self.addBuslist();
			}

			// Event
			self.$elem.find('[data-buslist-action=add]').click(function() {
				self.buslistIndex++;
				self.addBuslist();
			});


			self.$elem.delegate('[data-buslist-action=focus]', 'click', function(event) {
				var item = self.$buslist.find('[data-buslists-key='+ $(this).attr('data-key') +']');

				self.$buslist.parent().scrollTop( item.position().top + self.$buslist.parent().scrollTop() );
				$(this).parent().addClass('active').siblings().removeClass('active');
			});

			self.$elem.delegate('[data-buslist-action=focus]', 'click', function(event) {
				var item = self.$buslist.find('[data-buslists-key='+ $(this).attr('data-key') +']');

				self.$buslist.parent().scrollTop( item.position().top + self.$buslist.parent().scrollTop() );
				$(this).parent().addClass('active').siblings().removeClass('active');
			});


			self.$elem.delegate('[data-buslist-action=toggle]', 'click', function(event) {
				
				var $box =  $(this).parent();
				var is = $box.hasClass('active');

				if( is ){
					$box.find('.content').slideUp(200, function () {
						 $box.removeClass('active');
					});
				}
				else{
					$box.find('.content').slideDown(200, function () {
						 $box.addClass('active');
					});
				}
			});
			
			self.$buslist.parent().scroll(function() {

				/*var top = $(this).scrollTop();
				var h = $(this).outerHeight();
				$.each(self.$elem.find('[data-buslists-key]'), function(index, el) {
					
				});*/
				
				
			});
		},

		addBuslist: function (data) {
			var self = this;
			var key = self.buslistIndex;

			var item = self.setBuslist(key, data||{})
			self.$buslist.append( item );

			self.options.busformOpt._nametop = 'buslists['+ key +']';

			$.each(item.find( '[data-name]' ), function(index, el) {
				

				$(this).attr('name', 'buslists['+ key +'][' + $(this).attr('data-name') + ']');

			});

			// [id=seat]
			
			Event.plugins(item);
			Event.setPlugin(item.find('[plugin=pricevalues]'), 'pricevalues', self.options.busformOpt);


			self.$buslistnav.append( '<li><a data-buslist-action="focus" data-key="'+ key +'">Bus '+ key +'</a></li>' );
			self.$elem.find('[data-buslist-action=focus][data-key='+key+']').trigger('click');
		},
		setBuslist: function (key, data) {
			var self = this;

			li = $('<li>', {class: 'active', 'data-buslists-key': key}).append(
				  $('<header>', {'data-buslist-action': 'toggle', class: 'clearfix'}).append( 
				  		  $('<h2>', {class: 'title'}).text('Bus '+key) 
				  		, '<i class="arrow icon-chevron-down"></i>'
				  )
				, $('<div>', {class: 'content'}).append( self.options.busform )
			);

			return li;
		},

	};

	$.fn.periodForm = function( options ) {
		return this.each(function() {
			var $this = Object.create( PeriodForm );
			$this.init( options, this );
			$.data( this, 'periodForm', $this );
		});
	};

	$.fn.periodForm.options = {
		buslist: []
	};
	
})( jQuery, window, document );