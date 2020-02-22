'use strict';

$(document).ready(function() {
    safeCall('posts',function(obj) {
        obj.initRows();
    },true); 
});