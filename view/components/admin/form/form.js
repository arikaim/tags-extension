/**
 *  Arikaim
 *  @version    1.0  
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
            title: {
            identifier: "word",      
                rules: [{
                    type: "minLength[2]"       
                }]
            }
        }
    });   
});