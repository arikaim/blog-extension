/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function BlogPostsView() {
    var self = this;
    
    this.loadEditPost = function(uuid) {
        return arikaim.page.loadContent({
            id: 'details_content',           
            component: 'blog::admin.posts.edit',
            params: { 
                uuid: uuid
            }
        });  
    };

    this.init = function() {
        this.loadMessages('blog::admin.posts');

        paginator.init('items_list',{
            name: 'blog::admin.posts.view.rows',
            params: {
                namespace: 'posts'
            }
        }); 

        arikaim.ui.loadComponentButton('.create-blog-post');
    };

    this.initRows = function() {
        arikaim.ui.loadComponentButton('.post-action');

        $('.status-dropdown').dropdown({
            onChange: function(value) {
                var uuid = $(this).attr('uuid');
                //currency.setStatus(uuid,value);               
            }
        });

        arikaim.ui.button('.delete-post',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(self.getMessage('remove.content'),{ title: title });
            modal.confirmDelete({ 
                title: self.getMessage('remove.title'),
                description: message
            },function() {
                currency.delete(uuid,function(result) {
                    $('#' + uuid).remove();                
                });
            });
        });
    };
}

var blogPostView = new createObject(BlogPostsView,ControlPanelView);

arikaim.component.onLoaded(function() {
    blogPostView.init();
    blogPostView.initRows();
});