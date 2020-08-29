/* global dict_Search, dict_Settings */

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
    
    return text5;
}

function getItemFromDatabase(table="", value="", column="", page="", sort="") {

   var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/item_read.php';
        var params = '';

        if (table === "blog") {
            // Use a different link for blogs
            link = 'http://localhost/web/api/blog_read.php';
            table = "";
        } else if (table !== "") {
            params = '?table=' + table;
        }

        if ((value !== "") && (typeof value === "string")) {
            params += (params !== "" ? '&' : '?') + 'value=' + value;
        } else if ((value !== "") && (value !== null)) {
            var link = 'http://localhost/web/api/items_read.php';
            params += (params !== "" ? '&' : '?') + 'value=' + "(" + value.join(',') + ")";
        }

        if (column !== "") {
            params += (params !== "" ? '&' : '?') + 'column=' + column;
        }

        if (page !== "") {
            params += (params !== "" ? '&' : '?') + 'offset=' + parseInt(page)*100;
        }

        if (sort !== "") {
            params += (sort !== "" ? '&' : '?') + 'sort=' + sort;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    if (result.hasOwnProperty("data") && (result.data !== null)) {
                        resolve(result.data);
                    } else {
                        // TODO: This need to be handled elsewhere
                        //resolve(dict_Search["NoResults"]);
                        resolve(result.data);
                    }
                } else {
                    reject(result.error);
                }
            }
        };
    });

    //https://www.taniarascia.com/how-to-connect-to-an-api-with-javascript/
    return promiseObj;
}

function getAmountFromDatabase(table, column="", page="") {

   var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/item_num.php';
        var params = '';

        if (table !== "") {
            params = '?table=' + table;
        }

        if (column !== "") {
            params += (params !== "" ? '&' : '?') + 'column=' + column;
        }

        if (page !== "") {
            params += (params !== "" ? '&' : '?') + 'offset=' + parseInt(page)*100;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    resolve(result.data[0].num);
                } else {
                    reject(result.error);
                }
            }
        };
    });

    //https://www.taniarascia.com/how-to-connect-to-an-api-with-javascript/
    return promiseObj;
}

function getMapFromDatabase(table="", value="") {

   var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/map_read.php';
        var params = '';

        if (table !== "") {
            params = '?table=' + table;
        }

        if (value !== "") {
            params += (params !== "" ? '&' : '?') + 'value=' + value;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    if (result.hasOwnProperty("data") && (result.data !== null)) {
                        resolve(result.data);
                    } else {
                        // TODO: This need to be handled elsewhere
                        //resolve(dict_Search["NoResults"]);
                        resolve(result.data);
                    }
                } else {
                    reject(result.error);
                }
            }
        };
    });

    //https://www.taniarascia.com/how-to-connect-to-an-api-with-javascript/
    return promiseObj;
}

function addBlogToDatabase(title, text, user) {
    var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/blog_write.php';
        var params = '?type=add';

        if (title !== "") {
            params += '&title=' + CleanText(title);
        }

        if (text !== "") {
            params += '&text=' + CleanText(text);
        }

        if (user !== "") {
            params += '&user=' + user;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    if (result.hasOwnProperty("data") && (result.data === true)) {
                        resolve("<h1>" + dict_Settings["blog_added"] + "</h1>");
                    }
                } else {
                    reject(result.error);
                }
            }
        };
    });

    //https://www.taniarascia.com/how-to-connect-to-an-api-with-javascript/
    return promiseObj;
}

function deleteBlogFromDatabase(id) {
    var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/blog_write.php';
        var params = '?type=delete';

        if (id !== "") {
            params += '&id=' + id;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    if (result.hasOwnProperty("data") && (result.data === true)) {
                        resolve("<h1>" + dict_Settings["blog_removed"] + "</h1>");
                    }
                } else {
                    reject(result.error);
                }
            }
        };
    });

    //https://www.taniarascia.com/how-to-connect-to-an-api-with-javascript/
    return promiseObj;
}

function editBlogFromDatabase(id, title, text) {
    var promiseObj = new Promise(function(resolve, reject) {

        // Create a request variable and assign a new XMLHttpRequest object to it.
        var request = new XMLHttpRequest();

        var link = 'http://localhost/web/api/blog_write.php';
        var params = '?type=edit';

        if (title !== "") {
            params += '&title=' + CleanText(title);
        }

        if (text !== "") {
            params += '&text=' + CleanText(text);
        }

        if (id !== "") {
            params += '&id=' + id;
        }

        // Open a new connection, using the GET request on the URL endpoint
        request.open('GET', link + params, true);

        // Send request
        request.send();

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var result = JSON.parse(request.responseText);

                if (!result.error) {
                    if (result.hasOwnProperty("data") && (result.data === true)) {
                        resolve("<h1>" + dict_Settings["blog_edited"] + "</h1>");
                    }
                } else {
                    reject(result.error);
                }
            }
        };
    });

    //https://www.taniarascia.com/how-to-connect-to-an-api-with-javascript/
    return promiseObj;
}