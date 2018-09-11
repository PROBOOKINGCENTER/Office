// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var BookingForm = {
		init: function ( options, elem ) {
			var self = this;

			self.options = $.extend({}, $.fn.bookingform.options, options);
			
			self.$elem = $(elem);
			self.$summary = self.$elem.find('[role=summary]');
			self.$extralist = self.$elem.find('[role=extralist]');
			console.log( self.options );
			// self.$listsbox = self.$elem.find('[role=listsbox]');

			self.seat = self.options.seat;
			self.pax = 0;


			Event.setPlugin( self.$extralist, 'tableinputs', {
				items: self.options.extraList,
				data: self.options.extraListData,
				onChange: function (a, b, c) {
					

					var ul = self.$elem.find('[data-summary-section=extralist]');
					ul.empty();

					$.each(b, function(i, o) {

						if( o.name!='' ){

							ul.append(
								$('<tr>').append( 
									  $('<td>', {class: 'label'}).text( o.name )
									, $('<td>', {class: 'data'}).append(

										  $('<span>', {class: 'value', 'data-value': o.price}).text( PHP.number_format(o.price) )
										, ' '
										, $('<span>', {class: 'x'}).text( 'x' )
										, ' '
										, $('<span>', {class: 'count'}).text( PHP.number_format(o.qty) )
										, ' '
										, $('<span>').text( '=' )
										, ' '
										, $('<span>', {class: 'sum'}).text( PHP.number_format(o.amount) )

									)
								)
							);
						}
					});


					self.$elem.find('.js-extralist-total').text( PHP.number_format( c ) );
					self.verify();
				}
			});

			// self.verify();

			// Event 
			Event.setPlugin( self.$elem.find(':input[name=sales]'), 'selectize', {

			});

			Event.setPlugin( self.$elem.find(':input[name=company]'), 'selectize', {

				onChange: function (id) {
					self.setAgency(id);
				}
			}); 

			$('.input-value-number', self.$elem).on('click', function() {
				
				if( !$(this).closest('.touchtime-wrap').hasClass('open') ){
					self.$elem.find('.touchtime-wrap.open').removeClass('open');
				}

				if( self.seat > 0 ){
					var $wrap = $(this).closest('.touchtime-wrap');

					var quota = null;
					if( $(this).attr('data-quota') ){
						quota = self.pax/parseInt($(this).attr('data-quota'));
					}
					
					// 
					self.setNumberSeat( $wrap.find('.list-touchtime'), quota );
					$wrap.addClass('open');
				}
				
				return false;
			});

			self.$elem.find('.input-extra-discount').blur(function() {
				if( $(this).val()!='' ){
					$(this).val( PHP.number_format( $(this).val() ) );
				}
				self.verify();
			}).keyup(function () {
				if( $(this).val()!='' ){
					$(this).val( PHP.number_format( $(this).val() ) );
				}
				self.verify();
			}).click(function() {
				if( $(this).val()!='' ){
					$(this).select();
				}
			});

			$(window).click(function(event) {
				if( !$(event.target).hasClass('input-value-number') && $(event.target).closest('.touchtime-wrap.open').length===0 ){
					self.$elem.find('.touchtime-wrap.open').removeClass('open');
				}
			});

			self.$elem.delegate('.list-touchtime-item', 'click', function(event) {
				
				$(this).closest('.touchtime-wrap').find('.input-value-number').val( $(this).attr('data-value') );
				self.$elem.find('.touchtime-wrap.open').removeClass('open');

				self.verify();
			});

			self.$elem.find('.input-value-number').change(function(event) {


				if( $(this).hasClass('input-value-pax') && parseInt($(this).val())>self.options.seat ){
					$(this).val(self.options.seat);
				}

				self.verify();
			}).click(function(event) {
				
				if( $(this).val()!='' ){
					$(this).select();
				}
				
			}).keydown(function(event) {
				if (event.keyCode === 13) {
			        event.preventDefault();
			    }
			}).keyup(function () {
				self.$elem.find('.touchtime-wrap.open').removeClass('open');
				self.verify();
			});
			

			self.$elem.find('[data-value-action=minus], [data-value-action=plus]').click(function() {
				
				var type = $(this).attr('data-value-action'),
					input = $(this).closest('.controls').find('.input-value-number');

				self.$elem.find('.touchtime-wrap.open').removeClass('open');
				var qty = parseInt(input.val()) || 0;

				if(type=='minus'){ qty--; }
				else{ qty++; }

				if( qty < 0 ){ qty = 0; }
				if( qty>self.seat ){ qty = self.seat; }

				input.val( qty );
				self.verify();
			});


			if( self.options.booking ){
				self.booking = self.options.booking;

				if( self.booking.company_id ){
					self.setAgency(self.booking.company_id, self.booking.agen_id);

					
				}
				
			}
		},

		setAgency: function(company, agen){
			var self = this;
			var input = self.$elem.find(':input[name=agent]');

			input.empty();
			if( !company ){
				input.append( $('<option>',{value: '', text: '-'}) );
				return false;
			}

			$.ajax({
				url: app.getUri('tour/agencyList'),
				type: 'GET',
				dataType: 'json',
				data: {company: company},
			})
			.done(function(res) {
				// console.log("success", res);
				$.each(res, function(a, b){
					input.append( $('<option>',{value: b.id, text: b.name}) )
				});

				if( agen ){
					input.val(agen);
				}
				
			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
		},

		setItem: function (data) {
		},

		verify: function () {
			var self = this;

			$.each(self.$elem.find(':input.input-value-number'), function() {
				self.$summary.find('[data-summary='+ $(this).attr('data-name') +']').find('.count').text( $(this).val() || 0 );
			});

			var pax = 0;
			$.each(self.$elem.find(':input.input-value-pax'), function() {
				pax += parseInt($(this).val() || 0);
			});
			self.$elem.find('[data-summary-section=discount]').find('.count').text( PHP.number_format(pax) );

			var total = 0, discount = 0;
			$.each(self.$elem.find('[data-summary-section]'), function() {
				var section = $(this).attr('data-summary-section');

				var subtotal = 0;
				$.each($(this).find('tr'), function(index, el) {
					var sum = 0;

					if( $(this).find('.value').length==1 && $(this).find('.count').length==1 ){
						sum = parseInt($(this).find('.value').data('value')) * parseInt($(this).find('.count').text()) || 0;

						$(this).find('.sum').text( PHP.number_format(sum) );
					}
					else if( $(this).find('[data-value]').length==1 ){

						sum = parseInt($(this).find('[data-value]').attr('data-value')) || 0;	
					}


					if( section=='discount' ){
						discount+=sum;
					}
					else{
						subtotal+=sum;
					}
					
				});

				total+= subtotal;
				$(this).find('.subtotal').text(  PHP.number_format(subtotal) )
			});

			if( self.$elem.find('.input-extra-discount').length==1 ){
				discount+=parseInt( self.get_numbers( self.$elem.find('.input-extra-discount').val() ) )||0;
			}

			self.$elem.find('[data-summary-section=total]').find('.pax').text( PHP.number_format(pax) );
			self.$elem.find('[data-summary-section=total]').find('.discount').text( PHP.number_format(discount) );

			self.pax = pax;
			total = total-discount;
			if( total<0 ) total = 0;

			self.$elem.find('[data-discount-name=value]').val( self.pax );
			self.$elem.find('[data-summary-section=total]').find('.total').text( PHP.number_format( total ) );

			var deposit = parseInt(self.options.deposit)*pax;
			deposit = total<deposit? total:deposit;
			deposit = deposit<0?0:deposit;

			// self.seat = self.options.seat - pax;

			var fullpayment = total-deposit;
			fullpayment = fullpayment<0 ? 0:fullpayment;

			self.$elem.find('[data-summary-section=pay]').find('.deposit').text( PHP.number_format(deposit) );
			self.$elem.find('[data-summary-section=pay]').find('.fullpayment').text( PHP.number_format(fullpayment) );
		},

		get_numbers: function (input) {
			var number = input.match(/[0-9]+/g);
			if( number ){
				number = number.join([]);
			}
			return number;
		},

		setNumberSeat: function( $ul, a ) {
			var self = this;
			var seat = a || self.seat;

			$ul.empty();
			for (var i = 0; i <= seat; i++) {
				$ul.append( $('<div>', {class: 'list-touchtime-item', 'data-value': i, text: i }) );
			}
		},
	};

	$.fn.bookingform = function( options ) {
		return this.each(function() {
			var $this = Object.create( BookingForm );
			$this.init( options, this );
			$.data( this, 'bookingform', $this );
		});
	};

	$.fn.bookingform.options = {
		multiple: false,
		// size: null,
		salesList: [],

		seat: 34,

		deposit: 0
	};
	
})( jQuery, window, document );