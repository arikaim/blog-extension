'use strict';

$(document).ready(function() {
    arikaim.ui.form.addRules("#blog_settings",{
        inline: false,
        fields: {
            perPage: {
                identifier: "blog_posts_perpage",      
                rules: [{ type: "minLength[1]" }]
            }         
        }
    });

    arikaim.ui.form.onSubmit("#blog_settings",function() {  
        var perPage = $('#blog_posts_perpage').val();
        
        return options.save('blog.posts.perpage',perPage);
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
    });
});