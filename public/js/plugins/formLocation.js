// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var FormLocation = {
		init: function ( options, elem ) {
			var self = this;
			self.$elem = $(elem);

			// self.options = $.extend( {}, $.fn.slideshow.options, options );

			self.$province = self.$elem.find(':input#location_province');
			self.$zone = self.$elem.find(':input#location_zone');
			self.$district = self.$elem.find(':input#location_district');


		    self.getCity();
		    

		    self.$province.change(function() {
		    	self.getZone();
		    	self.$district.empty();
		    });

		    self.$zone.change(function() {
		    	self.getDistrict();
		    });
		},

		getCity: function () {
			var self = this;

			setTimeout(function () {
				
				$.get( app.getUri('location/provinceList/'), {country: self.$elem.find(':input#location_country').val(), enabled: 1}, function (res) {
					
					
					self.$province.empty();
					self.$province.append( $('<option>', {value: '', text: '-- Choose province --'}) );
					$.each(res, function(index, el) {
						self.$province.append( $('<option>', {value: el.id, text: el.name}) );
					});

				}, 'json');
			}, 1);
		},

		getZone: function () {
			var self = this;

			setTimeout(function () {
				
				$.get( app.getUri('location/zoneList/'), {province: self.$province.val()}, function (res) {
					
					self.$zone.empty();
					self.$zone.append( $('<option>', {value: '', text: '-- Choose zone --'}) );
					$.each(res, function(index, el) {
						self.$zone.append( $('<option>', {value: el.id, text: el.name + ' - ' + el.postcode}) );
					});

					// self.getDistrict();

				}, 'json');
			}, 1);
		},
		getDistrict: function () {
			var self = this;

			setTimeout(function () {
				
				$.get( app.getUri('location/districtList/'), {zone: self.$zone.val()}, function (res) {
					
					self.$district.empty();
					self.$district.append( $('<option>', {value: '', text: '-- Choose district --'}) );
					$.each(res, function(index, el) {
						self.$district.append( $('<option>', {value: el.id, text: el.name}) );
					});

				}, 'json');
			}, 1);
		}
	};

	$.fn.formLocation = function( options ) {
		return this.each(function() {
			var $this = Object.create( FormLocation );
			$this.init( options, this );
			$.data( this, 'formLocation', $this );
		});
	};

	$.fn.formLocation.options = {};
	
})( jQuery, window, document );