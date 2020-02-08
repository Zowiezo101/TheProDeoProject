<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "settings";
    require "layout/layout.php"; 
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

function settings_Helper_Layout() {
    global $dict_Settings;
    global $id;
    global $$id;
    
    if (isset($_SESSION['login'])) {
        // A login is found
        PrettyPrint('<div class="clearfix"> ', 1);
        PrettyPrint('    <div class="contents_left" id="settings_bar"> ');
        PrettyPrint('        <button class="button_'.$$id.'" onclick="ShowNew()">'.$dict_Settings["new_blog"].'</button> ');
        PrettyPrint('        <button class="button_'.$$id.'" onclick="ShowDelete()">'.$dict_Settings["delete_blog"].'</button> ');
        PrettyPrint('        <button class="button_'.$$id.'" onclick="ShowEdit()">'.$dict_Settings["edit_blog"].'</button> ');
        PrettyPrint('        <button class="button_'.$$id.'" onclick="location.href=\'tools/logout.php\'">'.$dict_Settings["logout"].'</button> ');
        PrettyPrint('    </div> ');
        PrettyPrint('');    
        PrettyPrint('    <div class="contents_right" id="settings_content"> ');
        PrettyPrint('        '.$dict_Settings["welcome"]);
        PrettyPrint('        - '.$dict_Settings["new_blog"].'<br>');
        PrettyPrint('        - '.$dict_Settings["delete_blog"].'<br>');
        PrettyPrint('        - '.$dict_Settings["edit_blog"].'<br>');
        PrettyPrint('    </div> ');
        PrettyPrint('</div> ');
    } else {
        // Log in page, in case no login is found yet
        PrettyPrint('<div id="settings_login"> ');
        PrettyPrint('    <form method="post" action="tools/login.php"> ');
                    // User name
        PrettyPrint('        <p>'.$dict_Settings["user"].'</p> ');
        PrettyPrint('        <input type="text" name="user" placeholder="'.$dict_Settings["user"].'"> ');
        PrettyPrint('');        
                    // Password
        PrettyPrint('        <p>'.$dict_Settings["password"].'</p> ');
        PrettyPrint('        <input type="password" name="password" placeholder="'.$dict_Settings["password"].'"> ');
        PrettyPrint('');        
                    // Submit button
        PrettyPrint('        <br> ');
        PrettyPrint('        <input id="submit_form_button" class="button_'.$$id.'" type="submit" name="submitLogin" value="'.$dict_Settings["login"].'"> ');
        PrettyPrint('        <br> ');
        PrettyPrint('    </form> ');
        PrettyPrint('');    
        
        // When the entered data is incorrect
        if (isset($_SESSION["error"])) {
            if ($_SESSION["error"] == true) {
                PrettyPrint("<p>".$dict_Settings["incorrect"]."</p>");
                $_SESSION["error"] = false;
            }
        }
        PrettyPrint('</div> ');
    }
} 
?>

<script>
<!-- This part is only available, when the user is logged in -->
<?php if (isset($_SESSION['login'])) { ?>
    // Add a new blog to the database
    function ShowNew() {
        // This is the title of the right side of the page
        Settings = document.getElementById("settings_content");
        Settings.innerHTML = "<h1><?php echo $dict_Settings["new_blog"]; ?></h1>";

        // A little textbox for the title of a new blog
        titleForm = document.createElement("textarea");
        titleForm.name = "title";
        titleForm.placeholder = "<?php echo $dict_Settings["title"]; ?>";
        titleForm.rows = 1;
        titleForm.required = true;
        
        // Contents of the new blok
        textForm = document.createElement("textarea");
        textForm.name = "text";
        textForm.placeholder = "<?php echo $dict_Settings["text"]; ?>";
        textForm.rows = 10;
        textForm.required = true;
        
        // The submit button
        submitForm = document.createElement("input");
        submitForm.type = "submit";
        submitForm.name = "submitAdd";
        submitForm.value = "<?php echo $dict_Settings["new_blog"]; ?>";
        submitForm.id = "submit_form_button";
        submitForm.className = "button_<?php echo $$id; ?>";
        
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
    
    function ShowDelete() {
        // This is the title of the right side of the page
        Settings = document.getElementById("settings_content");
        Settings.innerHTML = "<h1><?php echo $dict_Settings["delete_blog"]; ?></h1>";

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
        textForm.placeholder = "<?php echo $dict_Settings["text"]; ?>";
        textForm.rows = 10;
        textForm.disabled = true;
        textForm.id = "text";
        
        // Submit button, disabled until a blog is chosen
        submitForm = document.createElement("input");
        submitForm.type = "submit";
        submitForm.name = "submitDelete";
        submitForm.value = "<?php echo $dict_Settings["delete_blog"]; ?>";
        submitForm.id = "submit_form_button";
        submitForm.className = "off_button_<?php echo $$id; ?>";
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
    
    function ShowEdit() {
        // The title of the right side of the page
        Settings = document.getElementById("settings_content");
        Settings.innerHTML = "<h1><?php echo $dict_Settings["edit_blog"]; ?></h1>";

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
        titleForm.placeholder = "<?php echo $dict_Settings["title"]; ?>";
        titleForm.rows = 1;
        titleForm.required = true;
        titleForm.disabled = true;
        titleForm.id = "title";
        
        // Place holder for the text that will be edited
        textForm = document.createElement("textarea");
        textForm.name = "text";
        textForm.placeholder = "<?php echo $dict_Settings["text"]; ?>";
        textForm.rows = 10;
        textForm.required = true;
        textForm.disabled = true;
        textForm.id = "text";
        
        // Submit button, disabled until a blog is chosen
        submitForm = document.createElement("input");
        submitForm.type = "submit";
        submitForm.name = "submitEdit";
        submitForm.value = "<?php echo $dict_Settings["edit_blog"]; ?>";
        submitForm.id = "submit_form_button";
        submitForm.className = "off_button_<?php echo $$id; ?>";
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
        submitForm.className = "button_<?php echo $$id; ?>";
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
        submitForm.className = "button_<?php echo $$id; ?>";
        submitForm.disabled = false;
        titleForm.disabled = false;
        textForm.disabled = false;
    }
<?php } ?>

function Helper_onLoad() {
    <?php if (isset($_POST['submitAdd'])) { ?>
        Settings = document.getElementById("settings_content");
        Settings.innerHTML = "<?php AddBlog($_POST["title"], $_POST["text"], $_SESSION['login']); ?>";
        
        // Reload without resending the action
        oldHref = window.location.href;
        window.location.href = oldHref;
    <?php } if (isset($_POST['submitDelete'])) { ?>
        Settings = document.getElementById("settings_content");
        Settings.innerHTML = "<?php DeleteBlog($_POST["select"]); ?>";
        
        // Reload without resending the action
        oldHref = window.location.href;
        window.location.href = oldHref;
    <?php } if (isset($_POST['submitEdit'])) { ?>
        Settings = document.getElementById("settings_content");
        Settings.innerHTML = "<?php EditBlog($_POST["select"], $_POST["title"], $_POST["text"]); ?>";
        
        // Reload without resending the action
        oldHref = window.location.href;
        window.location.href = oldHref;
    <?php } ?>
}
    
window.onload = Helper_onLoad;
</script>
