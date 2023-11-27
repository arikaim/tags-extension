/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com 
 */
'use strict';

function TagsControlPanel() {
 
    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/admin/tags/delete/' + uuid,onSuccess,onError);          
    };

    this.add = function(data, onSuccess, onError) {
        return arikaim.post('/api/admin/tags/add',data,onSuccess,onError);          
    };

    this.update = function(data, onSuccess, onError) {
        return arikaim.put('/api/admin/tags/update',data,onSuccess,onError);          
    };

    this.getList = function(query, onSuccess, onError) {
        return arikaim.get('/api/admin/tags/list/' + query,onSuccess,onError);          
    };
}

var tags = new TagsControlPanel();

arikaim.component.onLoaded(function() {
    arikaim.ui.tab();
});