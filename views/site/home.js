$(function() {
    initCal();
});

function initCal() {
    $('div.cal').each(function(){
        console.log($(this).attr('id'));
        var cal = new CalHeatMap();
        cal.init({
            data: "/~wangchj/datas-years.json",
            start: new Date(2000, 5),
            //id : '#' + $(this).attr('id'),
            itemSelector: '#' + $(this).attr('id'),
            domain : "month",
            subDomain : "x_day",
            range : 2,
            cellSize: 15,
            cellPadding: 2,
            //cellradius: 5,
            domainGutter: 15,
            displayLegend:false,
            legendColors: {
                //min: '#d6e685',
                max: '#d6e685',
                //empty:'#eeeeee'
            },
            //highlight:new Date(2014, 5, 1)
            weekStartOnMonday: false,
            //scale: [40, 60, 80, 100]
        });
    });
}
