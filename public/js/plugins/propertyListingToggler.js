// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var ModelListing = {
		init: function ( options, elem ) {
			var self = this;

			
			self.setModel();

			self.$model.addClass('active');

			$( window ).resize(function () {
				self.resizeModel();
			});
			self.resizeModel();
			
			self.show();


			self.$model.find('[data-action=close]').click(function() {
				self.hide();
			});
		},

		loadModel: function ( data ) {
			var self = this;
		},

		resizeModel: function () {
			var self = this;


			self.$body.css({
				height: $(window).height() - ( 72 + self.$header.outerHeight() + self.$summary.outerHeight() + self.$buttons.outerHeight() )
			});
		},

		setModel: function ( data ) {
			var self = this;

			self.$model = $('<div>', {class: 'propertyListingModel'});
			self.$container = $('<div>', {class: 'model-container'});

			/* header */
			self.$header = $('<div>', {class: 'model-title clearfix'});

			self.$header.html( '' +
				// '<button type="button" data-action="close" class="model-title-close"></button>' +
				'<table class="propertyListing-tableSummary">'+
					'<tr>'+
						'<td>'+
							'<div class="propertyListing-status">'+
								'<span>#21393</span>'+
								'<div style="font-size: 12px;font-weight: normal;">12 Items</div>'+
							'</div>'+
							'<div class="propertyListing-title">'+
								'<h2>Christophe Adins</h2>'+
								'<div class="desc"><span>42 minutes - Chong</span></div>'+
							'</div>'+
						'</td>'+

						'<td style="width: 150px;white-space: nowrap;padding-right: 15px">'+
							'<label class="label"><i class="icon-file-word-o"></i> Export Word:</label>'+
							'<div class="group-btn">'+
								'<a class="btn btn-blue"><i class="icon-file-text-o"></i><span class="mls">Staff</span></a><a class="btn btn-red"><i class="icon-user-circle"></i><span class="mls">Client</span></a>'+
							'</div>'+
						'</td>'+

						'<td class="td-link-sharing">'+
							'<label class="label"><i class="icon-link"></i> Link sharing on website: </label>'+
							'<table class="link-sharing-table">'+
								'<tr>'+
									'<td><input type="text" name="" value="..." class="inputtext link-sharing-input"></td>'+
									'<td><button type="button" class="btn link-sharing-btn">Copy link</button></td>'+
								'</tr>'+
							'</table>'+
						'</td>'+
					'</tr>'+
				'</table>'
			);


			/* summary */
			self.$summary = $('<div>', {class: 'model-summary clearfix'}).html( '<div class="lfloat">'+
				'<label class="label"><i class="icon-user-circle-o"></i> Client:</label> <span>Mr.Christophe Adins (Show: 2)</span>'+
				'<label class="label mll"><i class="icon-user"></i> Consultant:</label> <span>Nita</span>'+
				'<label class="label mll"><i class="icon-clock-o"></i> Show Date:</label> <span>11/05/2018 10:10</span>'+
			'</div>' );


			self.$body = $('<div>', {class: 'model-body'});

			self.$buttons = $('<div>', {class: 'model-buttons clearfix'}).append(

				'<div class="rfloat"><button type="button" data-action="close" class="btn btn-blue"><span>Done</span></button></div>'
			);


			self.$model.html( self.$container.append( self.$header, self.$summary, self.$body, self.$buttons ) );
			$('body').append( self.$model );
		},

		show: function () {
			var self = this;

			setTimeout(function () {
				self.$model.addClass('show');
			}, 1);
		},

		hide: function (argument) {
			var self = this;

			self.$model.removeClass('show');

			setTimeout(function () {
				self.$model.removeClass('active');


				setTimeout(function () {
					self.$model.remove();
				}, 1000);

			}, 300);

			
		}


	};

	var PropertyListingToggler = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $(elem);
			self.setElem();

			self.options = $.extend( {}, $.fn.propertyListingToggler.options, options );
			self.is_loading = false;
			self.getData = {
				limit: 50,
				pager: 1,
				q: '',
				more: false,
			}

			self.resize();
			$( window ).resize(function () {
				self.resize();
			});


			// Event
			self.$form.submit(function(e) {
				e.preventDefault();
				self.search();
			});

			self.focus = false;

			// self.$inputSearch.

			self.$menu.parent().scroll(function() {
	
				var val = (self.$menu.outerHeight() * 3) /100;
				var pos = $(this).scrollTop() + $(this).outerHeight();
				var hei = self.$menu.outerHeight();
				var lev = hei - pos;
				if( lev < val && !self.is_loading && self.getData.more ){

					self.getData.pager ++;
					self.load();
				}
			});

			self.$inputSearch.keyup(function(e) {
				
				var val = $.trim( $(this).val() );

				if( val=='' && self.getData.q!=val ){
					self.getData.q = val;
					self.getData.pager = 1;

					self.load( 800 );
				}

				e.stopPropagation();
			}).click(function(e) {
				e.stopPropagation();
			}).focus(function() {
				self.focus = true;

			}).blur(function() {
				self.focus = false;
			});

			$(window).click(function( event ) {
				
				if ( $(event.target).parents('.propertyListingToggler').length && !self.$elem.hasClass('openToggler') ) {
					
					self.$menu.empty();
					self.load();
					self.open();

					self.$inputSearch.focus();
				}
				else if( !self.focus ){
					self.close();
				}

			});


			self.$menu.delegate('[data-listing-action=click]', 'click', function(event) {
				var $this = Object.create( ModelListing );
				$this.init( $(this).closest('[data-listing-id]').data(), this );
				event.preventDefault();
			});
			
		},

		setElem: function () {
			var self = this;
			
			self.$flyout = self.$elem.find('#propertyListingFlyout');
			self.$menu = self.$elem.find('[role=menu]');
			self.$menu.addClass('propertyListingList')
			self.$form = self.$elem.find('form');
			self.$listsbox = self.$elem.find('[role=listsbox]');
			self.$inputSearch = self.$elem.find('#search-query');
			self.$toggle = self.$elem.find('[role=toggle]');
		},

		resize: function () {
			var self = this;
			
			var h = $(window).height() - 150;

			self.$menu.parent().css({
				maxHeight: h > 550 ? 550 : h
			});
		},

		load: function ( length ) {
			var self = this;

			self.is_loading = true;

			if( self.getData.pager==1 ){
				self.$menu.empty();
			}

			self.$menu.parent().addClass('has-loading').removeClass('has-empty').removeClass('has-error');

			setTimeout(function () {
				
				self.fetch().done(function( res ) {
			

					self.getData = $.extend( {}, self.getData, res.options );

					if( res.error ){
						self.$menu.parent().addClass('has-error');
						return false;
					}

					if( res.items ){
						$.each( res.items, function(i, obj) {
							self.display( obj );
						});
					}

					if( self.getData.pager==1 && parseInt(res.total)==0 ){
						self.$menu.parent().addClass('has-empty');
					}
					
					self.$menu.parent().toggleClass('has-more', self.getData.more);
				});

			}, length || 1);
		},

		fetch: function () {
			var self = this;

			return $.ajax({
				url: app.getUri( 'property/listingList' ),
				type: 'GET',
				dataType: 'json',
				data: self.getData,
			})
			.fail(function() {
				self.$menu.parent().addClass('has-error');
				console.log("error");
			})
			.always(function() {
				self.is_loading = false;
				self.$menu.parent().removeClass('has-loading');
				console.log("complete");
			});
		},

		display: function (data) {
			var self = this;

			self.$menu.append( self.setItem( data ) );
		},

		setItem: function (data) {
			var self = this;

			var $li = $('<li>', {class: 'menu-item', 'data-listing-id': data.id});
			var $anchor = $('<div>', {class: 'anchor clearfix'});
			var link =  '/property/listing/' + data.id;

			var $avatar = $('<a>', {class: 'code lfloat mrm', 'data-listing-action': 'click', href: link}).append(
				  $('<span>', {class: 'fwb', text: '#'+data.code})
				, $('<div>', {class: 'fss', text: '19 Items'})
			);

			/* actions */
			var $actions = $('<div>', {class: 'anchor-actions rfloat mlm group-btn'});
			$actions.append( 
				  $('<a>', {class: 'btn btn-small', text: 'Staff', href: 'https://www.thaipropertyguide.com/datacenter.php/exportData/propertyListing/' + data.id + '?exporttype=staff' })
				, $('<a>', {class: 'btn btn-small', text: 'Client', href: 'https://www.thaipropertyguide.com/datacenter.php/exportData/propertyListing/' + data.id + '?exporttype=client'})
			);


			var $date = $('<span>', {class: 'date fcg fsm'});

			$date.append( $('<i>', {class: 'date-i icon-clock-o'}), $('<span>', {class: 'date-str mls'}).html( data.created_str ) );
			if( data.user_name ){
				$date.append( ' - ', $('<span>', {class: ''}).html( data.user_name ) );
			}

			/* content */
			var $content = $('<div>', {class: 'content'});
			$content.append(
				  $('<div>', {class: 'spacer'})
				, $('<div>', {class: 'massages'}).append(

					  $('<a>', {class: 'fullname', text: data.name, href: link, 'data-listing-action': 'click'})
					, $('<div>', {class: 'dics'}).append( $date )
				)
			);

			$anchor.append( $avatar, $actions, $content );
			$li.data( data );

			return $li.append( $anchor );
		},

		open: function () {
			var self = this;

			self.resize();
			self.$elem.addClass('openToggler');
		},
		close: function () {
			var self = this;

			self.$elem.removeClass('openToggler');
		},

		search: function () {
			var self = this;

			var val = self.$inputSearch.val();
			if( self.getData.q!=val ){
				self.getData.q = val;
				self.getData.pager = 1;

				self.load( 800 );

			}
		},

	};

	$.fn.propertyListingToggler = function( options ) {
		return this.each(function() {
			var $this = Object.create( PropertyListingToggler );
			$this.init( options, this );
			$.data( this, 'main', $this );
		});
	};

	$.fn.propertyListingToggler.options = {};
	
})( jQuery, window, document );