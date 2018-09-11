// Utility
if ( typeof Object.create !== 'function' ) {
	Object.create = function( obj ) {
		function F() {};
		F.prototype = obj;
		return new F();
	};
}

(function( $, window, document, undefined ) {
	
	var Charts = {
		init: function( options, elem ){
			var self = this;

			self.$elem = $( elem );
			self.options = $.extend( {}, $.fn.charts.options, options );

            /*self.$elem.css({
                width: self.options.width || self.$elem.parent().width(),
                height: self.options.height || self.$elem.parent().height()
            });*/

            if( self.$elem.data('type') ){
                self.options.type = self.$elem.data('type');
            }


            var highcharts = Object.create( self.load );
            highcharts.init( self.options, elem );
            $.data( elem, 'highcharts', highcharts );
		},

        load: {
            init: function ( options, elem ) {
                var self = this;

                self.$elem = $( elem );
                self.options = options;
                
                if( self.options.tooltip.formatter ){

                    var formatter = self.options.tooltip.formatter;
                    var text = '';
                    self.options.tooltip.formatter = function () {

                        text = '';
                        var name = this.series.name;
                        if( typeof formatter.name !== 'undefined' ){
                           name = formatter.name;
                        }
                        
                        if( name ){
                            text += '<b>' + name + '</b><br>';
                        }

                        if( formatter._x ){
                            text += this.x + formatter._x + '<br>';
                        }

                        if( formatter.y_ ){
                            text += formatter.y_ +  PHP.number_format(this.point.y);
                        }
                        

                        return text;
                    }

                    if( text='' ){
                        self.options.tooltip.formatter = null;
                    }
                }

                if( typeof $.fn['highcharts'] === 'undefined' ){
                    Event.getPlugin( 'highcharts', app.getUri('public/js/charts/highcharts/highcharts.js')  ).done(function () {
                        // self.loadData();
                        
                        self.display();

                    }).fail(function () {
                        console.log( 'Is not connect plugin: plot ' );
                    });
                }
                else{
                    self.display();
                    // self.loadData();
                }

            },

            loadData: function () {
                var self = this;
                self.display();
            },

            display: function () {
                var self = this;
                self.chart = self.$elem.highcharts( $.extend( {}, $.fn.charts.setup, self.options ) );
                if( typeof self.options.onOpen === 'function' ){
                    self.options.onOpen( self );
                }

                // console.log( self.chart, 'data:', self.$elem.data() );

                $('.navigation-btn-trigger').click(function() {
                    setTimeout(function () {
                        self.resize();
                    }, 300);
                    
                });
            },

            resize: function () {
                var self = this;

                // var highcharts = self.$elem.data('highcharts');

                // var height = self.$elem.height();
                // var width = self.$elem.width();

                // console.log( 'resize', self.highcharts );
                // self.chart.highcharts(width, height);
            },

        },
	}

	$.fn.charts = function( options ) {
		return this.each(function() {
			var chart = Object.create( Charts );
			chart.init( options, this );
			$.data( this, 'charts', chart );
		});
	};

	$.fn.charts.options = {
        
    };

    $.fn.charts.setup = {
        colors: ['#1b90d6','#66bb6a', '#66bb6a'], //1cc2ff
        chart: {
            type: 'column', // line
            backgroundColor: null,
            polar: true,
        },
        title: {
            text: null
        },
        subtitle: {
            text: null
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: null
            },
            stackLabels: {
                // format: "{total}"
            }
        },
        xAxis: {
            lineColor: "#c0c0c0",
            lineWidth: 3,
            // startOfWeek: 0
            tickWidth: 0,
            title: {
                text: null
            },
            categories: []
        },

        legend: {
            enabled: false,

            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false,
            /*title: {
                text: 'WorldClimate.com',
                style: {
                    fontWeight: 'bold'
                }
            },*/
            // labelFormatter: function () {
                // console.log( this.name );
                // return '(click to hide)';
            // }
        },
        plotOptions: {
            column: {
                // stacking: 'normal',
                dataLabels: {
                    // enabled: true,
                    // color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    /*style: {
                        textShadow: '0 0 3px black'
                    }*/
                },
                events:{
                    legendItemClick: function () {
                        // alert( 1 );
                    },
                    // showInLegend: true
                }
            },
            bar: {
                // stacking: 'normal',
            }
        },
        tooltip: {
            enabled: false,
            shadow: false,
            // formatter: function () {
                // console.log( this.series.name );
                // return '<b>' + this.series.name + '</b><br/>' +
                    // this.point.y + ' ' + this.point.name.toLowerCase();
            // }
        },
        exporting: {
            enabled: false
        },
        credits: {
            enabled: false
        },
    };
	
})( jQuery, window, document );