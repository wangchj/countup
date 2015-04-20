$(function() {
    drawFigures();
    initWindowResizing();
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
        resizeFigures();
        prevWidth = w;
    }    
}

function resizeFigures() {
    $('svg').each(function() {
        Snap(this).clear();
    });

    drawFigures();
}

var numcol  = 7; //Number of columns in this calendar
var gutter  = 10; //Spacing between months in pixels
var space   = 2; //Spacing between cells in pixels

var showDateText = false;

var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

/**
 * Draw a figure for all counters.
 */
function drawFigures() {
    var now = new Date();
    //console.log(now);
    //console.log(now.getFullYear());
    $('svg').each(function() {
        drawFigure(this, now.getFullYear(), now.getMonth());
    });

    $('rect').tooltip({container:'body'});
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
    var monthWidth = +g1.attr('width');
    var monthHeight = +g1.attr('height');
    var tx = (monthWidth) + gutter;
    g2.transform('t' + tx);

    snap.attr({width: 2 * monthWidth + gutter, height: monthHeight});
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
    var calId   = snap.attr('id'); //Id of this svg calendar
    var now     = new Date();
    //console.log(hist);

    /*console.log('year: ' + year);
    console.log('month: ' + month);
    console.log('numdays: ' + numdays);
    console.log('startOn: ' + startOn);
    console.log('numcol: ' + numcol);
    console.log('numrow: ' + numrow);
    console.log('gutter: ' + gutter);
    console.log('space: ' + space);
    console.log('width: ' + width);*/

    var group = snap.group();
    group.attr({width: width * numcol + space * numcol, height: width * numrow + space * numrow});

    for(row = 0; row < numrow; row++) {
        for(col = 0; col < numcol; col++) {
            var x = col * width + col * space;
            var y = row * width + row * space;
            var s = row * numcol + col; //Cell sequence number, starting from 0;
            var date = s - startOn + 1; //Date number, e.g. 15
            //console.log(s);
            //console.log(startOn);
            var c = '#d6e685';

            if(s < startOn || s >= numdays + startOn)
                c = '#f2f2f2';
            else {
                c = getColor(calId, new Date(year, month, date));
            }
            
            //var c = s < startOn || s > numdays + startOn ? '#eeeeee' : '#d6e685';
            var rectId = 'rect' + s;
            var rect = snap.rect(x, y, width, width).attr({id:rectId, fill:c});
            if(s >= startOn && s < startOn + numdays)
                rect.attr({'data-toggle':'tooltip', title:makeFullDateStr(year, month, date)});

            if(year == now.getFullYear() && month == now.getMonth() && date == now.getDate())
                rect.attr({strokeWidth:1, stroke:'#aaa'});
            group.add(rect);

            if(showDateText) {
                if(date > 0 && date <= numdays) {
                    var fontSize = width / 2;
                    var numchar = date < 10 ? 1 : 2;
                    var textX = x + (width - fontSize) / numchar;
                    var textY = y + (width - fontSize + (width / 6));
                    var textColor = '#aaa';
                    var text = snap.text(textX, textY, date).
                        attr({'font-size': fontSize + 'px', 'fill': textColor, 'opacity':0.8});
                    group.add(text);
                }
            }
        }
    }

    return group;
}

var colorYes = '#d6e685';
var colorNo  = '#e3e3e3';
var colorStart = '#bee685';
//var colorEnd = '#e6c785';

function getColor(calId, date) {
    //If this date is today or in the future, set this cell as no color.
    if(dateGreaterOrEqual(date, new Date()))
        return colorNo;

    var hist = data[calId]; //History data for this counter

    for(var h = 0; h < hist.length; h++) {
        var start = hist[h].start;
        var end = hist[h].end == null ? null : hist[h].end;

        if((dateGreaterOrEqual(date, start) && dateLess(date, end)) || (dateGreaterOrEqual(date, start) && (end == null))) {
            if(dateEqual(date, start))
                return colorStart;
            //else if(dateEqual(date, end))
            //    return '#e6c785';
            else
                return colorYes;
        }
    }

    return colorNo;
}

function dateEqual(date1, date2) {
    if(date1 == null || date2 == null)
        return false;

    return date1.getFullYear() == date2.getFullYear() && date1.getMonth() == date2.getMonth() && date1.getDate() == date2.getDate();
}

/**
 * date1 is greater than (comes after) date2.
 */
function dateGreater(date1, date2) {
    if(date1 == null || date2 == null)
        return false;

    if(date1.getFullYear() > date2.getFullYear())
        return true;
    if(date1.getFullYear() < date2.getFullYear())
        return false;
    if(date1.getMonth() > date2.getMonth())
        return true;
    if(date1.getMonth() < date2.getMonth())
        return false;
    if(date1.getDate() > date2.getDate())
        return true;
    return false;
}

/**
 * date1 comes before date2.
 */
function dateLess(date1, date2) {
    return dateGreater(date2, date1);
}

function dateGreaterOrEqual(date1, date2) {
    return dateGreater(date1, date2) || dateEqual(date1, date2);
}

function dateLessOrEqual(date1, date2) {
    return dateLess(date1, date2) || dateEqual(date1, date2);
}

/**
 * Make date string YYYY-mm-dd
 */
function makeDateStr(year, month, date) {
    if(month < 10) month = '0' + month;
    if(date  < 10) date  = '0' + date;
    return year + '-' + month + '-' + date;
}

/**
 * Example: March 22, 2015
 */
function makeFullDateStr(year, month, date) {
    return monthNames[month] + ' ' + date + ', ' + year;
}

function numberOfDays(year, month) {
    var d = new Date(year, month, 0);
    return +d.getDate();
}