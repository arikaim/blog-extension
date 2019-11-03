
$(document).ready(function() {
    console.log('ed');

    var editor = new EditorJS({
        holder: 'post',
        autofocus: true,
        placeholder: '...',
        tools: {
            header: {
              class: Header,
              inlineToolbar : true
            },
            //list: List
            // ...
        }
    });
});
