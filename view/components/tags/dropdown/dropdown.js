'use strict';

arikaim.component.onLoaded(function() {
    $('.tags-dropdown').dropdown({
        apiSettings: {     
            on: 'now',      
            url: arikaim.getBaseUrl() + '/api/admin/tags/list/{query}',   
            cache: false        
        },
        filterRemoteData: false         
    });
});