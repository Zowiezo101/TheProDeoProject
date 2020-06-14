/* global dict_Search */

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

        if (value !== "") {
            params += (params !== "" ? '&' : '?') + 'value=' + value;
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