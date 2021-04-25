/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function BlogControlPanel() {
  
    this.addPage = function(data, onSuccess, onError) {
        return arikaim.post('/api/admin/blog/page/add',data,onSuccess,onError);          
    };

    this.deletePage = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/admin/blog/page/delete/' + uuid,onSuccess,onError);          
    };

    this.updatePage = function(data, onSuccess, onError) {
        return arikaim.put('/api/admin/blog/page/update',data, onSuccess, onError);          
    };

    this.setPageStatus = function(uuid, status, onSuccess, onError) {
        var data = {
            uuid: uuid,
            status: status
        };
        
        return arikaim.put('/api/admin/blog/page/status',data,onSuccess,onError);          
    };
    
    this.addPost = function(data, onSuccess, onError) {
        return arikaim.post('/api/admin/blog/post/add',data,onSuccess,onError);          
    };

    this.deletePost = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/admin/blog/post/delete/' + uuid,onSuccess,onError);          
    };

    this.setPostStatus = function(uuid, status, onSuccess, onError) {
        var data = {
            uuid: uuid,
            status: status
        };
        
        return arikaim.put('/api/admin/blog/post/status',data,onSuccess,onError);          
    };

    this.updatePost = function(data, onSuccess, onError) {
        return arikaim.put('/api/admin/blog/post/update',data,onSuccess,onError);          
    };

    this.restorePost = function(uuid, onSuccess, onError) {
        var data = {
            uuid: uuid
        };

        return arikaim.put('/api/admin/blog/post/restore',data,onSuccess,onError);          
    };

    this.restorePage = function(uuid, onSuccess, onError) {
        var data = {
            uuid: uuid
        };
        
        return arikaim.put('/api/admin/blog/page/restore',data,onSuccess,onError);          
    };

    this.emptyTrash = function(onSuccess, onError) {
        return arikaim.delete('/api/admin/blog/trash/empty',onSuccess,onError);          
    };  
}

var blogControlPanel = new BlogControlPanel();

arikaim.component.onLoaded(function() {
    arikaim.ui.tab();      
});