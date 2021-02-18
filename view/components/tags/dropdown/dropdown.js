'use strict';

arikaim.component.onLoaded(function() {
    $('.tags-dropdown').dropdown({
        apiSettings: {     
            on: 'now',      
            url: arikaim.getBaseUrl() + '/api/tags/admin/list/{query}',   
            cache: false        
        },
        filterRemoteData: false         
    });
});