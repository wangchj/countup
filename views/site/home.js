$(function() {
    initWindowResizing();
    resizeBoxFigure();
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