// Bar chart
var hour = [];

for (var $i = 0; $i < 24; $i++) {
    hour.push($i);
}

var data = {
    labels: hour,
    series: [
        hourly_reports,
    ]
};

var options = {
    seriesBarDistance: 10,
    axisY: {
        onlyInteger: true,
    }
};

var responsiveOptions = [
    ['screen and (max-width: 640px)', {
        seriesBarDistance: 10,
        onlyInteger: true,

        axisX: {
            labelInterpolationFnc: function (value) {
                return value[0];
            }
        }
    }]
];

new Chartist.Bar('.ct-bar-chart', data, options, responsiveOptions);

data = {
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    series: [
        weekly_reports,
    ]
};

options = {
    onlyInteger: true,
    seriesBarDistance: 10,
};

responsiveOptions = [
    ['screen and (max-width: 640px)', {
        seriesBarDistance: 5,
        onlyInteger: true,

        axisX: {
            labelInterpolationFnc: function (value) {
                return value[0];
            }
        }
    }]
];

new Chartist.Bar('.ct-bar-chart-week', data, options, responsiveOptions);
