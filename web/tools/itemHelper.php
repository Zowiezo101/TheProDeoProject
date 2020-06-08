<?php

// These extra libraries are needed for the list of timelines and the list of family trees
if ($id == "peoples") {
    require "familytree.php";
    require "tools/mapHelper.php";
} else if ($id == "events") {
    require "timeline.php";
    require "tools/mapHelper.php";
} 

// A function to add parameters to links
function AddParams($page, $id, $sort) {
    $return_val = "";
        
    // If values are not defined, define them now
    // Use the default value, if they are not in the address bar
    if ($page == -1) {
        if (null !== filter_input(INPUT_GET, "page")) {
            $page = filter_input(INPUT_GET, "page");
        } else {
            $page = 0;
        }
    }
        
    if ($sort == -1) {
        if (null !== filter_input(INPUT_GET, "sort")) {
            $sort = filter_input(INPUT_GET, "sort");
        } else {
            $sort = "app";
        }
    }
    
    if ($page != 0) {
        $return_val = "?page=".$page."&id=".$id;
    } else {
        $return_val = "?id=".$id;
    } 
    
    if ($sort != "app") {
        $return_val = $return_val."&sort=".$sort;
    }
        
    return $return_val;
}

// This function creates a table with one page of item results.
// One page contains 100 items in a table.
function GetListOfItems($table) {
    global $dict_Search;
    global $conn;
    
    // Check the page number. If it isn't defined, just
    // use the default value of 0.
    if (null === filter_input(INPUT_GET, "page")) {
        $page_nr = 0;
    } else {
        $page_nr = filter_input(INPUT_GET, "page");
    }
    
    // Check if the results should be sorted.
    if (null === filter_input(INPUT_GET, "sort")) {
        $sort = "app";
    } else {
        $sort = filter_input(INPUT_GET, "sort");
    }
            
    // Sorting results by name or ID.
    switch($sort) {
        case 'alp':
        // Get new SQL array of items
        $sortBy = 'name ASC';
        break;
        
        case 'r-alp':
        // Get new SQL array of items
        $sortBy = 'name DESC';
        break;
        
        case 'r-app':
        // Get new SQL array of items
        $sortBy = substr($table, 0, -1).'_id DESC';
        break;
        
        default:
        // Get new SQL array of items
        $sortBy = substr($table, 0, -1).'_id ASC';
    }
    
    // Getting the query ready
    $sql = "SELECT ".substr($table, 0, -1)."_id, name FROM ".$table." ORDER BY ".$sortBy." LIMIT ".($page_nr*100).",".(($page_nr+1)*100);
    $result = $conn->query($sql);
    
    // If there are no results
    if (!$result) {
        PrettyPrint($dict_Search["NoResults"]);
    } elseif($result->num_rows == 0) {
        PrettyPrint($dict_Search["NoResults"]);
    } else {
        // If there are results, create the table with the results
        PrettyPrint("            <table>");
        while ($name = $result->fetch_array()) {
            PrettyPrint("                <tr>");
            PrettyPrint("                    <td>");
            PrettyPrint("                        <button onclick='saveScroll(\"".$table.".php".AddParams($page_nr, $name[substr($table, 0, -1).'_id'], $sort)."\")'>".$name['name']."</button>");
            PrettyPrint("                    </td>");
            PrettyPrint("                </tr>");
            PrettyPrint("");
        }
        PrettyPrint("            </table>");
    }
}

// Get the numbers of items that are stored in a table for a certain page
// This is to see if it was the last page
function GetNumberOfItems($table) {
    global $conn;
    
    // Check if the page number is set
    if (null === filter_input(INPUT_GET, "page")) {
        $page_nr = 0;
    } else {
        $page_nr = filter_input(INPUT_GET, "page");
    }
    
    // The query to run
    $sql = "SELECT ".substr($table, 0, -1)."_id, name FROM ".$table." WHERE ".substr($table, 0, -1)."_id >= ".($page_nr*100)." LIMIT 101";
    $result = $conn->query($sql);
    
    if (!$result) {
        return 0;
    }
    
    // Return the results
    return $result->num_rows;
}

// Get the information for a single item
function GetItemInfo($table, $ID, $column_name="") {
    global $dict_Search;
    global $conn;
    
    if ($column_name == "") {
        $column_name = substr($table, 0, -1)."_id";
    }
    
    // The query to run
    $sql = "SELECT * FROM ".$table." WHERE ".$column_name." = ".$ID;    
    $result = $conn->query($sql);
    $item = NULL;
    
    // No results
    if (!$result) {
        $Error = array("Name" => $dict_Search["NoResults"]);
        return $Error;
    }
    else {
        // If there are results, put them in a dictionary
        $item = $result->fetch_assoc();
    }
    
    return $item;
}

?>

