/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function PostsControlPanel() {
    var self = this;

    this.init = function() {     
        arikaim.ui.button('.view-posts',function(element) {
            var pageId = $(element).attr('page-id');

            return arikaim.page.loadContent({
                id: 'post_content',           
                component: 'blog::admin.posts.list',
                params: { page: pageId }
            });  
        });

        arikaim.ui.button('.add-post',function(element) {
            var pageId = $(element).attr('page-id');

            return arikaim.page.loadContent({
                id: 'post_content',           
                component: 'blog::admin.posts.add',
                params: { page: pageId }
            });  
        });

        arikaim.ui.button('.edit-page-name',function(element) {
            var pageId = $(element).attr('page-id');

            return arikaim.page.loadContent({
                id: 'post_content',           
                component: 'blog::admin.pages.edit',
                params: { page: pageId }
            });  
        });
    };

    this.loadEditPost = function(uuid, pageId) {
        return arikaim.page.loadContent({
            id: 'post_content',           
            component: 'blog::admin.posts.edit',
            params: { 
                uuid: uuid,
                page_id: pageId 
            }
        });  
    };

    this.initRows = function() {
        var component = arikaim.component.get('blog::admin');
        var removeMessage = component.getProperty('messages.remove.content');

        arikaim.ui.button('.edit-post',function(element) {
            var uuid = $(element).attr('uuid');
            var pageId = $(element).attr('page-id');

            return self.loadEditPost(uuid,pageId);
        });

        arikaim.ui.button('.delete-post',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(removeMessage,{ title: title });
            modal.confirmDelete({ 
                title: component.getProperty('messages.remove.title'),
                description: message
            },function() {
                blogControlPanel.deletePost(uuid,function(result) {
                    $('#' + uuid).remove();                              
                });
            });
        });
    };

    this.createEditor = function() {
        return new SimpleMDE({
            autofocus: true,
            autoDownloadFontAwesome: true,
            forceSync: true,
            element: document.getElementById("editor")
        });
    }
}

var posts = new PostsControlPanel();

arikaim.page.onReady(function() {
    posts.init();
    posts.initRows();
});