// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {
	
	var CalendarMin = {
		init: function( options, elem ){
			var self = this;

			self.$elem = $( elem );
			self.options = $.extend( {}, $.fn.calendarmin.options, options );


			self.$elem.addClass('calendarmin-wrap');
			self.config();

			
			self.setElem();
			self.update();

			// 

			/*self.Events()

			if ( typeof self.options.onComplete === 'function' ) {
				self.options.onComplete.apply( self, arguments );
			}
			
			// upcoming
			self.upcoming.init(  self.$upcoming ,self.options );

			$(window).resize(function() {
				self.resize();
				self._Month.resizeTab();
			});*/
		},

		config: function () {
			var self = this;


			if( !self.options.selectedDate ){
				self.options.selectedDate = new Date();
			}
			self.options.selectedDate.setHours(0, 0, 0, 0);

			// set date
			self.date = {
				today: new Date(),
				theDate: self.options.theDate,
				selected: self.options.selectedDate,
				lang: self.options.lang,

			};
			self.date.today.setHours(0, 0, 0, 0);

			if( !self.date.theDate ){
				self.date.theDate = new Date( self.date.today );
			}
		},

		setElem: function () {
			var self = this;

			self.$header = $('<div>', {class: 'calendarmin-title clearfix'});
			self.$calendar = $('<div>', {class: 'calendarmin-content'});


			self.$header.append(

				$('<h2>', {class: 'lfloat'}).append(
					  $('<span>', { class: 'month'})
					, $('<strong>', {class: 'year'})
				)
				, '<div class="rfloat">' +

				'<a class="btn-icon prevnext prev btn-no-padding" data-action-prevnext-mini="prev" title="Previous"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 129 129" enable-background="new 0 0 129 129"><g><path d="m88.6,121.3c0.8,0.8 1.8,1.2 2.9,1.2s2.1-0.4 2.9-1.2c1.6-1.6 1.6-4.2 0-5.8l-51-51 51-51c1.6-1.6 1.6-4.2 0-5.8s-4.2-1.6-5.8,0l-54,53.9c-1.6,1.6-1.6,4.2 0,5.8l54,53.9z"/></g></svg></a>'+

				'<a class="btn-icon prevnext prev btn-no-padding" data-action-mini="today" title="Today"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" width="438.533px" height="438.533px" viewBox="0 0 438.533 438.533" style="enable-background:new 0 0 438.533 438.533;" xml:space="preserve"><g><path d="M409.133,109.203c-19.608-33.592-46.205-60.189-79.798-79.796C295.736,9.801,259.058,0,219.273,0   c-39.781,0-76.47,9.801-110.063,29.407c-33.595,19.604-60.192,46.201-79.8,79.796C9.801,142.8,0,179.489,0,219.267   c0,39.78,9.804,76.463,29.407,110.062c19.607,33.592,46.204,60.189,79.799,79.798c33.597,19.605,70.283,29.407,110.063,29.407   s76.47-9.802,110.065-29.407c33.593-19.602,60.189-46.206,79.795-79.798c19.603-33.596,29.403-70.284,29.403-110.062   C438.533,179.485,428.732,142.795,409.133,109.203z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></a>' +

				'<a class="btn-icon prevnext next btn-no-padding" data-action-prevnext-mini="next" title="Next"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 129 129" enable-background="new 0 0 129 129"><g><path d="m40.4,121.3c-0.8,0.8-1.8,1.2-2.9,1.2s-2.1-0.4-2.9-1.2c-1.6-1.6-1.6-4.2 0-5.8l51-51-51-51c-1.6-1.6-1.6-4.2 0-5.8 1.6-1.6 4.2-1.6 5.8,0l53.9,53.9c1.6,1.6 1.6,4.2 0,5.8l-53.9,53.9z"/></g></svg></a></div>'
			);

			self.$elem.append(self.$header, self.$calendar);
		},

		setCalendar: function () {
			var self = this;

			self.$header.find('.year').text( Datelang.year( self.date.theDate.getFullYear(), 'normal', self.options.lang ) );
			self.$header.find('.month').text( Datelang.month( self.date.theDate.getMonth(), 'normal', self.options.lang ) );
			// self.$elem.find('.calendarLeft-listsboxWrap').css('top', self.$elem.find('.calendarLeft-calendarWrap').outerHeight() );	
		},
		
		update: function () {
			var self = this;


			self.$calendar.html( self.getCalendar() );
			self.setCalendar();
			// console.log( self.date );
			// self.display();
		},

		calculation: function ( date ) {
			
			var self = this;
			var data = {};
			var theDate = date || self.date.today;

			var firstDate = new Date( theDate.getFullYear(), theDate.getMonth(), 1);
			firstDate = new Date(theDate);
	        firstDate.setDate(1);
	        var firstTime = firstDate.getTime();
			var lastDate = new Date(firstDate);
	        lastDate.setMonth(lastDate.getMonth() + 1);
	        lastDate.setDate(0);
	        var lastTime = lastDate.getTime();
	        var lastDay = lastDate.getDate();

			// Calculate the last day in previous month
	        var prevDateLast = new Date(firstDate);
	        prevDateLast.setDate(0);
	        var prevDateLastDay = prevDateLast.getDay();
	        var prevDateLastDate = prevDateLast.getDate();

	        var prevweekDay = self.options.weekDayStart;
	        if( prevweekDay>prevDateLastDay ){
	        	prevweekDay = 7-prevweekDay;
	        }
	        else{
	        	prevweekDay = prevDateLastDay-prevweekDay;
	        }

			data.lists = [];
			for (var y = 0, i = 0; y < 7; y++){

				var row = [];
				var weekInMonth = false;

				for (var x = 0; x < 7; x++, i++) {
					var p = ((prevDateLastDate - prevweekDay) + i);

					var call = {};
					var n = p - prevDateLastDate;
					call.date = new Date( theDate ); 
					call.date.setHours(0, 0, 0, 0); 
					call.date.setDate( n );

					// If value is outside of bounds its likely previous and next months
	            	if (n >= 1 && n <= lastDay){
	            		weekInMonth = true;

	            		if( self.date.today.getTime()==call.date.getTime()){
	                    	call.today = true;
	                    }

	                    if( self.date.selected.getTime()==call.date.getTime() ){
	                    	call.selected = true;
	                    }
	            	}
	            	else{
	            		call.noday = true;
	            	}

					row.push(call);
				}

				if( row.length>0 && weekInMonth ) data.lists.push(row);
			}

			data.header = [];
			for (var x=0,i=self.options.weekDayStart; x<7; x++, i++) {
				if( i==7 ) i=0;
				data.header.push({
	        		key: i, 
	        		text: Datelang.day( i, 'normal', self.options.lang ),  // numbar, type, lang
	        	});
			};

			self.date.first = firstDate;
			self.date.end = lastDate;
			self.date.start_date = data.lists[0][0].date;
			self.date.end_date = data.lists[ data.lists.length-1 ][6].date;

			return data;
		},

		getCalendar: function (date, options) {
			
			var self = this;

			var options = $.extend( {}, {
				lists: true
			}, options );
			var settings = self.calculation( self.date.theDate );

			var $table = $('<table>', {class: 'calendarmin-table'});

			var $head = $('<tr>');
				$head.append( $('<th>', {class:'cw', text: 'CW' }) );
			$.each(settings.header, function(index, obj) {
				$head.append( $('<th>', {text: Datelang.day( obj.key, 'short', self.options.lang ) }) );
			});


			var $body = $('<tbody>');
			$.each( settings.lists, function(y, row){

				var $tr = $('<tr>');

				$.each(row, function(x, call){

					if( x==0 ){
						
						$tr.append( $('<td>', {class:'cw', text: self.getWeekNumber(call.date) }) );
					}

					var ul = '';
					if( options.lists ){
						ul = $('<ul>', {class: 'calendar-list'});
					}

					var $td = $('<td>', {'data-date': PHP.dateJStoPHP( call.date ) }).append( 
						$('<div>', {class: "inner"}).append(
							  $('<span>', {text: call.date.getDate()})
							, ul
						)
					);

					if( call.noday ){
						$td.addClass('over');
					}
					if( call.today ){
						$td.addClass('today');
					}

					$tr.append( $td );
				});

				$body.append( $tr );
			});

			return $table.append( $('<thead>').html( $head ), $body );
		},
		getWeekNumber: function (d) {
			
			// Copy date so don't modify original
		    d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
		    // Set to nearest Thursday: current date + 4 - current day number
		    // Make Sunday's day number 7
		    d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay()||7));
		    // Get first day of year
		    var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
		    // Calculate full weeks to nearest Thursday
		    var weekNo = Math.ceil(( ( (d - yearStart) / 86400000) + 1)/7);
		    // Return array of year and week number
		    return weekNo;
		},
	};

	$.fn.calendarmin = function( options ) {
		return this.each(function() {
			var $this = Object.create( CalendarMin );
			$this.init( options, this );
			$.data( this, 'calendarmin', $this );
		});
	};

	$.fn.calendarmin.options = {
		// string
		lang: "th",
		format: 'month', 
		summary: true,

		// date
		weekDayStart: 1,
		theDate: null,
		selectedDate: null,

		// resize
		resize: true,
		bordertop: 1,
		borderleft: 1,

		onComplete: function () {}
	};
	
})( jQuery, window, document );