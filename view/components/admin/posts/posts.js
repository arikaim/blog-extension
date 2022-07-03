/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function PostsControlPanel() {
    var self = this;

    this.init = function() {     
        this.loadMessages('blog::admin');

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
            var uuid = $(element).attr('uuid');

            return arikaim.page.loadContent({
                id: 'post_content',           
                component: 'blog::admin.pages.edit',
                params: { uuid: uuid }
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
        arikaim.ui.button('.edit-post',function(element) {
            var uuid = $(element).attr('uuid');
            var pageId = $(element).attr('page-id');

            return self.loadEditPost(uuid,pageId);
        });

        arikaim.ui.button('.delete-post',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(self.getMessage('remove.content'),{ title: title });
            modal.confirmDelete({ 
                title: self.getMessage('remove.title'),
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

var posts = createObject(PostsControlPanel,ControlPanelView);

arikaim.component.onLoaded(function() {
    posts.init();
    posts.initRows();
});