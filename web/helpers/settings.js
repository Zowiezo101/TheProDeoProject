
/* global dict_Settings, session_settings, getItemFromDatabase */

function CleanText(text) {
    // The newlines in the string cause problems..
    text1 = text.replace(/\r\n|\r|\n|\\r\\n|\\r|\\n/g, "<br/>");
    
    // Escape slashes
    text2 = text1.replace("\\", "\\\\");
    
    // Escape apastrophs
    text3 = text2.replace("'", "\\'");
    
    // Escape quotes
    text4 = text3.replace('"', '\\"');
    
    // Escape quotes
    text5 = text4.replace('`', '\\`');
    
    // Put the \n chars back
    text6 = text5.replace('<br/>', '\n');
    
    return text6;
}

function GetListOfBlogs(parent) {

   
    // Generate the default option in the selection list
    // Default string and option that cannot be chosen.
    // This forces the user the actually chose an option
    createChild(parent, "option", {
        value: '',
        disabled: true,
        selected: true,
        innerHTML: dict_Settings['default']
    });
    
    getItemFromDatabase("blog").then(function(information) {
        for (var itemIdx in information) {
            var blog = information[itemIdx];

            // Add all the blog names to the selection list as individual options
            createChild(parent, "option", {
                value: blog['id'],
                extra_text: CleanText(blog['text']),
                extra_title: CleanText(blog['title']),
                innerHTML: CleanText(blog['title']) + " @" + blog['date']
            });
        }
    }, console.log);
}

// Add a new blog to the database
function ShowNew() {
    if (session_settings.hasOwnProperty("login")) {
        // This is the title of the right side of the page
        Settings = document.getElementById("settings_content");
        Settings.innerHTML = "<h1>" + dict_Settings["new_blog"] + "</h1>";

        // A little textbox for the title of a new blog
        titleForm = document.createElement("textarea");
        titleForm.name = "title";
        titleForm.placeholder = dict_Settings["title"];
        titleForm.rows = 1;
        titleForm.required = true;

        // Contents of the new blok
        textForm = document.createElement("textarea");
        textForm.name = "text";
        textForm.placeholder = dict_Settings["text"];
        textForm.rows = 10;
        textForm.required = true;

        // The submit button
        submitForm = document.createElement("input");
        submitForm.type = "submit";
        submitForm.name = "submit_add";
        submitForm.value = dict_Settings["new_blog"];
        submitForm.id = "submit_form_button";
        submitForm.className = "button_" + session_settings["theme"];

        // Add all these things to the form
        newForm = document.createElement("form");
        newForm.method = "post";
        newForm.action = "";

        newForm.appendChild(titleForm);
        newForm.appendChild(textForm);
        newForm.appendChild(submitForm);

        // Add the form to the page
        Settings.appendChild(newForm);
    }
}

function ShowDelete() {
    if (session_settings.hasOwnProperty("login")) {
        // This is the title of the right side of the page
        Settings = document.getElementById("settings_content");
        Settings.innerHTML = "<h1>" + dict_Settings["delete_blog"] + "</h1>";

        // Make a selection bar
        selectForm = document.createElement("select");
        selectForm.name = "select";
        selectForm.onchange = PreviewRemove;
        selectForm.id = "select";

        // Add all the options to select
        GetListOfBlogs(selectForm);

        // Place holder for the text that will be deleted
        textForm = document.createElement("textarea");
        textForm.name = "text";
        textForm.placeholder = dict_Settings["text"];
        textForm.rows = 10;
        textForm.disabled = true;
        textForm.id = "text";

        // Submit button, disabled until a blog is chosen
        submitForm = document.createElement("input");
        submitForm.type = "submit";
        submitForm.name = "submit_delete";
        submitForm.value = dict_Settings["delete_blog"];
        submitForm.id = "submit_form_button";
        submitForm.className = "off_button_" + session_settings["theme"];
        submitForm.disabled = true;

        // Add all these things to a form
        newForm = document.createElement("form");
        newForm.method = "post";
        newForm.action = "";

        newForm.appendChild(selectForm);
        newForm.appendChild(textForm);
        newForm.appendChild(submitForm);

        // Add the form to the page
        Settings.appendChild(newForm);
    }
}

function ShowEdit() {
    if (session_settings.hasOwnProperty("login")) {
        // The title of the right side of the page
        Settings = document.getElementById("settings_content");
        Settings.innerHTML = "<h1>" + dict_Settings["edit_blog"] + "</h1>";

        // Add a selection bar
        selectForm = document.createElement("select");
        selectForm.name = "select";
        selectForm.onchange = PreviewEdit;
        selectForm.id = "select";

        // The options of the selection bar
        GetListOfBlogs(selectForm);

        // Place holder for the title that will be edited
        titleForm = document.createElement("textarea");
        titleForm.name = "title";
        titleForm.placeholder = dict_Settings["title"];
        titleForm.rows = 1;
        titleForm.required = true;
        titleForm.disabled = true;
        titleForm.id = "title";

        // Place holder for the text that will be edited
        textForm = document.createElement("textarea");
        textForm.name = "text";
        textForm.placeholder = dict_Settings["text"];
        textForm.rows = 10;
        textForm.required = true;
        textForm.disabled = true;
        textForm.id = "text";

        // Submit button, disabled until a blog is chosen
        submitForm = document.createElement("input");
        submitForm.type = "submit";
        submitForm.name = "submit_edit";
        submitForm.value = dict_Settings["edit_blog"];
        submitForm.id = "submit_form_button";
        submitForm.className = "off_button_" + session_settings["theme"];
        submitForm.disabled = true;

        // Add all these things to a form
        newForm = document.createElement("form");
        newForm.method = "post";
        newForm.action = "";

        newForm.appendChild(selectForm);
        newForm.appendChild(titleForm);
        newForm.appendChild(textForm);
        newForm.appendChild(submitForm);

        // Add the form to the page
        Settings.appendChild(newForm);
    }
}

function PreviewRemove() {
    // Get the text that needs to be visualised
    var select = document.getElementById("select");
    var selected = select.options[select.selectedIndex];
    var text = selected.extra_text;

    // The box with the text
    var textForm = document.getElementById("text");
    textForm.value = text;

    // Now enable the submit button
    var submitForm = document.getElementById("submit_form_button");
    submitForm.className = "button_" + session_settings["theme"];
    submitForm.disabled = false;
}

function PreviewEdit() {
    // Get the text that needs to be visualised
    var select = document.getElementById("select");
    var selected = select.options[select.selectedIndex];
    var title = selected.extra_title;
    var text = selected.extra_text;

    var titleForm = document.getElementById("title");
    var textForm = document.getElementById("text");

    // Update the default text to make updates easier..
    titleForm.value = title;
    textForm.value = text;

    // Now enable the submit button and the textareas
    var submitForm = document.getElementById("submit_form_button");
    submitForm.className = "button_" + session_settings["theme"];
    submitForm.disabled = false;
    titleForm.disabled = false;
    textForm.disabled = false;
}