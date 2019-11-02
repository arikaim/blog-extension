/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */

function BlogControlPanel() {
    var self = this;

    this.addPage = function(data, onSuccess, onError) {
        return arikaim.post('/api/blog/admin/detete/',data,onSuccess,onError);          
    };

    this.deletePage = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/blog/admin/detete/' + uuid,onSuccess,onError);          
    };

    this.updatePage = function(data, onSuccess, onError) {
        return arikaim.put('/api/blog/admin/update',data, onSuccess, onError);          
    };

    this.disablePage = function(data, onSuccess, onError) {
        return arikaim.put('/api/blog/admin/update',data, onSuccess, onError);          
    };
    
    this.enablePage = function(data, onSuccess, onError) {
        return arikaim.put('/api/blog/admin/update',data, onSuccess, onError);          
    };

    this.addPost = function(data, onSuccess, onError) {
        return arikaim.post('/api/blog/admin/detete/',data,onSuccess,onError);          
    };

    this.init = function() {     
        arikaim.ui.tab();      
    };
}

var blogControlPanel = new BlogControlPanel();

arikaim.page.onReady(function() {
    blogControlPanel.init();
});