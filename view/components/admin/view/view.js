/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com 
*/
'use strict';

function TagsView() {
    var self = this;
  
    this.init = function() {
        this.loadMessages('tags::admin.view');
   
        paginator.init('tags_rows',{
            name: 'tags::admin.view.rows',
            params: {}
        },'tags');
        
        search.init({
            id: 'tags_rows',
            component: 'tags::admin.view.rows',
            event: 'tags.search.load'
        },'tags');

        arikaim.events.on('tags.search.load',function(result) {     
            paginator.setPage(1,'tags',function(result) {
                paginator.reload();
            });
            self.initRows();    
        },'tagsSearch');

        arikaim.events.on('tag.create',function(tags) {
            if (isArray(tags) == false) {
                return false;
            }

            tags.forEach(function(item) {
                arikaim.page.loadContent({
                    id: 'tags_rows',
                    prepend: true,
                    component: 'tags::admin.view.row',
                    params: {
                        uuid: item
                    }
                },function() {
                    self.initRows();
                });  
            });
             
        },'tagCreate');

        arikaim.events.on('tag.update',function(uuid) {
            arikaim.page.loadContent({
                id: 'row_' + uuid,   
                replace: true,             
                component: 'tags::admin.view.row',
                params: {
                    uuid: uuid
                }
            },function() {
                self.initRows();
            });  
        },'tagUpdate');

        arikaim.ui.loadComponentButton('.create-tag');

        this.initRows();
    };

    this.loadRows = function() {
        arikaim.page.loadContent({
            id: 'tags_rows',
            component: 'tags::admin.view.rows',
            params: {}
        },function() {
            self.initRows();
        });   
    };

    this.initRows = function() {
        arikaim.ui.loadComponentButton('.tag-action-button');

        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');
            var message = arikaim.ui.template.render(self.getMessage('remove.content'),{ title: title });
         
            modal.confirmDelete({ 
                title: self.getMessage('remove.title'),
                description: message
            },function() {
                tags.delete(uuid,function(result) {
                    arikaim.ui.table.removeRow('#row_' + uuid);               
                });
            });
        });
    };
}

var tagsView = createObject(TagsView,ControlPanelView);

arikaim.component.onLoaded(function() {
    tagsView.init();   
});