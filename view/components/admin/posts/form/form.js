$(document).ready(function() {
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

    var editor = new SimpleMDE({
        autofocus: true,
        autoDownloadFontAwesome: true,
        element: document.getElementById("editor")
    });
});

