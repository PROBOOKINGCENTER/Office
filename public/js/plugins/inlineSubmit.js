// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var InlineSubmit = {
		init: function (options, form) {
			var self = this;

			self.$form = $(form);
			self.$submit = self.$form.find(':input[type=submit]');

			self.verify();

			// Event
			self.$form.find(':input').change(function() {
				self.verify('change');
			}).keyup(function() {
				self.verify('keyup');
			});

			
			self.$form.submit(function(evt) {
				evt.preventDefault();

				Event.inlineSubmit( self.$form ).done(function( result ) {
					Event.processForm(self.$form, result);
				});
			});
		},

		verify: function(e) {
			var self = this;

			var disable = true;
			$.each(self.$form.find(':input'), function(index, el) {

				var val = '';
				if( el.nodeName=='INPUT' ){
					if( $(this).attr('type')=='radio' || $(this).attr('type')=='checkbox' ){
						if( $(this).prop('checked') ){
							val = $(el).val();
						}
					}
					else if( $(this).attr('type')!='hidden' ){
						val = $(el).val();
					}
				}
				else if( el.nodeName=='TEXTAREA' || el.nodeName=='SELECT' ){
					val = $(el).val();
				}


				if( val!='' ){
					disable = false;
					return;
				}
			});


			self.$submit
				.prop('disabled', disable)
				.toggleClass('disabled', disable);
		}
	};


	$.fn.inlineSubmit = function( options ) {
		return this.each(function() {
			var $this = Object.create( InlineSubmit );
			$this.init( options, this );
			$.data( this, 'inlineSubmit', $this );
		});
	};
	
})( jQuery, window, document );