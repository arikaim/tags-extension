'use strict';

arikaim.page.onReady(function() {    
    arikaim.ui.form.addRules("#tags_form",{
        inline: false,
        fields: {
            tags: {
                identifier: "tags",      
                rules: [{
                    type: "minLength[2]"       
                }]
            }
        }
    });   
});