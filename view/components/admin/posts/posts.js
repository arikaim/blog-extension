/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */

function PostsControlPanel() {
    var self = this;

    this.init = function() {     
        arikaim.ui.button('.add-post',function(element) {
            return arikaim.page.loadContent({
                id: 'post_content',           
                component: 'blog::admin.posts.add',
                params: {}
            });  
        });
    };

    this.initRows = function() {
        arikaim.ui.button('.edit-post',function(element) {
            var uuid = $(element).attr('uuid');

            return arikaim.page.loadContent({
                id: 'post_content',           
                component: 'blog::admin.posts.edit',
                params: { uuid: uuid}
            });  
        });
    };
}

var posts = new PostsControlPanel();

arikaim.page.onReady(function() {
    posts.init();
    posts.initRows();
});