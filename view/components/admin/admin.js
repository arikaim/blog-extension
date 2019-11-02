/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */

function BlogControlPanel() {
    var self = this;

    this.delete = function(uuid,onSuccess,onError) {
        return arikaim.delete('/api/blog/admin/detete/' + uuid,onSuccess,onError);          
    };

    this.update = function(data,onSuccess,onError) {
        return arikaim.put('/api/blog/admin/update',data, onSuccess, onError);          
    };

    this.init = function() {           
    };
}

var blogControlPanel = new BlogControlPanel();

arikaim.page.onReady(function() {
    blogControlPanel.init();
});