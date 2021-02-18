'use strict';

arikaim.component.onLoaded(function() {
    safeCall('tagsView',function(obj) {
        obj.initRows();
    },true);  
});