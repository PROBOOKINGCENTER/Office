// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var SelectFlag = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $(elem);
			self.options = $.extend( {}, $.fn.selectflag.options, options );

			self.$elem.wrap('<div class="select-flag-wrap"></div>');
			self.$wrap = self.$elem.parent();
			self.$flyout = $('<div>', { class: 'select-flag-flyout'});
			self.$trigger = $('<button>', { type: 'button', class: 'select-flag-toggle btn', text: ''});
			self.$elem.attr('type', 'hidden');

			self.$wrap.append( self.$trigger, self.$flyout );
			
			self.$tabs = $('<ul>', {class: 'flag-nav'});
			self.$listsbox = $('<div>', {class: 'flag-list'});

			var categoryList = {};
			$.each( self.options.data, function(i, data) {

				if( !categoryList[data.category_id] ){
					categoryList[data.category_id] = {
						id: data.category_id,
						name: data.category_name,
						countVal: 0,
					};
				}

				categoryList[data.category_id].countVal++;

				self.$listsbox.append( $('<div>', {class: 'flag-item', 'data-id': data.id, 'data-category': data.category_id}).append( $('<div>', {class: 'flag-item-inner'}).append(
					  $('<i>', {class: 'flag icoflag-'+ data.id.toLowerCase()})
					, $('<span>', {class: 'caption'}).text( data.name )
				) ) );
			});

			$.each( categoryList, function(i, data) {

				if( data.countVal>0 ){
					self.$tabs.append( $('<li>', {'data-id': data.id}).append( $('<a>', {text: data.name}) ) );
				}
			});

			self.$flyout.append( self.$tabs, self.$listsbox );



			// 
			if( self.$elem.val()!='' ){
				var el = self.$listsbox.find('[data-id='+ self.$elem.val() +']');
				if( el.length ){

					self.activeTab( self.$tabs.find('[data-id='+  el.data('category') +']').index() );
					self.active( self.$elem.val() );
					console.log( el.index(), self.$elem.val() );
				}
				else{
					self.activeTab();
				}
			}
			else{
				self.activeTab();
			}

			// Event
			self.$tabs.find('[data-id]').click(function(e) {
				self.activeTab( $(this).index() );
			});

			self.$listsbox.find('[data-id]').click(function() {
				self.active( $(this).data('id') );
				self.dropdown();
			});

			self.$trigger.on("click", function(){
				self.dropdown();
			});

			$(window).click(function() {
				
				if( $(event.target).parents('.select-flag-wrap').length === 0 && !$(event.target).hasClass('select-flag-toggle') && self.$wrap.hasClass('open') ){
					self.dropdown();
				}
			});
		},
		dropdown: function () {
			var self = this;

			// self.$flyout.slideToggle(1, function () {
			self.$wrap.toggleClass('open', !self.$wrap.hasClass('open') );
			// });

		},

		active: function( id ) {
			var self = this;

			console.log( id );
			var el = self.$listsbox.find('[data-id=' + id + ']');
			el.addClass('active').siblings().removeClass('active');
			self.$elem.val( id );

			self.$trigger.html( el.find('.flag').clone() )
		},
		activeTab: function (index) {
			var self = this;

			var el = self.$tabs.find('li').eq(index).length ? self.$tabs.find('li').eq(index): self.$tabs.find('li').first();

			el.addClass('active').siblings().removeClass('active');
			var category = el.data('id');

			self.$listsbox.find('.flag-item').removeClass('show');
			self.$listsbox.find('[data-category='+ category +']').addClass('show');
		}
	};

	$.fn.selectflag = function( options ) {
		return this.each(function() {
			var $this = Object.create( SelectFlag );
			$this.init( options, this );
			$.data( this, 'selectflag', $this );
		});
	};

	$.fn.selectflag.options = {
		data: [],
	};
	
})( jQuery, window, document );