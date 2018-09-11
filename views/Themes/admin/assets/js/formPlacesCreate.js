// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var FormPlacesCreate = {
		init: function (options, elem) {
			var self = this;

			self.$form = $(elem);
			$.each( self.$form.find('[data-ref]'), function () {
				var key = $(this).attr('data-ref');
				self['$'+key] = $(this);
			} );

			self.action = {};
			$.each( self.$form.find('[data-action]'), function () {
				var key = $(this).attr('data-action');
				self.action['$'+key] = $(this);
			} );

			self.$form.submit(function(e) {
				e.preventDefault();				

				Event.inlineSubmit( self.$form ).done(function( result ) {

					// result.url = '';
					Event.processForm(self.$form, result);

					if( result.error ){
						return false;
					}

					console.log( result );

					self.next();
				});
			});

			self.limit = self.$form.find('[data-section]').length;
			self.action.$prev.click(function () {
				self.prev();
			});


			self.$form.find('.uiStep').click(function() {
				if( $(this).hasClass('uiStepSelected') ) return false;
				var index = $(this).index(),
					$section = self.$form.find('[data-section]').eq(index);

				if(index < self.$step.find('.uiStepSelected').index() || $section.hasClass('done') ){
					$(this).addClass('uiStepSelected').siblings().removeClass('uiStepSelected');
					$section.addClass('active').siblings().removeClass('active');
					self.active();
				}

				console.log( index );
			});

			self.active();
		},

		prev: function () {
			var self = this;

			var prev = self.$form.find('.active[data-section]').prev();

			if( prev.length==0 ) return false;

			prev.addClass('active').siblings().removeClass('active');
			self.$step.find('.uiStep').eq( prev.index() ).addClass('uiStepSelected').siblings().removeClass('uiStepSelected');

			self.active();
		},

		next: function () {
			var self = this;

			var next = self.$form.find('.active[data-section]').next();

			if( next.length==0 ) return false;

			next.addClass('active').siblings().removeClass('active');
			self.$step.find('.uiStep').eq( next.index() ).addClass('uiStepSelected').siblings().removeClass('uiStepSelected');

			self.active();
		},

		active: function () {
			var self = this;

			setTimeout( function () {

				self.index = self.$step.find('.uiStepSelected').index();
				self.action.$prev.toggleClass('hidden_elem', self.index==0);

				var type = self.$form.find('.active[data-section]').data('section');
				self.$form.find('#options_type').val( type );

				var lastActive = (self.index+1)==self.limit;
				self.action.$submit.toggleClass('has-next', !lastActive );
				self.$form.find('#options_save').val( lastActive ? 1:0 );
			}, 1);
			
		}
	}
	$.fn.formPlacesCreate = function( options ) {
		return this.each(function() {
			var $this = Object.create( FormPlacesCreate );
			$this.init( options, this );
			$.data( this, 'formPlacesCreate', $this );
		});
	};
	$.fn.formPlacesCreate.settings = {
		multiple: false,
	}
	
})( jQuery, window, document );