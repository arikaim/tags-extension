'use strict';

$(document).ready(function() {
    function initTagsForm() {
        arikaim.ui.form.onSubmit("#tags_form",function() {  
            return tags.update('#tags_form');
        },function(result) {          
            arikaim.ui.form.showMessage(result.message);        
        });
    }

    $('.tags-dropdown').on('change',function(element) {
        var selected = $(this).dropdown('get value');    
        if (isEmpty(selected) == true) {
            $('#tag_content').html("");
        } else {
            arikaim.page.loadContent({
                id: 'tag_content',
                component: 'tags::admin.form',
                params: { uuid: selected }
            },function(result) {
                initTagsForm();
            });  
        }            
    });

    initTagsForm();    
});