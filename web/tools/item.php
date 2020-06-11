<script>
        
    async function PrevPage() {
        // Get the stored page number
        // If there is no page number, we are already at the first page and don't need to go further back
        if (session_settings.hasOwnProperty("page")) {
            var page = parseInt(session_settings["page"], 10);
            
            if (page - 1 === 0) {
                // Going a page back means going to the first page
                // Remove the page property
                page = "";
            } else {
                page = page - 1;
            }
            
            // Show the new information
            await updateSessionSettings("page", page).then(async function () {
                    updateButtonLeft();
                    updateButtonRight();
                    await getItemFromDatabase(session_settings["table"], 
                                              "", 
                                              "", 
                                              page ? page : 0, 
                                              getSortSql(session_settings["sort"])).then(showItemList, console.log);
                }, console.log
            );
        }
    }
    
    async function NextPage() {
        // Get the stored page number
        if (session_settings.hasOwnProperty("page")) {
            var page = parseInt(session_settings["page"], 10);
        } else {
            // No page given, means that we are at the first page
            page = 0;
        }
            
        // Show the new information
        await updateSessionSettings("page", page + 1).then(async function () {
                updateButtonLeft();
                updateButtonRight();
                await getItemFromDatabase(session_settings["table"], 
                                          "", 
                                          "", 
                                          page + 1, 
                                          getSortSql(session_settings["sort"])).then(showItemList, console.log);
            }, console.log
        );
    }
    
    async function SortOnAppearance() {
        // Get the stored page number
        if (session_settings.hasOwnProperty("sort")) {
            var sort = session_settings["sort"];
        } else {
            // No sort given, means that we have default sort
            sort = "app";
        }
        // New sort setting
        sort = (sort === "app") ? "r-app" : "app";
            
        // Show the new information
        await updateSessionSettings("sort", sort).then(async function () {
            await updateSessionSettings("page", "").then(async function () {
                updateButtonAlp();
                updateButtonApp();
                await getItemFromDatabase(session_settings["table"], 
                                          "", 
                                          "", 
                                          0, 
                                          getSortSql(sort)).then(showItemList, console.log);
            }, console.log);
        }, console.log);
    }
    
    async function SortOnAlphabet() {
        // Get the stored page number
        if (session_settings.hasOwnProperty("sort")) {
            var sort = session_settings["sort"];
        } else {
            // No sort given, means that we have default sort
            sort = "app";
        }
        // New sort setting
        sort = (sort === "alp") ? "r-alp" : "alp";
            
        // Show the new information
        await updateSessionSettings("sort", sort).then(async function () {
            await updateSessionSettings("page", "").then(async function () {
                updateButtonAlp();
                updateButtonApp();
                await getItemFromDatabase(session_settings["table"], 
                                          "", 
                                          "", 
                                          0, 
                                          getSortSql(sort)).then(showItemList, console.log);
            }, console.log);
        }, console.log);
    }
    
    function setButtonLeft(parent) {
        // Previous page
        var buttonLeft = document.createElement("button");
        parent.appendChild(buttonLeft);
        
        // Set its attributes
        buttonLeft.id = "button_left";
        buttonLeft.onclick = PrevPage;
        buttonLeft.innerHTML = "←";
        
        updateButtonLeft();
    }
    
    function updateButtonLeft() {
        var buttonLeft = document.getElementById("button_left");
        
        if (session_settings.hasOwnProperty("page")) {
            var page = session_settings["page"];
        } else {
            page = 0;
        }
        
        buttonLeft.disabled = (page === 0) ? true : false;
        buttonLeft.className = ((page === 0) ? "off_" : "") + "button_" + session_settings["theme"];
    }
    
    function setButtonApp(parent) {
        // Sort on Apperance
        var buttonApp = document.createElement("button");
        parent.appendChild(buttonApp);
        
        // Set its attributes
        buttonApp.id = "button_app";
        buttonApp.onclick = SortOnAppearance;
        
        updateButtonApp();
    }
    
    function updateButtonApp() {
        var buttonApp = document.getElementById("button_app");
        
        if (session_settings.hasOwnProperty("sort")) {
            var sort = session_settings["sort"];
        } else {
            sort = "app";
        }
        
        buttonApp.className = (sort === "app") ? "sort_9_1" : "sort_1_9";   
    }
    
    function setButtonAlp(parent) {
        // Sort on Alphabet
        var buttonAlp = document.createElement("button");
        parent.appendChild(buttonAlp);
        
        // Set its attributes
        buttonAlp.id = "button_alp";
        buttonAlp.onclick = SortOnAlphabet;
        
        updateButtonAlp();
    }
    
    function updateButtonAlp() {
        var buttonAlp = document.getElementById("button_alp");
        
        if (session_settings.hasOwnProperty("sort")) {
            var sort = session_settings["sort"];
        } else {
            sort = "app";
        }
        
        buttonAlp.className = (sort === "alp") ? "sort_z_a" : "sort_a_z";   
    }
    
    function setButtonRight(parent) {
        // Next page
        var buttonRight = document.createElement("button");
        parent.appendChild(buttonRight);
        
        // Set its attributes
        buttonRight.id = "button_right";
        buttonRight.onclick = NextPage;
        buttonRight.innerHTML = "→";
        
        updateButtonRight();
    }
    
    function updateButtonRight() {
        var buttonRight = document.getElementById("button_right");
        
        // Check if this is the last page. If so, disable next button.
        // TODO: 
        <?php        
            PrettyPrint("var NrOfItems = ".GetNumberOfItems($id).";");
        ?>
        buttonRight.disabled = (NrOfItems < 101) ? true : false;
        buttonRight.className = ((NrOfItems < 101) ? "off_" : "") + "button_" + session_settings["theme"];
        
    }

    // TODO: When more than one language is available, 
    // use convertBibleVerseLinkDEF, convertBibleVerseLinkEN functions 
    function convertBibleVerseLink(bookName, bookIdx, chapIdx, verseIdx) {

        // Convert the text to UTF for the dutch website to understand
        // Local and hosted websites use different encoding..
        // TODO: Could go wrong on webhostapp
    //    if (mb_detect_encoding(bookTXT['name']) == "UTF-8") {
            // Already UTF-8
            var bookUTF = bookName;
    //    } else {
    //        var bookUTF = iconv("ISO-8859-1", "UTF-8", bookTXT['name']);
    //    }

        // The first part of the webpage to refer to
        var weblink = "<?php echo $dict_Footer['DB_website']; ?>" + bookUTF + "/" + chapIdx;

        var bookAbv = ["GEN", "EXO", "LEV", "NUM", "DEU",
                       "JOS", "JDG", "RUT", "1SA", "2SA",
                       "1KI", "2KI", "1CH", "2CH", "EZR",
                       "NEH", "EST", "JOB", "PSA", "PRO",
                       "ECC", "SNG", "ISA", "JER", "LAM",
                       "EZK", "DAN", "HOS", "JOL", "AMO",
                       "OBA", "JON", "MIC", "NAM", "HAB",
                       "ZEP", "HAG", "ZEC", "MAL", "MAT",
                       "MRK", "LUK", "JHN", "ACT", "ROM",
                       "1CO", "2CO", "GAL", "EPH", "PHP",
                       "COL", "1TH", "2TH", "1TI", "2TI",
                       "TIT", "PHM", "HEB", "JAS", "1PE",
                       "2PE", "1JN", "2JN", "3JN", "JUD",
                       "REV"];

        // Pad the chapter to get 3 digits
        var chapStr = "000" + chapIdx.toString();
        var chapPadded = chapStr.substr(chapStr.length - 3);
        
        // Pad the verse to get 3 digits
        var verseStr = "000" + verseIdx.toString();
        var versePadded = verseStr.substr(verseStr.length - 3);
        
        // Link to a certain part of the webpage, to get the exact verse mentioned
        var weblink2 = "#" + bookAbv[bookIdx - 1] + "-" + chapPadded + "-" + versePadded;

        return weblink + weblink2;
    }

    function convertBibleVerseText(bookName, chapIdx, verseIdx) {
        var text = "";
        if (bookName !== "") {
            text = bookName + " " + chapIdx + ":" + verseIdx;
        }
        return text;
    }
    
</script>

