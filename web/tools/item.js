/* global dict_Footer, select_Search_gender, dict_PeoplesParams, session_settings, select_Search_location */

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
    var weblink = dict_Footer['db_website'] + bookUTF + "/" + chapIdx;

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

function getSortSql(sortStr) {
    var sortSql = "";

    // Sorting results by name or ID.
    switch(sortStr) {
        case 'alp':
            // Get new SQL array of items
            sortSql = 'name asc';
            break;

        case 'r-alp':
            // Get new SQL array of items
            sortSql = 'name desc';
            break;

        case 'r-app':
            if (session_settings["table"] !== "books") {
                // Get new SQL array of items
                sortSql = 'book_start_id desc, book_start_chap desc, book_start_vers desc';
            } else {
                sortSql = 'book_id desc';
            }
            break;

        default:
            if (session_settings["table"] !== "books") {
                // Get new SQL array of items
                sortSql = 'book_start_id asc, book_start_chap asc, book_start_vers asc';
            } else {
                sortSql = 'book_id asc';
            }
            break;
    }

    return sortSql;
}

function getGenderNoun(genderInt, parentNoun) {
    var genderStr = "";
    
    switch(genderInt) {
        case "-1":
        case -1:
        case "0":
        case 0:
            if ((parentNoun === "parent_id") || (parentNoun === "people_id") || 
                    (parentNoun === "people1_id") || (parentNoun === "people2_id")) {
                genderStr = "";
            } else {
                genderStr = select_Search_gender.unknown;
            }
            break;
            
        case "1":
        case 1:
            if (parentNoun === "parent_id") {
                genderStr = " (" + dict_PeoplesParams.father_id + ")";
            } else if (parentNoun === "people_id") {
                genderStr = " (" + dict_PeoplesParams.son + ")";
            } else if ((parentNoun === "people1_id") || (parentNoun === "people2_id")) {
                genderStr = "";
            } else {
                genderStr = select_Search_gender.male;
            }
            break;
            
        case "2":
        case 2:
            if (parentNoun === "parent_id") {
                genderStr = " (" + dict_PeoplesParams.mother_id + ")";
            } else if (parentNoun === "people_id") {
                genderStr = " (" + dict_PeoplesParams.daughter + ")";
            } else if ((parentNoun === "people1_id") || (parentNoun === "people2_id")) {
                genderStr = "";
            } else {
                genderStr = select_Search_gender.female;
            }
            break;
    }
    
    return genderStr;
}

function getTypeNoun(typeInt) {
    var typeStr = "";
    
    switch(typeInt) {
        case "-1":
        case -1:
            typeStr = "";
            break;
            
        case "0":
        case 0:
            typeStr = " (" + select_Search_location.birth + ")";
            break;
            
        case "1":
        case 1:
            typeStr = " (" + select_Search_location.living + ")";
            break;
    
        case "2":
        case 2:
            typeStr = " (" + select_Search_location.death + ")";
            break;
            
        case "3":
        case 3:
            typeStr = " (" + select_Search_location.founder + ")";
            break;
            
        case "4":
        case 5:
            typeStr = " (" + select_Search_location.destroyer + ")";
            break;
    }
    
    return typeStr;
}