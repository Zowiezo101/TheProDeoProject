<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "settings";
    require "layout/template.php"; 
?>
<?php 

function CleanText($text, $convertBR = 0) {
    // The newlines in the string cause problems..
    $text1 = str_replace(array("\r\n","\r","\n","\\r\\n","\\r","\\n"), "<br/>", $text);
    
    // Escape slashes
    $text2 = str_replace("\\", "\\\\", $text1);
    
    // Escape apastrophs
    $text3 = str_replace("'", "\\'", $text2);
    
    // Escape quotes
    $text4 = str_replace('"', '\\"', $text3);
    
    if ($convertBR == 1) {
        // Put the \n chars back
        $text5 = str_replace('<br/>', '\n', $text4);
    } else {
        $text5 = $text4;
    }
    
    return $text5;
}

function AddBlog($title, $text, $user) {
    global $dict_Settings;
    global $conn;
    
    // The query to run
    $sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
    $result = $conn->query($sql);

    if (!$result) {
        // Display an error if anything failed
        echo "<h1>SQL: ".$conn->error."</h1>";
    } else {        
        // Get the current date
        date_default_timezone_set('Europe/Amsterdam');
        $date = date("Y-m-d H:i:s a"); 
        
        // Insert the new added blog into the database
        $sql = "INSERT INTO blog (title, text, user, date) VALUES ('".CleanText($title)."','".CleanText($text)."','".$user."','".$date."')";
        $result = $conn->query($sql);    
        
        // Give some indication of the result
        if (!$result) {
            echo "<h1>SQL: ".$conn->error."</h1>";
        } else {
            echo "<h1>".$dict_Settings["blog_added"]."</h1>";
        }
    }    
}

function DeleteBlog($id) {
    global $dict_Settings;
    global $conn;
    
    // The query to run
    $sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
    $result = $conn->query($sql);

    if (!$result) {
        // Display an error if anything went wrong
        echo "<h1>SQL: ".$conn->error."</h1>";
    } else {        
        // Delete the corresponding blog
        $sql = "DELETE FROM blog WHERE id=".$id;
        $result = $conn->query($sql);
        
        // Show the results
        if (!$result) {
            echo "<h1>SQL: ".$conn->error."</h1>";
        } else {        
            echo "<h1>".$dict_Settings["blog_removed"]."</h1>";
        }
    }    
}

function EditBlog($id, $title, $text) {
    global $dict_Settings;
    global $conn;
    
    // The query to run
    $sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
    $result = $conn->query($sql);

    if (!$result) {
        // Show an error if the query failed
        echo "<h1>SQL: ".$conn->error."</h1>";
    } else {        
        // Update the corresponding blog in the database
        $sql = "UPDATE blog SET title='".CleanText($title)."', text='".CleanText($text)."' WHERE id=".$id;
        $result = $conn->query($sql);
    
        // Show an indication of the results
        if (!$result) {
            echo "<h1>SQL: ".$conn->error."</h1>";
        } else {        
            echo "<h1>".$dict_Settings["blog_edited"]."</h1>";
        }
    }    
}

function GetListOfBlogs() {
    global $dict_Settings;
    global $conn;
    
    // The query to run
    $sql = "CREATE TABLE IF NOT EXISTS blog (id INT AUTO_INCREMENT, title VARCHAR(255), text TEXT, user VARCHAR(255), date VARCHAR(255), PRIMARY KEY(id))";
    $result = $conn->query($sql);

    if (!$result) {
        // Show an error if the query failed
        echo "<h1>SQL: ".$conn->error."</h1>";
    } else {    
        // Get all the blogs in the database
        $sql = "SELECT * FROM blog";
        $result = $conn->query($sql);
    
        // Show an error if anything failed
        if (!$result) {
            echo "<h1>SQL: ".$conn->error."</h1>";
        } else {
            // Generate the default option in the selection list
            $newOption = "optionForm = document.createElement('option');";
            $addOption = "selectForm.appendChild(optionForm);";
            
            // Default string and option that cannot be chosen.
            // This forces the user the actually chose an option
            echo $newOption;
            echo "optionForm.value = '';";
            echo "optionForm.disabled = true;";
            echo "optionForm.selected = true;";
            echo "optionForm.innerHTML = '".$dict_Settings['default']."';";
            echo $addOption;
            
            while ($blog = $result->fetch_array()) {    
                // Add all the blog names to the selection list as individual options
                echo $newOption;
                echo "optionForm.value = '".$blog['id']."';";
                echo "optionForm.innerHTML = '".CleanText($blog['title'], 1)." @".$blog['date']."';";
                echo "optionForm.extra_text = '".CleanText($blog['text'], 1)."';";
                echo "optionForm.extra_title = '".CleanText($blog['title'], 1)."';";
                echo $addOption;
            }
        }    
    }
}
?>

