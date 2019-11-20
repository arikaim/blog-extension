$(document).ready(function() {
    $('#name').keyup(function() {
        var slug = arikaim.text.createSlug($(this).val());
        $('#slug').html(slug);      
    });
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