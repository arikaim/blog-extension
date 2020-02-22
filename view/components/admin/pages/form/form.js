'use strict';

$(document).ready(function() {   
    // init form
    arikaim.ui.form.addRules("#page_form",{
        inline: false,
        fields: {
            name: {
                identifier: "name",      
                rules: [{ type: "minLength[2]" }]
            }
        }
    });
});