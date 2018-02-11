$(function() {

    $.plot('#placeholder-vertical', dataVertical, {
        xaxis: {
            minTickSize: [1, 'day'],
            mode: 'time',
            timezone: 'browser',
        },
        yaxis: {
            min: minValue,
            max: maxValue
        },
        bars: {
            show: true,
            barWidth: 50000000,
            numbers: {
                show: true,
                font: '10pt Arial',
                fontColor: '#000000',
                // remove the next 3 lines to vertically center the values
                threshold: 0.25, // any value < 25% of the maximum data point will display above the bar
                yAlign: function(y) { return y; }, // shows numbers at the top of the bar
                yOffset: 5 // pixel offset so numbers are not right up on the edge of the top of the bar
            }
        }
    });

    $.plot('#placeholder-horizontal', dataHorizontal, {
        xaxis: {
            min: minValue,
            max: maxValue
        },
        yaxis: {
            minTickSize: [1, 'day'],
            mode: 'time',
            timezone: 'browser',
        },
        bars: {
            show: true,
            barWidth: 50000000,
            horizontal: true,
            numbers: {
                show: true,
                font: '10pt Arial',
                fontColor: '#000000',
                // remove the next 3 lines to horizontally center the values
                threshold: 0.25, // any value lower than 25% of the maximum data point will display above the bar
                xAlign: function(x) { return x; }, // shows numbers at the top of the bar
                xOffset: 5 // pixel offset so numbers are not right up on the edge of the top of the bar
            }
        }
    });
});