<script>
    function _Database_Helper_onLoad() {
        var ButtonPrev = document.getElementById("button_left");
        var ButtonNext = document.getElementById("button_right");
        var ButtonApp = document.getElementById("button_app");
        var ButtonAlp = document.getElementById("button_alp");
        
        // Check if this is page 0. If so, disable to prev button..    
        <?php            
            if (null === filter_input(INPUT_GET, "page")) {
                $page_nr = 0;
            } else {
                $page_nr = filter_input(INPUT_GET, "page");
            }
            
            if (null === filter_input(INPUT_GET, "sort")) {
                $sort = 'app';
            } else {
                $sort = filter_input(INPUT_GET, "sort");
            }
            
            PrettyPrint("var PageNr = ".$page_nr.";", 1);
            PrettyPrint("var NrOfItems = ".GetNumberOfItems($id).";");
            PrettyPrint("var SortType = '".$sort."';");
        ?>
        
        if (PageNr === 0) {
            // First page
            ButtonPrev.disabled = true;
            ButtonPrev.className = "off_button_<?php echo $$id; ?>";
        } else {
            // Not the first page
            ButtonPrev.disabled = false;
            ButtonPrev.className = "button_<?php echo $$id; ?>";
        }
        if (NrOfItems < 101) {
            // Last page
            ButtonNext.disabled = true;
            ButtonNext.className = "off_button_<?php echo $$id; ?>";
        } else {
            // Not the last page
            ButtonNext.disabled = false;
            ButtonNext.className = "button_<?php echo $$id; ?>";
        }
        
        switch (SortType) {
            case "app":
                    ButtonApp.className = "sort_9_1";
                    ButtonAlp.className = "sort_a_z";
                break;
            
            case "r-app":
                    ButtonApp.className = "sort_1_9";
                    ButtonAlp.className = "sort_a_z";
                break;
                
            case "alp":
                    ButtonApp.className = "sort_1_9";
                    ButtonAlp.className = "sort_z_a";
                break;
                
            case "r-alp":
                    ButtonApp.className = "sort_1_9";
                    ButtonAlp.className = "sort_a_z";
                break;
            
            default:
                break;
        }

        // Set the height of the left div, to the height of the right div
        var ContentsR = document.getElementsByClassName("contents_right")[0];
        var ContentsL = document.getElementsByClassName("contents_left")[0];
        
        ContentsL.setAttribute("style", "height: " + ContentsR.offsetHeight + "px");
        
        loadScroll();
    }
    
    function PrevPage() {        
        <?php
            if (null === filter_input(INPUT_GET, "page")) {
                $page_nr = 0;
            } else {
                $page_nr = filter_input(INPUT_GET, "page");
            }
            
            PrettyPrint("var PageNr = ".$page_nr.";", 1);
        ?>
        
        if (PageNr === 1) {
            // The page parameter should now be removed
            oldHref = window.location.href;
            newHref = removeURLParameter(oldHref, "page");
            window.location.href = newHref;
        } else if (PageNr > 1) {
            // The page parameter only has to be updated
            oldHref = window.location.href;
            newHref = updateURLParameter(oldHref, "page", PageNr - 1);
            window.location.href = newHref;
        }
    }
    
    function NextPage() {
        <?php
            if (null === filter_input(INPUT_GET, "page")) {
                $page_nr = 0;
            } else {
                $page_nr = filter_input(INPUT_GET, "page");
            }
            
            PrettyPrint("var PageNr = ".$page_nr." + 1;", 1);
        ?>
        
        oldHref = window.location.href;
        newHref = updateURLParameter(oldHref, "page", PageNr);
        window.location.href = newHref;
    }
    
    function SortOnAlphabet() {        
        Button = document.getElementById("button_alp");
    
        // The sort parameter only has to be updated
        oldHref = window.location.href;
        <?php if ((null !== filter_input(INPUT_GET, "sort")) && (filter_input(INPUT_GET, "sort")) == "alp") { ?>
            newHref = updateURLParameter(oldHref, "sort", "r-alp");
        <?php } else { ?>
            newHref = updateURLParameter(oldHref, "sort", "alp");
        <?php } ?>
        
        newHref = removeURLParameter(newHref, "page");
        window.location.href = newHref;
                
        return;
    }
    
    function SortOnAppearance() {
        Button = document.getElementById("button_app");
    
        // The sort parameter only has to be updated
        oldHref = window.location.href;
        <?php if (null === filter_input(INPUT_GET, "sort")) { ?>
            newHref = updateURLParameter(oldHref, "sort", "r-app");
        <?php } else { ?>
            newHref = removeURLParameter(oldHref, "sort");
        <?php } ?>
        
        newHref = removeURLParameter(newHref, "page");
        window.location.href = newHref;
                
        return;
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
        text = "";
        if (bookName !== "") {
            text = bookName + " " + chapIdx + ":" + verseIdx;
        }
        return text;
    }
</script>