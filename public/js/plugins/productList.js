/* v.2.2 */

// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var ProductList = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $(elem);
			self.settings =  $.extend( {}, $.fn.productList.settings, options );

			self.url = self.settings.url;
			self.period_url = self.settings.period_url;

			// set Data
			self.options = {
				limit: 10,
				page: 1,
				q: '',

				more: false,
				status: 1,

				has_period: true,
			};
			self.has_loading = false;

			self.setElem();
			self.setData();


			self.refresh(1);

			self.Event();
		},
		setData: function () {
			var self = this;

			self.loadSeries();
		},

		// set Elem
		setElem: function () {
			var self = this;

			self.$el = {};
			$.each(self.$elem.find('[role]') ,function(index, el) {
				self.$el[ $(this).attr('role') ] = $(this); 
			});	


			/*self.$el.filter = {};
			$.each(self.$elem.find('[data-filter]') ,function(index, el) {
				self.$el.filter[ $(this).attr('data-filter') ] = $(this); 
			});*/
		},


		refresh: function (ready, callback) {
			var self = this;

			self.$el.listsbox.parent()
				.removeClass('has-empty')
				.removeClass('has-error')
				.removeClass('has-more')
				.addClass('has-loading');

			if( self.options.page==1 ){
				self.$el.listsbox.empty();
			}

			clearTimeout( self.is_loading );

			self.is_loading = setTimeout(function () {

				self.fetch().done(function( results ) {

					if( results.error ){
						self.$el.listsbox.parent().addClass('has-error');
						return false;
					}

					self.options = $.extend( {}, self.options, results.options );

					self.$el.listsbox.parent().toggleClass('has-more', self.options.more);

					if( parseInt(self.options.page)==1 && parseInt(results.total)==0 ) {
						self.$el.listsbox.parent().addClass('has-empty');
						return false;
					}

					self.buildFrag( results.items );
				});

			}, ready || 1);
		},

		fetch: function () {
			var self = this;

			return $.ajax({
				url: self.url,
				data: self.options,
				dataType: 'json'
			}).always(function () {

				self.$el.listsbox.parent().removeClass('has-loading');

				if( self.is_search ){
					self.$elem.find('.search-input').attr('disabled', false);
					self.$elem.find('.search-input').focus();

					self.is_search = false;
				}

				self.$elem.removeClass('off');
				 
			}).fail(function() { 
				self.$el.listsbox.parent().addClass('has-error');
			});
		},

		buildFrag: function ( items ) {
			var self = this;

			$.each(items, function(i, obj) {

				self.display( self.setItem( obj ) );
				self.display( $('<tbody>').append( $('<tr>', {class: 'divider'}).append( $('<td>', {colspan: self.settings.keys.length}) ) ) );
			});
		},

		setItem: function ( data ) {
			var self = this;

			// console.log( data );
			tbody = $('<tbody>', {'serie-id': data.id});

			// header 
			tbody.append( $('<tr>', {class: 'tr-header'}).append( $('<td>', {colspan: self.settings.keys.length, class: 'td-header'}).append(

				$('<div>', {class: 'clearfix'}).append( 

					$('<div>', {class: 'lfloat'}).append( 
						$('<span>', {class: 'g-status'}).append( 
						  	  (data.country_code? $('<i>', {class: 'flag icoflag-'+ data.country_code.toLowerCase() }): '')
							, $('<span>', {class: 'tour-item__status'}).text( data.code )
						)
						, $('<span>', {class: 'fwb'}).text( data.name )
					)
					, $('<div>', {class: 'rfloat'}).append( 
						$('<button>', {type: 'button', class: 'btn', 'data-serie-control':"re-period"}).append( '<i class="icon-refresh"></i>' )
					)
				)

			) ) );


			// title 
			var title = $('<tr>');
			$.each(self.settings.keys, function(i, obj) {
				title.append( $('<th>').addClass( obj.class ).html( obj.text ) );
			});
			tbody.append( title );

			for (var i=1; i <= data.period_total; i++) {
				tbody.append( self.setElemPeriod(i) );
			}


			if( !data.periodList ){
				self.loadPeriod( data.id, tbody );
			}
			else{

				self.buildFragPeriod(tbody, data.periodList);
			}
			
			tbody.data( data );
			return tbody;
		},
		setElemPeriod: function (eq) {
			var self = this, tr = $('<tr>', {class: 'tr-item'});

			$.each(self.settings.keys, function(i, obj) {

				var txt = obj.key=='seq' 
					? eq
					: $('<div>', {/*class: 'loadber_elem',*/ 'data-ref': obj.key}).html('&nbsp;');

				var td = $('<td>').addClass( obj.class ).html( txt );

				if( obj.multiple ){
					td.addClass('multiple')
				}

				// tr.data( obj );
				tr.append( td );
			});

			return tr;
		},
		display: function (item) {
			var self = this;

			self.$el.listsbox.append( item );
			Event.plugins( item );
		},
		loadPeriod: function (id, elem, delay) {
			var self = this;

			setTimeout(function () {

				var dataPost = {
					series: id, 
					sort: 'per_date_start', 
					dir: 'asc', 
					state: [0,1,2],
					with_booking: true
				}

				if( self.options.startDate && self.options.endDate ){
					dataPost.startDate = self.options.startDate;
					dataPost.endDate = self.options.endDate;
				}

				$.ajax({
					url: self.period_url,
					type: 'GET',
					dataType: 'json',
					data: dataPost, // 
				})
				.done(function(res) {

					self.buildFragPeriod(elem, res);

					/*if( elem.find('tr.off').length>0 ){
						elem.find('tr.off').remove();
					}*/
				})
				.fail(function() {
					// console.log("error");
				})
				.always(function() {
					// console.log("complete");
				});
			
			}, delay || 1);
		},
		buildFragPeriod: function (elem, res) {
			var self = this;

			elem.find('.tr-item').remove();

			var eq = 0;
			$.each(res, function(i, period) {
				eq++; tr = self.setElemPeriod( eq );
				elem.append( tr );

				// console.log( period );
				tr.find('td').not('.multiple').attr('rowspan', period.busList.length);

				var busSeq = 0;
				$.each( period.busList, function(index, bus) {

					busSeq++;

					bus.period = period.id;

					period.bus_str = busSeq;
					period.seat_str = bus.seat;

					if( index>0 ){

						_tr = self.setElemPeriod();
						_tr.find('td').not('.multiple').remove();

						tr.after( _tr );
						tr = _tr;
					}

					self.displayPeriod( tr, period );
					var discount = parseInt( period.discount ) || 0;
					var price = parseInt( period.price ) || 0;

					tr.find('[data-ref=price_str]').parent().toggleClass('is-discount', discount > 0);

					if( discount > 0 ){

						tr.find('[data-ref=price_str]').empty().addClass('has-discount').append(
						 	  $('<span>', {class: 'price x'}).text( PHP.number_format(price) )
							, $('<span>', {class: 'price'}).text( PHP.number_format(price-discount) )
						);


					}


					tr.find('.multiple').attr({'data-bus-no': busSeq});

					self.updateBooking( tr, busSeq, bus );
				});
			});

		},

		displayPeriod: function ( $el, data) {
			
			$el.attr({'data-period-id': data.id, 'data-period-type': 'serie-item' });

			$.each($el.find('[data-ref]'), function() {
				var $elem = $(this), 
					name = $(this).attr('data-ref');

				if(  typeof data[name] !== 'undefined' ){
					var type = typeof data[name];
					// console.log( type );

					if( name=='status_arr' ){
						$elem.removeClass('loadber_elem').addClass('ui-status').css(
							'background-color', data.status_arr.color).text( data.status_arr.name );
					}
					else if( type==='string' ){
						$elem.removeClass('loadber_elem').html( data[name] );
					}
					else if(type==='number'){
						$elem.removeClass('loadber_elem').text( PHP.number_format( data[name] ) );
					}

					// console.log( typeof data[name] );					
				}
			});	
		},
		setActionPeriod: function (data) {
			var div = $('<div>', {class: 'whitespace group-btn'}),
				seat = parseInt(data.seat);
				
			if( (seat-parseInt(data.fullpayment)) > 0 ){

				if( parseInt(data.bookingCountVal) >= seat ){
					div.append( $('<button>', {type: 'button', class: 'btn btn-yellow btn-small', 'data-action':'booking'}).append( $('<i>', {class: 'icon-file-text-o mrs'}), $('<span>', {text: 'W/L'}) ).data( data ) );
				}
				else{
					div.append( $('<button>', {type: 'button', class: 'btn btn-blue btn-small', 'data-action':'booking'}).append( $('<i>', {class: 'icon-plus-circle mrs'}), $('<span>', {text: 'จอง'}) ).data( data ) );
				}
			}
			else{
				div.append( $('<button>', {type: 'button', class: 'btn btn-red btn-small'}).append( $('<i>', {class: 'icon-minus-circle mrs'}), $('<span>', {text: 'เต็ม'}) ) );
			}
			
			// div.append( $('<button>', {type: 'button', class: 'btn'}).append( $('<i>', {class: 'icon-ellipsis-v'}) ) );
			div.append( $('<a>', {type: 'button', class: 'btn btn-small', 'href': app.getUri('tour/period/' + data.period + '/' + data.no), target: '_blank'}).append( '<i class="icon-info"></i>' ) );

			return div;
		},

		updateBooking: function ( $el, busno, data) {
			var self = this;
			var options = data.options;
			var booking = data.booking || {
				fullpayment: [],
				booking: []
			};

			booking.seat = parseInt(data.seat);
			booking.seat_balance = data.seat_balance;
			booking.bookingVal = booking.booking.length;
			booking.fullpaymentVal = booking.fullpayment.length;

			var bookingStatus = booking.status || {};
			// var fullpayment = bookingStatus[35] || {};

			var opt = [
				  { key: 'seat', value: data.seat? PHP.number_format(data.seat): '-' }
				, { key: 'seat_balance', value: data.seat_balance? PHP.number_format(data.seat_balance): '-' }
				, { key: 'bookingCountVal', value: booking.total? PHP.number_format(booking.total): '-' }
				, { key: 'fullpayment', value: booking.fullpaymentVal? PHP.number_format(booking.fullpaymentVal): '-' }
				, { key: 'bus_status_str', value: self.setElemStatus(data.status_arr) }
				, { key: 'booking_str', value: self.setElemBooking(booking.booking || {} ) }
				, { key: 'waitlist_str', value: self.setElemBooking(booking.wishlist || {} ) }
				, { key: 'action_str', value: self.setActionBooking(booking) }
			];

			// var $el = self.$elem.find('[data-period-id='+ period +']');
			$.each(opt, function(i, obj) {
				$el.find('[data-bus-no='+ busno +'] [data-ref='+ obj.key +']').removeClass('loadber_elem').html( obj.value );
			});

		},
		setElemStatus: function (data) {
			return $('<span>', {class: 'ui-status'}).css('background-color', data.color).text( data.name );
		},


		setBooking: function (items) {
		},

		setElemBooking: function (items) {
			var self = this;

			var bookingList = $('<div>', {class: 'booking-list'});

			$.each(items, function(j, book) {
				
				if( j > 0 ){
					bookingList.append( $('<span>', {class: 'separator'}).text( ' | ' ) );
				}

				var h1 = $('<div>').append( 
					''
					, ( parseInt(book.company_guarantee)==1 ? '<i class="icon-thumbs-up mrs"></i>' : '' )
					,  $('<span>', {class: 'name',text: book.name})
					, ' '
					, $('<span>', {text: book.countVal }) 
					, ' '
					, $('<span>', {text: '('+ book.company_name +')' })
				);


				bookingList.append( $('<a>').css({'color': book.status_arr.color }).append(
					h1
				) );
			});

			return bookingList;
		},

		setActionBooking: function ( data ) {
			var div = $('<div>', {class: 'whitespace group-btn'}),
				seat = data.seat;
				
			if( seat-data.fullpaymentVal > 0 ){

				if( data.bookingVal >= seat ){
					div.append( $('<button>', {type: 'button', class: 'btn btn-yellow btn-small', 'data-action':'booking'}).append( $('<i>', {class: 'icon-file-text-o mrs'}), $('<span>', {text: 'W/L'}) ).data( data ) );
				}
				else{
					div.append( $('<button>', {type: 'button', class: 'btn btn-blue btn-small', 'data-action':'booking'}).append( $('<i>', {class: 'icon-plus-circle mrs'}), $('<span>', {text: 'จอง'}) ).data( data ) );
				}
			}
			else{
				div.append( $('<button>', {type: 'button', class: 'btn btn-red btn-small'}).append( $('<i>', {class: 'icon-minus-circle mrs'}), $('<span>', {text: 'เต็ม'}) ) );
			}
			
			// div.append( $('<button>', {type: 'button', class: 'btn'}).append( $('<i>', {class: 'icon-ellipsis-v'}) ) );
			div.append( $('<a>', {type: 'button', class: 'btn btn-small', 'href': app.getUri('product/period/' + data.options.period + '/' + data.options.bus), target: '_blank'}).append( '<i class="icon-info"></i>' ) );

			return div;
		},

		Event: function () {
			var self = this;

			self.$elem.find('[data-action=tryagain]').click(function() {
				self.refresh(200);
			});

			self.$elem.find('[data-action=more]').click(function() {
				self.loadMore(500);
			});

			self.$elem.find('[data-action=scrolltop]').click(function() {
				$('body, html').animate({scrollTop: 0 }, 'fast' );
			});

			
			self.$elem.delegate('[data-serie-control=re-period]', 'click', function(event) {
				var box = $(this).closest('[serie-id]');
				var data = box.data();

				box.find('[data-ref]').addClass('loadber_elem').html('&nbsp;');
				self.loadPeriod( data.id, box, 200 );
			});


			self.$el.filter.find('[data-filter=refresh]').click(function() {

				if( !self.$el.listsbox.hasClass('has-loading') ){
					self.options.page = 1;
					self.options.seq = 0;
					$('body, html').animate({scrollTop: 0},500);

					self.refresh(200);
				}
			});

			self.$el.filter.find('[data-filter=search]').submit(function(event) {
				event.preventDefault();

				var val = $.trim( $(this).find(':input').val() );

				if( val!=self.options.q ){
					self.options.q = val;
					self.options.page = 1;
					self.options.seq = 0;
					$('body, html').animate({scrollTop: 0},500);

					self.refresh( 200 );
				}
			});

			self.$el.filter.find('[data-filter=search]').find(':input').keyup(function(e) {
				var val = $.trim( $(this).val() );

				if( val!=self.options.q && val=='' ){
					self.options.q = val;
					self.options.page = 1;
					self.options.seq = 0;
					$('body, html').animate({scrollTop: 0},500);

					self.refresh( 200 );
				}
			});


			self.$el.filter.find('[data-filter=selector]').change(function() {
					
				self.options.page = 1;
				self.options.seq = 0;
				$('body, html').animate({scrollTop: 0},500);

				var name = $(this).attr('name'),
					val = $.trim($(this).val());

				self.options[name] = val

				if( name=='country' ){
					self.loadCity(val);
				}

				self.refresh( 200 );
			});
			

			self.$el.filter.find('[data-filter=daterange]').caleran({
				format: 'DD/MM/YYYY',
				// rangeOrientation: "vertical",
				showButtons: true,
				// inline: true,
				startOnMonday: true,
				startEmpty: true,
				showFooter: false,

				showOn: "top",
    			arrowOn: "right",

				onafterselect: function (caleran, startDate, endDate) {
					
					caleran.$elem.parent().addClass('has-date');

					self.options.page = 1;
					self.options.seq = 0;
					$('body, html').animate({scrollTop: 0},500);

					self.options.startDate = PHP.dateJStoPHP(new Date(startDate));
					self.options.endDate = PHP.dateJStoPHP(new Date(endDate));

					self.refresh();
				}
			});

			self.$el.filter.find('[data-action=cleardate]').click(function() {
				var input = self.$elem.find('.daterange-input');

				data = input.data('caleran');
				data.clearInput();

				input.val('');
				$(this).parent().removeClass('has-date');

				delete self.options.startDate;
				delete self.options.endDate;

				
				self.options.page = 1;
				self.options.seq = 0;
				$('body, html').animate({scrollTop: 0},500);

				self.refresh( 200 );
			});


			self.$el.filter.find('[data-action=submit]').click(function() {

				self.options.page = 1;
				self.options.seq = 0;
				$('body, html').animate({scrollTop: 0},500);

				self.refresh( 200 );
			});

			$(window).scroll(function() {

				self.$elem.find('.u-scrolltop').parent().toggleClass('is-scrolling', $(this).scrollTop() > 60 );
				var h = self.$el.listsbox.outerHeight() - $(this).outerHeight();

				if( h-$(this).scrollTop() <= 200 && !self.$el.listsbox.parent().hasClass('has-loading') && self.options.more ){
					self.loadMore();
				}
			});
			setTimeout(function () {
				self.resize();
			}, 1);
			$(window).resize(function() {
				self.resize();
			});


			$('.navigation-trigger').click(function() {
				setTimeout(function () {
					self.resize();
				},1);
			});


			self.$el.listsbox.delegate('[data-action=booking]', 'click', function(event) {
				var a = $(this).data();
				var b = $(this).closest('tr').data();
				
				
				$.lightbox(app.getUri('booking/create'), {
					id: b.periodId,
					bus: a.no
				}, {
					
				});
			});
		},
		resize: function () {
			var self = this;

			if( $('#doc').hasClass('fixed_elem') ) return;

			var outer = $( window ); // $( window );
			var offset = self.$elem.offset();
			var right = 0;
			var fullw = outer.width() - (offset.left+right);
			var fullh = outer.height(); // + outer.scrollTop();

			var left = $('body').hasClass('is-pushed-left') ? 260: 50;
			var top = $('.page-topbar').outerHeight();

			
			self.$el.header.css({
				position: 'fixed',
				right: right,
				left: left,
				top: top,
				zIndex: 50
			});

			// console.log( self.$el.toolbar.outerHeight() );

			self.$el.content.css({
				paddingTop: 100
			});

		},
		loadMore: function () {
			var self = this;

			self.options.page++;
			self.refresh();
		},

		loadCity: function (val) {
			var self = this;

			var $filterCity = self.$el.filter.find(':input#city');

			Tour.Location.cityList({country: val}).done(function (resp) {

				$filterCity.empty();
				$filterCity.append( $('<option>', {value: '', text: '-' }) );

				if( val=='' || resp.length==0 ){
					$filterCity.prop('disabled', true).addClass('disabled'); //.parent().addClass('hidden_elem');
				}
				else{
					$filterCity.prop('disabled', false).removeClass('disabled').parent().removeClass('hidden_elem');
					$.each(resp, function(key, value) {
						$filterCity.append( $('<option>', {value: value.id, text: value.name }) );
					});
				}

				self.loadSeries({country: val});
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		},
		loadSeries: function (data) {
			var self = this;

			var $el = self.$el.filter.find(':input#ser');

			Tour.lists( data || {}).done(function (resp) {

				$el.empty();
				$el.append( $('<option>', {value: '', text: self.settings.seriesText || '-' }) );

				if( resp.length==0 ){
					$el.prop('disabled', true).addClass('disabled').parent().addClass('hidden_elem');
				}
				else{
					$el.prop('disabled', false).removeClass('disabled').parent().removeClass('hidden_elem');

					$.each(resp, function(key, value) {
						var optgroup = $('<optgroup>', {label: value.name });

						if( value.items.length ){
							$.each(value.items, function(i, item) {
								optgroup.append( $('<option>', {value: item.id, text: item.code+ ' - ' +item.name }) );
							});

							$el.append( optgroup );
						}
					});
				}
			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
		},
	};

	$.fn.productList = function( options ) {
		return this.each(function() {
			var $this = Object.create( ProductList );
			$this.init( options, this );
			$.data( this, 'productList', $this );
		});
	};

	$.fn.productList.settings = {};
	
})( jQuery, window, document );