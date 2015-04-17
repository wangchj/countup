$(function() {
    //initWindowResizing();
    //resizeBoxFigure();
    drawFigures();
});

var prevWidth; //Figure container width before resizing

function initWindowResizing() {
    prevWidth = $('.cimg').width();

    var resizeId;
    $(window).resize(function() {
        clearTimeout(resizeId);
        resizeId = setTimeout(windowResized, 500);
    });
}

function windowResized() {
    var w = $('.cimg').width();
    if(w != prevWidth) {
        resizeBoxFigure();
        prevWidth = w;
    }    
}

var numcol  = 7; //Number of columns in this calendar
var gutter  = 10; //Spacing between months in pixels
var space   = 2; //Spacing between cells in pixels

/**
 * Draw a figure for all counters.
 */
function drawFigures() {
    var now = new Date();
    console.log(now);
    console.log(now.getFullYear());
    $('svg').each(function() {
        drawFigure(this, now.getFullYear(), now.getMonth());
    });
}

/**
 * Draw a figure, which contains multiple calendar.
 */
function drawFigure(svg, year, month) {
    var width = Math.min($('.cimg').width() - 50, 900); //Width of the figure
    var snap = Snap(svg);

    //snap.rect(50, 50, 50, 50);
    var g1, g2;

    if(month === 0) {
        g1 = drawMonthCalendar(snap, 0, width, year - 1, 11); //December of last year
    }
    else {
        g1 = drawMonthCalendar(snap, 0, width, year, month - 1);
    }

    var g2 = drawMonthCalendar(snap, 1, width, year, month);

    var tx = (+g1.attr('width')) + gutter;
    g2.transform('t' + tx);
}

/**
 * Draw a calendar.
 * @param snap      Snap object
 * @param i         calendar index: the first calendar is 0, next is 1.
 * @param cwidth    figure (canvas) total width.
 * @param year      year, as in 2014
 * @param month     month: 0 = Jan, 1 = Feb, ... , 11 = Dec
 */
function drawMonthCalendar(snap, i, cwidth, year, month) {
    var numdays = numberOfDays(year, month + 1); //Number of days in this month;
    var startOn = (new Date(year, month, 1)).getDay(); //Day of the first of this month; 0 = Sunday, 1 = Monday, etc
    var numrow  = Math.ceil((numdays + startOn) / numcol); //Number of rows in this calendar
    var width   = (((cwidth - gutter) / 2) - ((numcol - 1) * space)) / numcol; //width of cell

    console.log('year: ' + year);
    console.log('month: ' + month);
    console.log('numdays: ' + numdays);
    console.log('startOn: ' + startOn);
    console.log('numcol: ' + numcol);
    console.log('numrow: ' + numrow);
    console.log('gutter: ' + gutter);
    console.log('space: ' + space);
    console.log('width: ' + width);

    var group = snap.group();
    group.attr({width: width * numcol + space * numcol, height: width * numrow + space * numrow});

    for(row = 0; row < numrow; row++) {
        for(col = 0; col < numcol; col++) {
            var x = col * width + col * space;
            var y = row * width + row * space;
            var s = row * numcol + col;
            //console.log(s);
            //console.log(startOn);
            var c = s < startOn || s > numdays + startOn ? '#eeeeee' : '#d6e685';
            var rect = snap.rect(x, y, width, width).attr({fill:c});
            group.add(rect);
        }
    }

    return group;
}

function numberOfDays(year, month) {
    var d = new Date(year, month, 0);
    return +d.getDate();
}

function resizeBoxFigure() {
    var numcol = 52;                    //Number of columns
    var numrow = 7;                     //Number of rows
    var space  = 2;                     //Size of spacing in pixels
    var width  = Math.min($('.cimg').width() - 50, 900); //Width of figure
    var wcell  = (width - ((numcol - 1) * space)) / numcol;   //Width of each cell
    var height = wcell * (numrow) + space * (numrow); //Height of the figure
    
    //console.log(width);

    $('svg').each(function() {
        var s = Snap(this);
        var r = s.selectAll('rect');
        //console.log(r);

        s.attr({width:width, height:height});

        for(i = 0; i < r.length; i++) {
            var col = Math.floor(i / numrow);
            var row = i % numrow;
            var x = col * wcell + col * space;
            var y = row * wcell + row * space;
            r[i].attr({x:x, y:y, width:wcell, height:wcell}, 2000);
        }

        //s.attr({width:10});
    })
    
}