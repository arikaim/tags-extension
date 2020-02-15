'use strict';

$(document).ready(function() {  
    $('.tags-dropdown').dropdown({
        apiSettings: {     
            on: 'now',      
            url: arikaim.getBaseUrl() + '/api/tags/admin/list/{query}',   
            cache: false        
        },
        filterRemoteData: false         
    });
});