<script>
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
            submitForm.name = "submitAdd";
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
            <?php GetListOfBlogs(); ?>

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
            submitForm.name = "submitDelete";
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
            <?php GetListOfBlogs(); ?>

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
            submitForm.name = "submitEdit";
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

    function onLoadSettings() {
        if (session_settings.hasOwnProperty('login')) {
            // A login is found
    //        PrettyPrint('<div class="clearfix"> ', 1);
    //        PrettyPrint('    <div class="contents_left" id="settings_bar"> ');
    //        PrettyPrint('        <button class="button_'.$$id.'" onclick="ShowNew()">'.$dict_Settings["new_blog"].'</button> ');
    //        PrettyPrint('        <button class="button_'.$$id.'" onclick="ShowDelete()">'.$dict_Settings["delete_blog"].'</button> ');
    //        PrettyPrint('        <button class="button_'.$$id.'" onclick="ShowEdit()">'.$dict_Settings["edit_blog"].'</button> ');
    //        PrettyPrint('        <button class="button_'.$$id.'" onclick="location.href=\'tools/logout.php\'">'.$dict_Settings["logout"].'</button> ');
    //        PrettyPrint('    </div> ');
    //        PrettyPrint('');    
    //        PrettyPrint('    <div class="contents_right" id="settings_content"> ');
    //        PrettyPrint('        '.$dict_Settings["welcome"]);
    //        PrettyPrint('        - '.$dict_Settings["new_blog"].'<br>');
    //        PrettyPrint('        - '.$dict_Settings["delete_blog"].'<br>');
    //        PrettyPrint('        - '.$dict_Settings["edit_blog"].'<br>');
    //        PrettyPrint('    </div> ');
    //        PrettyPrint('</div> ');
        } else {
            // Log in page, in case no login is found yet
            // The div to put everything in
            var content = document.getElementById('content');

            // The div that has the log in screen
            var login_div = document.createElement('div');
            content.appendChild(login_div);

            // Set the attributes
            login_div.id = "settings_login";

            // The log in form
            var login_form = document.createElement('form');
            login_div.appendChild(login_form);

            // Set the attributes
            login_form.method = "post";
            login_form.action = "tools/login.php";

            // User name
            var user_text = document.createElement("p");
            login_form.appendChild(user_text);

            // Set the attributes
            user_text.innerHTML = dict_Settings["user"];

            var user_input = document.createElement("input");
            login_form.appendChild(user_input);

            // Set the attributes
            user_input.type = "text";
            user_input.name = "user";
            user_input.placeholder = dict_Settings["user"];

            // Password
            var pass_text = document.createElement("p");
            login_form.appendChild(pass_text);

            // Set the attributes
            pass_text.innerHTML = dict_Settings["password"];

            var pass_input = document.createElement("input");
            login_form.appendChild(pass_input);

            // Set the attributes
            pass_input.type = "password";
            pass_input.name = "password";
            pass_input.placeholder = dict_Settings["password"];

            login_form.appendChild(document.createElement("br"));

            // Submit button
            var submit_input = document.createElement("input");
            login_form.appendChild(submit_input);

            // Set the attributes
            submit_input.id = "submit_form_button";
            submit_input.className = "button_" + session_settings["theme"];
            submit_input.type = "submit";
            submit_input.name = "submitLogin";
            submit_input.value = dict_Settings["login"];

            login_form.appendChild(document.createElement("br"));

            // When the entered data is incorrect
            if (session_settings.hasOwnProperty("error") && (session_settings["error"] === "1")) {
                // Show an error
                var error = document.createElement("p");
                login_div.appendChild(error);

                // Set the attributes
                error.innerHTML = dict_Settings["incorrect"];

                // Error has been shown, no need to show it twice
                updateSessionSettings("error", false);
            }
        }

        <?php if (null !== filter_input(INPUT_POST, 'submitAdd')) { ?>
    //        Settings = document.getElementById("settings_content");
    //        Settings.innerHTML = "<?php AddBlog(filter_input(INPUT_POST, "title"), filter_input(INPUT_POST, "text"), $_SESSION['login']); ?>";
    //        
    //        // Reload without resending the action
    //        oldHref = window.location.href;
    //        window.location.href = oldHref;
        <?php } if (null !== filter_input(INPUT_POST, 'submitDelete')) { ?>
    //        Settings = document.getElementById("settings_content");
    //        Settings.innerHTML = "<?php DeleteBlog(filter_input(INPUT_POST, "select")); ?>";
    //        
    //        // Reload without resending the action
    //        oldHref = window.location.href;
    //        window.location.href = oldHref;
        <?php } if (null !== filter_input(INPUT_POST, 'submitEdit')) { ?>
    //        Settings = document.getElementById("settings_content");
    //        Settings.innerHTML = "<?php EditBlog(filter_input(INPUT_POST, "select"), filter_input(INPUT_POST, "title"), filter_input(INPUT_POST, "text")); ?>";
    //        
    //        // Reload without resending the action
    //        oldHref = window.location.href;
    //        window.location.href = oldHref;
        <?php } ?>
    }
</script>
