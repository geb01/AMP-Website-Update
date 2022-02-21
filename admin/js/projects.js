$(document).ready(function() {
    var projEditor = $('#proj-editor');
    var editor = new ContextEditor(projEditor, 'projects');

    // enable save buttons when images are uploaded 
    projEditor.on('change', '.uploadable-form>input[type=file]', function(e) {
        editor.enable();
    });

    // create function objects for text prompts
    var editProject = editable('name', 'Edit project name');
    var editDescription = editable('description', 'Edit description');
    var editHeads = editable('heads', 'Edit project heads (separate with slash "/")');

    // add options to context menu
    editor.contextMenu.push(['Upload Image', changeImage]);
    editor.contextMenu.push(['Edit Name of Department', editProject]);
    editor.contextMenu.push(['Edit Description', editDescription]);
    editor.contextMenu.push(['Edit Officers', editHeads]);
    editor.contextMenu.push(['Delete', deleteNode]);
    editor.save.click(onSave);

    // add a new slide
    $('.add', projEditor).click(function() {
        var add = $(this);

        // create a dummy file input first
        var input = $("<input type='file' name='file' />").click();
        var first_creation = true;

        input.change(function() {
            // a picture was uploaded
            if (!first_creation) return;
            first_creation = false;
            var node = $(
                "<div class='node'><div class='legend'>" +
                "<div class='menu-button'></div>" +
                "<img class='img uploadable' name='image'>" +
                "<span name='name'></span>" +
                "<span name='description'></span>" +
                "<span name='heads'></span>" +
                "</div></div>"
            );
            editor.legend = $('.legend', node);
            if (editProject() && editDescription() && editHeads()) {
                // @see uploadable.js
                node.insertBefore(add);
                var form = $("<form class='uploadable-form' enctype='multipart/form-data'></form>");
                form.append(input);
                form.append($("<input type='text' name='upload_dir' value='' />"));
                $('.img', node).after(form);
                input.trigger('change');
            }
        });

    });

    // save configuration to json file
    function finalSave() {
        var result = [];
        editor.root.children('.node').each(function() {
            var node = $(this);
            var legend = node.children('.legend');
            var dict = {};
            dict['image'] = $('[name=image]', legend).attr('src');
            dict['name'] = $('[name=name]', legend).html();
            dict['description'] = $('[name=description]', legend).html();
            var heads = $('[name=heads]', legend).html().split('/');
            dict['heads'] = heads.map(function(e) { return { name: $.trim(e) }; })
            result.push(dict);
        });
        editor.ajax(result);
    }

    // perform save
    function onSave() {
        if ($(this).is('[disabled]')) return;
        if (!confirm('Are you sure you want to save?')) return;
        window.uploadImages(projEditor, 'about/projects', finalSave);
    }

    // add an image uploading feature to context menu
    function changeImage() {
        var img = $('.img', editor.legend);
        img.trigger('upload');
    }

    // a generic function that returns a prompting function object
    function editable(property, promptText) {
        return function() {
            var content = $('[name=' + property + ']', editor.legend);
            var text = prompt(promptText + ':\n', content.html());
            if (text !== null) {
                content.html(text);
                editor.enable();
                return true;
            }
            return false;
        };
    }

    // delete current node
    function deleteNode() {
        editor.node.remove();
        editor.enable();
        return true;
    }

});