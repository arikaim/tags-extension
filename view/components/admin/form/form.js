/**
 *  Arikaim
 *  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license.html
 *  http://www.arikaim.com
 * 
 *  Extension: Category
 *  Component: category:admin.categories.form
 */

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