'use strict';

$(document).ready(function() {
    safeCall('tagsView',function(obj) {
        obj.initRows();
    },true);  
});