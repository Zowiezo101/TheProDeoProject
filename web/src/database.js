/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * getData(table, id, options)
 * @param table
 * @param id
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  getBooks()
 *  getPersons()
 *  getEvents()
 *  getLocations()
 *  getSpecials()
 *  getActivities()
 *  getBlogs()
 * 
 */
function getData(table, id, options) {
    var url = "http://localhost/web/api/item_read.php";
    var params = getParams({"table": table, "id": id, "options": options});
    
    $.fetch(url + params, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            }
        }
    ).then(response => response.json()).then (
        function (response) {		
            
        }
    );
}

function getParams() {
    
}
		
