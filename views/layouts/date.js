var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

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

/**
 * Gets the number of days in a particular month.
 * For example, Feb 2012 has 29 days; so this function returns 29. 
 */
function numberOfDays(year, month) {
    var d = new Date(year, month, 0);
    return +d.getDate();
}