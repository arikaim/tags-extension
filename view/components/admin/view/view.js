/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com 
*/
'use strict';

function TagsView() {
    var self = this;
    this.messages = null;

    this.loadMessages = function() {
        if (isObject(this.messages) == true) {
            return;
        }

        arikaim.component.loadProperties('tags::admin.view',function(params) { 
            self.messages = params.messages;
        }); 
    };

    this.init = function() {
        var language = $('#tags_rows').attr('language');

        this.loadMessages();

        $('#choose_language').dropdown({
            onChange: function(value) {  
                $('#tags_rows').attr('language',value);     
                paginator.setParams({ language: value });  
                search.options.params = { language: value };     
                self.loadRows(value);       
            }
        }); 

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
            paginator.setPage(1,'tags',function(result) {
                paginator.reload();
            });
            self.initRows();    
        },'tagsSearch');

        this.initRows();
    };

    this.loadRows = function(language) {
        arikaim.page.loadContent({
            id: 'tags_rows',
            component: 'tags::admin.view.rows',
            params: { language: language }
        },function() {
            self.initRows();
        });   
    };

    this.initRows = function() {

        arikaim.ui.button('.delete-button',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');
            
            var message = arikaim.ui.template.render(self.messages.remove.content,{ title: title });
            modal.confirmDelete({ 
                title: self.messages.remove.title,
                description: message
            },function() {
                tags.delete(uuid,function(result) {
                    arikaim.ui.table.removeRow('#' + uuid);               
                });
            });
        });

        arikaim.ui.button('.edit-button',function(element) {
            var uuid = $(element).attr('uuid');
            
            arikaim.ui.setActiveTab('#edit_tag','.tags-tab-item')   
            arikaim.page.loadContent({
                id: 'tags_content',
                component: 'tags::admin.edit',
                params: { uuid: uuid }
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

        arikaim.ui.button('.translations-button',function(element) {
            var uuid = $(element).attr('uuid');
            
            arikaim.ui.setActiveTab('#translations','.tags-tab-item')   
            arikaim.page.loadContent({
                id: 'tags_content',
                component: 'tags::admin.translations',
                params: { uuid: uuid }
            });            
        });
    };
}

var tagsView = new TagsView();

$(document).ready(function() {
    tagsView.init();   
    tagsView.initRows();
});