$(document).ready(function() {
    $('.status-dropdown').dropdown({
        onChange: function(value) {           
            var uuid = $(this).attr('uuid');         
            blogControlPanel.setPostStatus(uuid,value);
        }       
    });
    
    arikaim.ui.form.addRules("#editor_form",{
        inline: false,
        fields: {
            title: {
                identifier: "title",      
                rules: [{
                    type: "minLength[2]"       
                }]
            }
        }
    });   

    var editor = posts.createEditor();
});