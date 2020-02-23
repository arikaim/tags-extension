'use strict';

$(document).ready(function() {
    $('#choose_language').dropdown({
        onChange: function(value) {
            var uuid = $('#tag_form_content').attr('uuid');
            arikaim.page.loadContent({
                id: 'tag_form_content',
                component: 'tags::admin.form',
                params: { 
                    uuid: uuid,
                    language: value 
                }
            },function(result) {
                initTagsForm();
            });   
            $('#language').val(value);
        }
    }); 

    function initTagsForm() {
        arikaim.ui.form.onSubmit("#tags_form",function() {  
            var language = $('#choose_language').dropdown('get value');
            $('#language').val(language);

            return tags.update('#tags_form');
        },function(result) {          
            arikaim.ui.form.showMessage(result.message);        
        });
    }

    $('.tags-dropdown').on('change',function(element) {
        var selected = $(this).dropdown('get value');    
        $('#tag_form_content').attr('uuid',selected);
        
        if (isEmpty(selected) == true) {
           arikaim.ui.hide('#tag_content');
        } else {
            arikaim.ui.show('#tag_content');

            arikaim.page.loadContent({
                id: 'tag_form_content',
                component: 'tags::admin.form',
                params: { uuid: selected }
            },function(result) {
                initTagsForm();
            });  
        }            
    });

    initTagsForm();    
});