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

	var ToursList = {
		init: function ( options, elem ) {
			var self = this;

			self.$elem = $(elem);

			self.settings =  $.extend( {}, $.fn.toursList.settings, options );

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

			// set Elem
			self.$listsbox = self.$elem.find('[role=listsbox]');
			self.$formSearch = self.$elem.find('.form-search');
			self.$inputSearch = self.$elem.find('#search-query');
			// self.$scroll = self.$elem.find('');

			var $selectCountry = self.$elem.find('select#country'),
				$selectCity = self.$elem.find('select#city');

			$selectCountry.change(function() {

				var items = $(this).find('[value='+ $(this).val() +']').data('items');
				$selectCity.empty();

				if( !items ) {
					$selectCity.prop('disabled', true)
					$selectCity.parent().addClass('hidden_elem');
					return false;
				}

				if( items.length > 0 ){
					
					$selectCity.append( $('<option>', {value: '', text: '-'}) )
					$.each(items, function(i, obj) {
						$selectCity.append( $('<option>', {value: obj.id, text: obj.name}) );
					});

					$selectCity.prop('disabled', false);
					$selectCity.parent().removeClass('hidden_elem');
				}
				else{
					$selectCity.prop('disabled', true)
					$selectCity.parent().addClass('hidden_elem');
				}
			});

			self.$elem.find('.js-daterange').caleran({
				format: 'DD/MM/YYYY',
				// rangeOrientation: "vertical",
				showButtons: true,
				// inline: true,
				startOnMonday: true,
				startEmpty: true,
				showFooter: false,

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

			// console.log( self.settings );
			self.refresh();


			// Event 
			$('[data-action=tryagain]').click(function() {
				self.refresh(500);
			});

			$('[data-action=more]').click(function() {
				self.loadMore(500);
			});

			$(window).scroll(function() {

				self.$elem.find('.u-scrolltop').parent().toggleClass('is-scrolling', $(this).scrollTop() > 60 );


				var h = self.$listsbox.outerHeight() - $(this).outerHeight();

				if( h-$(this).scrollTop() <= 200 && !self.$listsbox.parent().hasClass('has-loading') && self.options.more ){
					self.loadMore();
				}
			});

			self.$elem.find('[data-action=scrolltop]').click(function() {
				$('body, html').animate({scrollTop: 0 }, 'fast' );
			});

			self.currSearch = '';
			self.$formSearch.submit(function(e) {
				e.preventDefault();

				var val = $.trim( self.$inputSearch.val() );

				if( val!=self.options.q ){
					self.options.q = val;
					self.options.page = 1;
					self.options.seq = 0;
					$('body, html').animate({scrollTop: 0},500);

					self.refresh( 200 );
				}
			});

			self.$inputSearch.keyup(function(e) {
				var val = $.trim( self.$inputSearch.val() );

				if( val!=self.options.q && val=='' ){
					self.options.q = val;
					self.options.page = 1;
					self.options.seq = 0;
					$('body, html').animate({scrollTop: 0},500);

					self.refresh( 200 );
				}
			});

			self.$elem.find('[ref=selector]').change(function() {
				
				self.options.page = 1;
				self.options.seq = 0;
				$('body, html').animate({scrollTop: 0},500);

				self.refresh( 200 );
			});


			$('body').delegate('[data-serie-control=re-period]', 'click', function(event) {
				var box = $(this).closest('[serie-id]')
				var data = box.data();

				box.find('[data-ref]').addClass('loadber_elem').html('&nbsp;');

				self.loadPeriod( data.id, box, 200 );
			});


			self.$elem.find('[data-action=cleardate]').click(function() {
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

			self.$listsbox.delegate('[data-action=booking]', 'click', function(event) {
				var a = $(this).data();
				var b = $(this).closest('tr').data();
				
				// window.open(app.getUri('booking/create/'+b.periodId+'/'+a.no),'_blank');
				// $(this).prop('disabled', true);
				$.lightbox(app.getUri('booking/create'), {
					id: b.periodId,
					bus: a.no
				}, {
					
				});
			});
				
			self.resize();
			$(window).resize(function() {
				self.resize();
			});

			$('.navigation-trigger').click(function() {
				setTimeout(function () {
					self.resize();
					
				}, 1);
			});
		},
		setElem: function () {
			var self = this;
			self.$elem = $(self.elem);
			self.$elem.find('[ref]').each(function () {
				if( $(this).attr('ref') ){
					var ref = "$" + $(this).attr('ref');
					self[ref] = $(this);
				}
				
			});

			$.each(self.$elem.find('[plugin=dropdown]'), function() {
				var options = $.parseJSON( $(this).attr('data-options') );
				options.onClick = function (el) {
					self.action($(el).attr('ajaxify'));
				}

				$(this).dropdown(options);
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


			self.$elem.find('[role=topbar]').css({
				position: 'fixed',
				right: right,
				left: left,
				top: top,
				// background: '#fff',
			});

			self.$elem.find('[role=content]').css({
				paddingTop: self.$elem.find('[role=topbar]').outerHeight() || 51
			});
		},

		chooseSelect: function (data, callback) {
			var self = this;

			var $el = self.$elem.find('select[name='+data.id+']'), _get = {};

			$.each( self.$elem.find('select[role=choose][data-id='+data.id+']'), function(index, el) {
				_get[$(this).attr('name')] = $(this).val();
			});

			$.get( data.url, _get, function (res) {
							
				$el.empty();

				var count = 0;
				var options = $.map( res, function (obj) {

					count+= parseInt( obj.count );
					return $('<option>', {
						value: obj.id,
						text: obj.name+ ( obj.count ? ' ('+obj.count+')':'' )
					})[0];
				});

				$el.append( $('<option>', {
					value: '',
					text: 'All' // (' + count + ')
				}), options);

			},'json');
		},

		selection: function (checked, item) {
			var self = this;

			if( item == 'all' ){
				$.each(self.$listsbox.find('tr'), function (i, obj) {
					var item = $(this);

					if(checked==true && !item.hasClass('has-checked')){
						self.selectItem(item);
					}
					else if(checked==false && item.hasClass('has-checked')){
						self.cancelItem(item);
					}
				});
			}
			else{
				if(checked){
					self.selectItem(item);
				}
				else{
					self.cancelItem(item);
				}
			}
		},
		selectItem: function (el) {
			var self = this;
			var toggle_checkbox = el.find('input#toggle_checkbox');
			var id = el.attr('data-id');
			toggle_checkbox.prop('checked', true);
			el.addClass('has-checked');

			self.ids[ parseInt(id) ] = el;
			self.active();
		},
		cancelItem: function (el) {
			var self = this;

			var toggle_checkbox = el.find('input#toggle_checkbox');
			var id = el.attr('data-id');
			toggle_checkbox.prop('checked', false);
			el.removeClass('has-checked');

			delete self.ids[ parseInt(id) ];
			self.active();
		},
		active: function () {
			var self = this;

			var length = Object.keys(self.ids).length;
			if( length > 0){
				self.$actions.addClass('hidden_elem');
				self.$selection.removeClass('hidden_elem').find('.count-value').text( length );
			}
			else{

				self.$selection.addClass('hidden_elem').find('.count-value').text("");
				self.$tabletitle.find('input#checkboxes').prop('checked', false);
				self.$actions.removeClass('hidden_elem');
			}

			self.resize();
		},
		refresh: function ( ready, callback ) {
			var self = this;

			self.$listsbox.parent()  
				.removeClass('has-empty')
				.removeClass('has-error')
				.removeClass('has-more')
				.addClass('has-loading');

			if( self.options.page==1 ){
				self.$listsbox.empty();
			}

			self.is_loading = setTimeout(function () {
				self.fetch().done(function( results ) {

					if( results.error ){
						self.$listsbox.parent().addClass('has-error');
						return false;
					}

					self.options = $.extend( {}, self.options, results.options );

					self.$listsbox.parent().toggleClass('has-more', self.options.more);

					if( parseInt(self.options.page)==1 && parseInt(results.total)==0 ) {
						self.$listsbox.parent().addClass('has-empty');
						return false;
					}
					
					self.buildFrag( results.items );

				});
			}, ready || 1);
		},
		fetch:  function() {
			var self = this;

			if( self.is_search ) self.$elem.find('.search-input').attr('disabled', true);

			$.each( self.$elem.find(':input[ref=selector]'), function() {
				
				if( $(this).attr('name')!='' ) {
					self.options[ $(this).attr('name') ] = $.trim( $(this).val() );
				}
			});

			return $.ajax({
				url: app.getUri('tour/lists'),
				data: self.options,
				dataType: 'json'
			}).done(function () {

				self.$listsbox.parent().removeClass('has-loading');

				if( self.is_search ){
					self.$elem.find('.search-input').attr('disabled', false);
					self.$elem.find('.search-input').focus();

					self.is_search = false;
				}

				self.$listsbox.parent().removeClass('off');
				 
			}).fail(function() { 
				self.$listsbox.parent().addClass('has-error');
			});
		},

		buildFrag: function (items) {
			var self = this;
			
			$.each(items, function(i, obj) {
				self.display( self.setItem( obj ) );

				self.display( $('<tbody>').append( $('<tr>', {class: 'divider'}).append( $('<td>', {colspan: self.settings.keys.length}) ) ) );
			});

			// self.resize();
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
						  	  $('<i>', {class: 'flag icoflag-'+ data.country_code.toLowerCase() })
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

			self.loadPeriod( data.id, tbody );
			tbody.data( data );
			return tbody;
		},
		setElemPeriod: function (eq) {
			var self = this, tr = $('<tr>', {class: 'tr-item'});

			$.each(self.settings.keys, function(i, obj) {

				var txt = obj.key=='seq' 
					? eq
					: $('<div>', {class: 'loadber_elem', 'data-ref': obj.key}).html('&nbsp;');

				var td = $('<td>').addClass( obj.class ).html( txt );

				if( obj.multiple ){
					td.addClass('multiple')
				}

				tr.data( obj );
				tr.append( td );

			});

			return tr;
		},


		display: function( item ) {
			var self = this;

			self.$listsbox.append( item );
			Event.plugins( item );
		},


		loadMore: function () {
			var self = this;

			self.options.page++;
			self.refresh();
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
					url: app.getUri('tour/period/lists'),
					type: 'GET',
					dataType: 'json',
					data: dataPost, // 
				})
				.done(function(res) {

					// console.log( res );

					elem.find('.tr-item').remove();

					var eq = 0;
					$.each(res, function(i, period) {
						eq++; tr = self.setElemPeriod( eq );
						elem.append( tr );


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

							if( discount > 0  ){
								tr.find('[data-ref=price_str]').empty().addClass('has-discount').append(
								 	  $('<span>', {class: 'price x'}).text( PHP.number_format(price) )
									, $('<span>', {class: 'price'}).text( PHP.number_format(price-discount) )
								);
							}

							tr.find('.multiple').attr({'data-bus-no': busSeq});

							self.updateBooking( period.id, busSeq, bus );
						});

					});

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

		setElemBooking( data ) {
			var self = this;

			var bookingList = $('<div>', {class: 'booking-list'}), bookingListCountVal = 0;
			var waitlist = $('<div>', {class: 'booking-list waitlist'}), waitlistCountVal;

			$.each(data, function(j, book) {
				// console.log( j, book );
				// console.log( book );
				if( book.status_arr.type=='booking'  ){

					if( bookingListCountVal > 0 ){
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

					var listTipTable = $('<table>', {class: 'booking-list-tip-table'});
					$.each(book.detailList, function(k, e) {
						
						listTipTable.append(
							$('<tr>').append(
								 '' 
								// , $('<td>').text( '1' )
								, $('<td>').text( e.name )
								, $('<td>').text( e.qty==0 ? '-': PHP.number_format(e.qty) )
							)
						)
					});
					

					bookingListCountVal++;
					bookingList.append( $('<a>').css({'color': book.status_arr.color }).append(
						h1
						, $('<div>', {class: 'booking-list-tip'}).append(
							  ''
							/*, $('<div>', {class: 'booking-list-tip-title'}).css({
							  	'background-color': book.status_arr.color
							  }).append(
							  	  ''
							   	, ( parseInt(book.company_guarantee)==1 ? '<i class="icon-thumbs-up mrs"></i>' : '' )
								, $('<span>', {class: 'name',text: book.name})

							)*/
							, $('<div>', {class: 'booking-list-tip-content'}).append( listTipTable )
						)
					) );
				}

				if( book.status_arr.type=='waitlist' ){

					if( waitlistCountVal > 0 ){
						waitlist.append( $('<span>', {class: 'separator'}).text( ' | ' ) );
					}

					waitlistCountVal++;
					waitlist.append( $('<a>').css({'color': book.status_arr.color }).append( 

						''
						, ( parseInt(book.company_guarantee)==1 ? '<i class="icon-thumbs-up mrs"></i>' : '' )
						,  $('<span>', {class: 'name',text: book.name})
						, ' '
						, $('<span>', {text: book.countVal }) 
						, ' '
						, $('<span>', {text: '('+ book.company_name +')' }) 
					) );
				}
			});


			return {
				bookingList: bookingList,
				bookingListCountVal: bookingListCountVal,

				waitlist: waitlist,
				waitlistCountVal: waitlistCountVal,
			}
		},

		updateBooking: function (period, bus, data) {
			// console.log( data );


			var $el = $('[data-period-id='+ period +']');
			// console.log( period, bus, data );
			var booking = this.setElemBooking(data.bookingList);

			$el.find('[data-bus-no='+ bus +'] [data-ref=seat_str]').removeClass('loadber_elem').html( data.seat? PHP.number_format(data.seat): '-' );
			
			$el.find('[data-bus-no='+ bus +'] [data-ref=wanted_str]').removeClass('loadber_elem').html( data.wanted? PHP.number_format(data.wanted): '-' );

			$el.find('[data-bus-no='+ bus +'] [data-ref=bookingCountVal]').removeClass('loadber_elem').html( data.bookingCountVal? PHP.number_format(data.bookingCountVal): '-' );
			$el.find('[data-bus-no='+ bus +'] [data-ref=fullpayment]').removeClass('loadber_elem').html( data.fullpayment? PHP.number_format(data.fullpayment): '-' );

			
			$el.find('[data-bus-no='+ bus +'] [data-ref=booking_str]').removeClass('loadber_elem').html( booking.bookingList );
			$el.find('[data-bus-no='+ bus +'] [data-ref=waitlist_str]').removeClass('loadber_elem').html( booking.waitlist );
			
			$el.find('[data-bus-no='+ bus +'] [data-ref=action_str]').removeClass('loadber_elem').html( this.setActionPeriod( data ) );

		}

	};

	$.fn.toursList = function( options ) {
		return this.each(function() {
			var $this = Object.create( ToursList );
			$this.init( options, this );
			$.data( this, 'toursList', $this );
		});
	};

	$.fn.toursList.settings = {};
	
})( jQuery, window, document );