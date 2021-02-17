'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.addRules("#blog_settings",{});

    arikaim.ui.form.onSubmit("#blog_settings",function() {  
        var perPage = $('#blog_posts_perpage').val();
        
        return options.save('blog.posts.perpage',perPage);
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
    });
});