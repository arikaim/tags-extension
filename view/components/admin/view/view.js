/**
 *  Arikaim
 *  @version    1.0  
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license.html
 *  http://www.arikaim.com
 * 
 *  Extension: Tags
 *  Component: tags::admin.view
*/

function TagsView() {
    var self = this;

    this.init = function() {
        var component = arikaim.component.get('tags::admin');
        var remove_message = component.getProperty('messages.remove.content');
      
        paginator.init('tags_rows');

        arikaim.ui.button('.add-button',function(element) {
           
            var language = $(element).attr('language');
           // category.loadAddCategory(parent_id,language); 
        });
      
        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(remove_message,{ title: title });
            modal.confirmDelete({ 
                title: component.getProperty('messages.remove.title'),
                description: message
            },function() {
                tags.delete(uuid,function(result) {
                    $('#' + uuid).remove();
                    $('.class-' + uuid).remove();                   
                });
            });
        });
    };
}

var tagsView = new TagsView();

arikaim.page.onReady(function() {
    tagsView.init();   
});