// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var PromotionSelectItem = {
		init: function (options, elem) {
			var self = this;

			self.$elem = $(elem);

			// set Data
			self.options = $.extend( {}, $.fn.promotionSelectItem.options, options );

			self.items = {};

			$.each(self.options.items, function(i, obj) {
				
				var key = obj.id + '_' + obj.bus;
				self.items[key]= obj;
			});



			// self.calculate();
			// console.log( self.options, self.$elem );

			// set Elem
			self.$country = self.$elem.find('[role=country]');
			self.$series = self.$elem.find('[role=series]');
			self.$period = self.$elem.find('[role=period]');
			self.$preview = self.$elem.find('[role=preview]');


			self.calculate();
			/*$.each(self.items, function(i, data) {
				self.activePeriod(data, 1);
			});*/


			self.country();
			/**/

			self.$period.closest('table').hide();
			self.$elem.find('.period-action').hide();
			self.$elem.find('[data-action]').addClass('hidden_elem');

			self.$country.delegate('[data-id]', 'click', function(event) {
				self.activeCountry( $(this) );
			});

			self.$series.delegate('[data-id]', 'click', function(event) {
				self.periodList( $(this) );
			});

			self.$period.delegate(':input[type=checkbox]', 'change', function(event) {


				var data = $(this).closest('tr').data();
				self.activePeriod(data, $(this).prop('checked'));

				if( !$(this).prop('checked') ){
					self.removePreviewPeriod( data.period_id + '_' + data.bus );
				}
				else{
					var $series = self.$preview.find('[series-id='+ data.serie_id +']');
					
					if( !$series.hasClass('open') ){
						self.openPreviewSeries( $series );
					}
				}

				// self.periodList( $(this) );
			});


			self.$elem.find('[data-period-action=all], [data-period-action=cancel]').click( function(event) {3

				var action = $(this).attr('data-period-action')=='all' ? 1:0;

				self.$period.find(':input[type=checkbox]').prop('checked', action);

				$.each(self.$period.find(':input[type=checkbox]'), function() {
					var data = $(this).closest('tr').data();
					self.activePeriod(data, action);

					if( action==0 ){
						self.removePreviewPeriod( data.period_id + '_' + data.bus );
					}
				});
			});

			self.$elem.find('[data-action=cancel]').click( function(event) {3

				self.$period.find(':input[type=checkbox]').prop('checked', 0);
				$.each(self.$period.find(':input[type=checkbox]'), function() {
					self.activePeriod($(this).closest('tr').data(), 0);
				});

				self.items = {};
				self.calculate();

				self.$preview.empty();
			});


			self.$preview.delegate('[data-period-action=remove]', 'click', function(event) {
				self.removePeriod( $(this) );
			});

			self.$preview.delegate('[data-series-action=remove]', 'click', function(event) {
				$.each($(this).closest('[series-id]').find('[period-id]'), function(index, el) {
					self.removePeriod( $(this) );
				});
			});
			

			self.$preview.delegate('[series-id]>.label', 'click', function(event) {
				self.openPreviewSeries( $(this).parent() );
			});
		},

		removePeriod: function ($el) {
			var self = this;

			var id = $el.closest('[period-id]').attr('period-id');

			var input = self.$period.find(':input#period_'+ id);
			if( input.length==1 ){
				input.prop('checked', 0);
				self.activePeriod(input.closest('tr').data(), 0);
			}
			else{
				delete self.items[ id ];
				self.calculate();
			}

			self.removePreviewPeriod( id );
		},

		openPreviewSeries: function ( $this ) {
			var is = $this.hasClass('open');

			if( is ){
				$this.find('.sub-item').slideUp(100, function() {
					$this.removeClass('open');
				});
			}
			else{
				$this.find('.sub-item').slideDown(100, function() {
					$this.addClass('open');
				});
			}
		},
		removePreviewPeriod: function (id) {

			var self = this;
			var previewPeriod = self.$preview.find('[period-id='+ id +']');
			if(previewPeriod.length){

				var box = previewPeriod.closest('[series-id]');
				previewPeriod.remove();

				if( box.find('[period-id]').length==0 ){
					box.remove();
				}
			}
		},

		country: function () {
			var self = this;

			self.$country.html( self.el.loader );

			$.ajax({
				url: app.getUri( 'location/country/list' ),
				type: 'GET',
				dataType: 'json',
				data: { has_series: true }
			})
			.done(function(res) {

				self.$country.empty();
				$.each(res.items, function(i, obj) {
					self.$country.append( $('<li>', {text: obj.name, 'data-id': obj.id}) )
				});

				self.activeCountry( self.$country.find('>li').first() );
			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
			
		},
		activeCountry: function ( $el ) {
			var self = this;

			var data = $el.data();
			$el.addClass('active').siblings().removeClass('active');

			self.$series.html( self.el.loader );
			$.ajax({
				url: app.getUri( 'tour/lists' ),
				type: 'GET',
				dataType: 'json',
				data: { country: data.id, has_period: 1 }
			})
			.done(function(res) {

				self.$series.empty();
				$.each(res.items, function(i, obj) {

					var li = $('<li>', {'data-id': obj.id}).append(
						$('<div>', {class: ''}).append(
							  $('<span>').text( obj.code )
							, ' - '
						 	, $('<span>').text( obj.name )
						)
					);

					li.data({
						serie_id: obj.id,
						serie_code: obj.code,
						serie_name: obj.name,

						country_id: obj.country_id,
						country_name: obj.country_name
					});

					self.$series.append( li )
				});

				if( res.items.length ){
					self.periodList( self.$series.find('>li').first() );
				}
				else{
					self.$period.closest('table').hide();
				}
				
			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
		},

		periodList: function ( $el ) {
			var self = this;

			var data = $el.data();
			$el.addClass('active').siblings().removeClass('active');

			self.$period.closest('table').hide();
			self.$elem.find('.period-action').hide();

			// self.$period.html( self.el.loader );
			$.ajax({
				url: app.getUri( 'tour/periodList' ),
				type: 'GET',
				dataType: 'json',
				data: { series: data.serie_id, state: [1] }
			})
			.done(function(res) {

				self.$period.empty();
				$.each(res, function(i, obj) {

					$.each(obj.busList, function(key, bus) {
						
						id = 'period_'+ obj.id +'_'+ bus.no;
						var input= $('<input type="checkbox" id="'+id+'">');


						if( self.items[ obj.id +'_'+ bus.no ] ){
							input.prop('checked', true);
						}

						var tr = $('<tr>').append(
							  $('<td>').html( $('<label>', {class: 'checkbox', 'for': id}).html(input) ) 
							, $('<td>').html( '<label for="'+id+'">'+ obj.date_str +'</label>'  ) 
							, $('<td>', {class: 'tar'}).html( '<label for="'+id+'">'+ bus.no + '/' +bus.seat +'</label>' ) 
							// , $('<td>').text( bus.seat ) 
						);

						data.period_id = obj.id;
						data.period_name = obj.date_str;
						data.bus = bus.no;
						data.seat = bus.seat;
						tr.data(data);

						self.$period.append( tr );

					});
				});

				if( res.length>0 ){
					self.$elem.find('.period-action').show();
					self.$period.closest('table').show();
				}
				
			})
			.fail(function() {
				// console.log("error");
			})
			.always(function() {
				// console.log("complete");
			});
		},

		activePeriod: function (data, action) {
			var self = this;

			var key = data.period_id + '_' + data.bus;
			if( !self.items[ key ] ){
				self.items[ key ] = data;
			}


			if( !action ){
				delete self.items[ key ];
			}

			self.calculate();
		},

		calculate: function () {
			var self = this;

			var dd = {};
			$.each(self.items, function(i, obj) {
				
				if( !dd[ obj.country_id ] ){
					dd[ obj.country_id ] = {
						name: obj.country_name,
						series: {}
					}
				}

				if( !dd[ obj.country_id ].series[ obj.serie_id ] ){
					dd[ obj.country_id ].series[ obj.serie_id ] = {
						name: obj.serie_code + ' - ' + obj.serie_name,
						code: obj.serie_code,
						period: {},
					}
				}

				dd[ obj.country_id ].series[ obj.serie_id ].period[ obj.period_id + '_' + obj.bus ] = {
					name: obj.period_name,
					bus: obj.bus,
					seat: obj.seat,
				}
			});


			// self.$preview.empty();
			$.each(dd, function(countryID, country) {
				
				var $country = self.$preview.find('[country-id='+ countryID +']')

				if( $country.length==0 ){
					$country = $('<div>', {class:'item', 'country-id': countryID}).append(
						  $('<div>', {class:'label'}).text( country.name )
						, $('<div>', {class:'sub-item'})
					);

					self.$preview.append( $country );
				}

				$.each(country.series, function(seriesID, series) {
					var $series = $country.find('[series-id='+ seriesID +']');

					if( $series.length==0 ){
						$series = $('<div>', {class:'item', 'series-id': seriesID}).append(
							  $('<div>', {class:'label'}).append( $('<span>').append(
							  	  $('<span>', {class: 'text'}).text( series.name )
							  	, $('<button>', {type: 'button', 'data-series-action':'more', class: 'more'}).html('<i class="ic icon-angle-right"></i>')
							  	, $('<button>', {type: 'button', 'data-series-action':'remove', class: 'remove'}).html('<i class="icon-remove"></i>')
							  ) )
							, $('<div>', {class:'sub-item _period'})
						);

						$country.find('>.sub-item').append( $series );
					}

					$.each(series.period, function(periodID, period) {
						var $period = $series.find('[period-id='+ periodID +']');

						if( $period.length==0 ){
							$period = $('<div>', {class:'item', 'period-id': periodID}).append(
								$('<div>', {class:'label'}).append( $('<span>').append(
									  period.name 
									, $('<button>', {type: 'button', 'data-period-action':'remove', class: 'remove'}).html('<i class="icon-remove"></i>') 
									, $('<input>', {type: 'hidden', value:periodID, name: 'items[]'}) 
								)
							) );

							$series.find('>.sub-item').append( $period );
						}
					});
				});

			});

			self.$elem.find('[data-action]').toggleClass('hidden_elem', Object.keys(self.items).length==0 );
			// console.log( dd, self.items );
		},

		el: {
			loader: function () {
				return '<li class="loader"><div class="loader-spin-wrap"><div class="loader-spin"></div></div></li>';
			}
		}
	};

	$.fn.promotionSelectItem = function( options ) {
		return this.each(function() {
			var $this = Object.create( PromotionSelectItem );
			$this.init( options, this );
			$.data( this, 'promotionSelectItem', $this );
		});
	};

	$.fn.promotionSelectItem.options = {
		delay: 1,
		items: [],
	};
	
})( jQuery, window, document );