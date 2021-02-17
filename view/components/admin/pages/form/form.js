'use strict';

arikaim.component.onLoaded(function() {
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