<?php 
    // Make it easier to copy/paste code or make a new file
    $id = "settings";
    require "layout/template.php"; 
?>

<script>
    function onLoadSettings() {
        // The div to put everything in
        var content = document.getElementById('content');
            
        if (session_settings.hasOwnProperty('login')) {
            // A login is found
            var clearfix = createChild(content, "div", {
                class: "clearfix"
            });
            
            var contents_left = createChild(clearfix, "div", {
                class: "contents_left",
                id: "settings_bar"
            });
            
            createChild(contents_left, "button", {
                class: "button_" + session_settings["theme"],
                onclick: ShowNew,
                innerHTML: dict_Settings["new_blog"]
            });
            
            createChild(contents_left, "button", {
                class: "button_" + session_settings["theme"],
                onclick: ShowDelete,
                innerHTML: dict_Settings["delete_blog"]
            });
            
            createChild(contents_left, "button", {
                class: "button_" + session_settings["theme"],
                onclick: ShowEdit,
                innerHTML: dict_Settings["edit_blog"]
            });
            
            createChild(contents_left, "button", {
                class: "button_" + session_settings["theme"],
                onclick: function() { 
                    location.href='tools/logout.php';
                },
                innerHTML: dict_Settings["logout"]
            });
            
            var contents_right = createChild(clearfix, "div", {
                class: "contents_right",
                id: "settings_content",
                innerHTML: dict_Settings["welcome"] + "<br>" +
                        "- " + dict_Settings["new_blog"] + "<br>" + 
                        "- " + dict_Settings["delete_blog"] + "<br>" + 
                        "- " + dict_Settings["edit_blog"] + "<br>"
            });
        } else {
            // Log-in page, in case no login is found yet

            // The div that has the log-in screen
            var login_div = createChild(content, "div", {
                id: "settings_login"
            });

            // The log-in form
            var login_form = createChild(login_div, "form", {
                method: "post",
                action: "tools/login.php"
            });

            // User name & password
            createChildren(login_form, [
                ["p", {
                    innerHTML: dict_Settings["user"]
                }], 
                ["input", {
                    type: "text",
                    name: "user",
                    placeholder: dict_Settings["user"]
                }], 
                ["p", {
                    innerHTML: dict_Settings["password"]
                }],
                ["input", {
                    type: "password",
                    name: "password",
                    placeholder: dict_Settings["password"]
                }],
            
                // Submit button
                ["br", {}],
                ["input", {
                    id: "submit_form_button",
                    className: "button_" + session_settings["theme"],
                    type: "submit",
                    name: "submitLogin",
                    value: dict_Settings["login"]
                }],
                ["br", {}]
            ]);

            // When the entered data is incorrect
            if (session_settings.hasOwnProperty("error") && (session_settings["error"] === "1")) {
                // Show an error
                createChild(login_div, "p", {
                    innerHTML: dict_Settings["incorrect"]
                });

                // Error has been shown, no need to show it twice
                updateSessionSettings("error", false);
            }
        }

        if (session_settings.hasOwnProperty("submitAdd")) {
            Settings = document.getElementById("settings_content");
            Settings.innerHTML = AddBlog(session_settings["title"], session_settings["text"], session_settings["login"]).then();
            
            // Reload without resending the action
            goToPage("settings.php");
            
        } if (session_settings.hasOwnProperty("submitDelete")) {
            Settings = document.getElementById("settings_content");
            Settings.innerHTML = DeleteBlog(session_settings["selecet"]).then();
            
            // Reload without resending the action
            goToPage("settings.php");
            
        } if (session_settings.hasOwnProperty("submitEdit")) {
            Settings = document.getElementById("settings_content");
            Settings.innerHTML = EditBlog(session_settings["selecet"], session_settings["title"], session_settings["text"]).then();
            
            // Reload without resending the action
            goToPage("settings.php");
        }
    }
</script>
