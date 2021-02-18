'use strict';

arikaim.component.onLoaded(function() {
    $('.tags-dropdown').on('change',function(element) {
        var selected = $(this).dropdown('get value');  
        if (isEmpty(selected) == true) {
            $('#relations_content').html("");
        } else {
            arikaim.page.loadContent({
                id: 'relations_content',
                component: 'system:admin.orm.relations.view',
                params: {                   
                    extension: 'tags',
                    model: 'TagsRelations',
                    id: selected 
                }
            });  
        }            
    });
});