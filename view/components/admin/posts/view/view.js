/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function BlogPostsView() {
    var self = this;

    this.init = function() {
        this.loadMessages('blog::admin.posts.messages');
    
        this.setItemsSelector('post_items');
        this.setItemComponentName('blog::admin.posts.view.row');

        this.initRows();
    };

    this.initRows = function() {
        arikaim.ui.loadComponentButton('.post-action');

        $('.status-dropdown').on('change', function() {
            var val = $(this).val();
            var uuid = $(this).attr('uuid');

            blogApi.setPostStatus(uuid,val);                
        });

        arikaim.ui.button('.delete-post',(element) => {
            var uuid = $(element).attr('uuid');
            
            arikaim.ui.getComponent('post_delete_modal').open(function() {
                blogApi.deletePost(uuid,function(result) {
                    self.deleteItem(result.uuid);
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
    };
}

var blogPostView = new createObject(BlogPostsView,ControlPanelView);

arikaim.component.onLoaded(function() {
    blogPostView.init();
});