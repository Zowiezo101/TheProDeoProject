/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * getData(table, id, options)
 * @param {String} table
 * @param {Array|String} ids
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 *  
 *  getBooks()
 *  getPeoples()
 *  getEvents()
 *  getLocations()
 *  getSpecials()
 *  getActivities()
 *  getBlogs()
 * 
 */
function getData(table, ids, options) {
    var url = "http://localhost/web/api/item_read.php";
    var params = getParams({"table": table, "ids": ids, "options": options});
    
    return $.fetch(url, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            },
            body: JSON.stringify(params)
        }
    );
}

/**
 * getBlogs(id, options)
 * @param {Array|String} ids
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getBlogs(ids, options) {
    return getData("blogs", ids, options);
}

/**
 * getBooks(id, options)
 * @param {Array|String} ids
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getBooks(ids, options) {
    return getData("books", ids, options);
}

/**
 * getEvents(id, options)
 * @param {Array|String} ids
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getEvents(ids, options) {
    return getData("events", ids, options);
}

/**
 * getActivities(id, options)
 * @param {Array|String} ids
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getActivities(ids, options) {
    return getData("activitys", ids, options);
}

/**
 * getPeoples(id, options)
 * @param {Array|String} ids
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getPeoples(ids, options) {
    return getData("peoples", ids, options);
}

/**
 * getLocations(id, options)
 * @param {Array|String} ids
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getLocations(ids, options) {
    return getData("locations", ids, options);
}

/**
 * getSpecials(id, options)
 * @param {Array|String} ids
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getSpecials(ids, options) {
    return getData("specials", ids, options);
}

/**
 * @param {{table: String, ids: Array|String, options: <*>}} params
 * */
function getParams(params) {
    /**
     * @type {{table: String, ids: Array|String, columns: Array, filters: Array, calculations: Array}}
     * */
    var params_json = {};
    
    params_json.table = params.table;
    params_json.ids = params.ids;
    params.json.columns = params.options.hasOwnProperty('columns') ? params.options.columns : "";
    params.json.filters = params.options.hasOwnProperty('filters') ? params.options.filters : "";
    params.json.calculations = params.options.hasOwnProperty('calculations') ? params.options.calculations : "";
    
    return params_json;
}
		
