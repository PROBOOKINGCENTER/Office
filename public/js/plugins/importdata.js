// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {

	var ImportData = {
		init: function ( options, elem ) {
			var self = this;
			self.elem = elem;
			self.$elem = $( elem );
			
			self.options = $.extend({}, $.fn.importdata.options, options);
			self.type = '';

			self.url = Event.URL + 'render/';
			self.file_type = 'excel';

			self.$listsfield = self.$elem.find('[ref=listsfield]');
			self.$listTitle = self.$elem.find('[ref=listtitle]');
			self.$listsdata = self.$elem.find('[ref=listsbox]');

			self.Events();
		},

		Events: function () {
			var self = this;

			self.$elem.find('[data-action]').click(function() {
				
				self.$elem.find('[data-action]').removeClass('active');
				$(this).addClass('active');

				self.type = $(this).data('action');
				self.openStep( 1 );
			});

			self.$elem.find('[data-step]').click(function() {
				self.openStep( $(this).data('step') );
			});

			self.$elem.find(':input[type=file]').change(function() {

				if( $(this).val()=='' ) return false;
				self.choosefile( this.files[0] );
			});

			self.$elem.find('[data-option]').change(function() {

				if( $(this).attr('type')=='checkbox' ){ //$(this).context.nodeName=='INPUT' && 
					self.options[ $(this).data( 'option' ) ] = $(this).prop('checked');
				}
				else{
					self.options[ $(this).data( 'option' ) ] = $(this).val();
				}

				self.matchData();
			});
			
		},

		openStep: function ( active ) {
			var self = this;

			self.$elem.find('.uiStepList .uiStep').eq( active ).addClass('uiStepSelected').siblings().removeClass('uiStepSelected');

			self.$elem.find('.uiStepList_body>.uiStepList_section').eq( active ).removeClass('hidden_elem').siblings().addClass('hidden_elem');
		},

		choosefile: function ( file ) {
			var self = this;

			self.file = file;
			var formData = new FormData();
			formData.append('file1', file);
			formData.append('type', self.type);

			self.$elem.find('.FileDrop').addClass('has-load');

			return $.ajax({
				type: "POST",
				url: self.url + self.file_type,
				data: formData,
				dataType: 'json',
				processData: false,
        		contentType: false,
			})
			.done(function (res) { 

				self.data = res;
				self.matchData();
			})
			.always(function() {
				// complete
				self.$elem.find('.FileDrop').removeClass('has-load');
			})
			.fail(function(  ) {
				// error
				Event.showMsg({text: 'File Error', load: 1, auto: 1, bg: 'red'});
			});

		},

		matchData: function () {
			var self = this;

			self.$elem.find( '[data-val=column]' ).text(self.data.column);
			self.$elem.find( '[data-val=row]' ).text(self.data.row);
			self.$elem.find( '[data-val=filename]' ).text(self.data.filename);


			// lists field
			var $row = $('<tr>');
			for (var i = 0; i < self.data.column-1; i++) {

				var $select = $('<select>', {
					class: 'inputtext inputtext_field', 
					name: 'import['+i+'][field][]'
				});

				$select.append( $('<option>', {value: '', text: '-'}) );
				$.each( self.data.fields, function(i, obj) {
					$select.append( $('<option>', {value: obj.id, text: obj.name}) );
				});

				$box = $('<div>', {class:'inner'}).append( 
					$select, 
					$('<div>', {class: 'message'}).text('Field already in use') 
				);

				$row.append( $('<td>').append( $box ) );
			}
			$row.append( $('<td>', {class:'free'}) );
			self.$listsfield.html( $row );

			// lists title
			if( !self.options.first_row ){
				var $row = $('<tr>');
				for (var i = 1; i < self.data.column; i++) {

					$row.append( $('<td>').append( $('<div>', {class: 'hdr-text'}).text( 'Column ' + i ) ) );
				}
				$row.append( $('<td>', {class:'free'}) );
				self.$listTitle.html( $row );
			}

			// lists Data
			self.$listsdata.empty();
			$.each(self.data.lists, function(i, cols) {
				
				$row = $('<tr>');
				$.each(cols, function(index, val) {

						val = $('<div>', {class: 'hdr-text'}).text( val );
					if( self.options.first_row && i==0 ){
					}
					else{
						// val = $('<textarea>', {class: 'inputtext', 'data-plugins':'autosize'}).text( val );
						// val.autosize();
					}

					$row.append( $('<td>').html( val ) );
				});


				$row.append( $('<td>', {class:'free'}) );
				if( self.options.first_row && i==0 ){
					self.$listTitle.html( $row );
					return;
				} 

				self.$listsdata.append($row);
			});

			// Event.plugins( self.$listsdata );
			

			self.openStep( 2 );
			self.resize();
		},
		resize: function () {
			var self = this;

			var w_full = self.$elem.outerWidth();
			var w_inner = 0;
			var h_inner = 0;

			$.each(self.$listsdata.find('tr:first-child > td'), function(index, el) {

				var w = $(this).outerWidth();
				w_inner += w;
				h_inner += $(this).height();

				if( $(this).hasClass('free') ) return false;

				$(this).attr('data-id', index).css({ width: w });
				self.$listTitle.find('tr:first-child > td').eq(index).attr('data-id', index).css({
					
					width: w
				});

				self.$listsfield.find('tr:first-child > td').eq(index).css({
					
					width: w
				});

				
			});

			// w_inner = self.$find('.Import_tablePanel-data>table').outerWidth();
			self.$elem.find('.Import_filePanel').width( w_inner>w_full? w_inner:w_full );

			var top = self.$elem.offset().top;
			var h_full =  $(window).height() - (top+240);

			self.$elem.find('.Import_matchData_body').height( h_full );
		}
	};

	$.fn.importdata = function( options ) {
		return this.each(function() {
			var $this = Object.create( ImportData );
			$this.init( options, this );
			$.data( this, 'importdata', $this );
		});
	};

	$.fn.importdata.options = {
		first_row: true
	};
	
})( jQuery, window, document );