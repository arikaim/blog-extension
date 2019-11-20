$(document).ready(function() {
    safeCall('pages',function(obj) {
        obj.initRows();
    },true);   
});