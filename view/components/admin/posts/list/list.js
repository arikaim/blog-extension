'use strict';

arikaim.component.onLoaded(function() {
    safeCall('posts',function(obj) {
        obj.initRows();
    },true); 
});