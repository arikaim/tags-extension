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
        var language = $('#tags_rows').attr('language');

        paginator.init('tags_rows',{
            name: 'tags::admin.view.rows',
            params: { language: language }
        },'tags');
        
        search.init({
            id: 'tags_rows',
            component: 'tags::admin.view.rows',
            event: 'tags.search.load'
        },'tags')  

        arikaim.events.on('tags.search.load',function(result) {      
            paginator.reload();
            self.initRows();    
        },'tagsSearch');

        this.initRows();
    };

    this.initRows = function() {

        var component = arikaim.component.get('tags::admin');
        var remove_message = component.getProperty('messages.remove.content');

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
                });
            });
        });

        arikaim.ui.button('.relations-button',function(element) {
            var uuid = $(element).attr('uuid');
            
            arikaim.ui.setActiveTab('#relations','.tags-tab-item')   
            arikaim.page.loadContent({
                id: 'tags_content',
                component: 'tags::admin.relations',
                params: { uuid: uuid }
            });            
        });
    };
}

var tagsView = new TagsView();

arikaim.page.onReady(function() {
    tagsView.init();   
});