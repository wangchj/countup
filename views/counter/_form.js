$(function(){
    var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    $('#counter-startdate').datepicker({dateFormat: 'MM d, yy'});
    var now = new Date();
    $('#counter-startdate').val(monthNames[now.getMonth()] + ' ' + now.getDate() + ', ' + now.getFullYear());

});