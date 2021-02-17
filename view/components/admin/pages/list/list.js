'use strict';

arikaim.component.onLoaded(function() {
    safeCall('pagesView',function(obj) {
        obj.initRows();
    },true);   
});