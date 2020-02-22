/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function BlogControlPanel() {
    var self = this;

    this.addPage = function(data, onSuccess, onError) {
        return arikaim.post('/api/blog/admin/page/add',data,onSuccess,onError);          
    };

    this.deletePage = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/blog/admin/page/delete/' + uuid,onSuccess,onError);          
    };

    this.updatePage = function(data, onSuccess, onError) {
        return arikaim.put('/api/blog/admin/page/update',data, onSuccess, onError);          
    };

    this.setPageStatus = function(uuid, status, onSuccess, onError) {
        var data = {
            uuid: uuid,
            status: status
        };
        
        return arikaim.put('/api/blog/admin/page/status',data,onSuccess,onError);          
    };
    
    this.addPost = function(data, onSuccess, onError) {
        return arikaim.post('/api/blog/admin/post/add',data,onSuccess,onError);          
    };

    this.deletePost = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/blog/admin/post/delete/' + uuid,onSuccess,onError);          
    };

    this.setPostStatus = function(uuid, status, onSuccess, onError) {
        var data = {
            uuid: uuid,
            status: status
        };
        
        return arikaim.put('/api/blog/admin/post/status',data,onSuccess,onError);          
    };

    this.updatePost = function(data, onSuccess, onError) {
        return arikaim.put('/api/blog/admin/post/update',data,onSuccess,onError);          
    };

    this.restorePost = function(uuid, onSuccess, onError) {
        var data = {
            uuid: uuid
        };

        return arikaim.put('/api/blog/admin/post/restore',data,onSuccess,onError);          
    };

    this.restorePage = function(uuid, onSuccess, onError) {
        var data = {
            uuid: uuid
        };
        
        return arikaim.put('/api/blog/admin/page/restore',data,onSuccess,onError);          
    };

    this.emptyTrash = function(onSuccess, onError) {
        return arikaim.delete('/api/blog/admin/trash/empty',onSuccess,onError);          
    };

    this.init = function() {     
        arikaim.ui.tab();      
    };
}

var blogControlPanel = new BlogControlPanel();

arikaim.page.onReady(function() {
    blogControlPanel.init();
});