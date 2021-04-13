/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global fetch */

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
    var query = getQuery({"table": table, "ids": ids, "options": options});
    
    return fetch(url + query, {
            method: 'GET' /*,
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            },
            body: JSON.stringify(params)*/
        }
    ).then(response => response.json());
}

function postData(table, ids, options) {
    var url = "http://localhost/web/api/item_read.php";
    var params = getParams({"table": table, "ids": ids, "options": options});
    
    return fetch(url, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            },
            body: JSON.stringify(params)
        }
    ).then(response => response.json());
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
    if ((typeof(ids) === "undefined") || 
            (typeof(options) === "undefined")) {
        options = {
            sort: ["id desc"]
        };
    }
    return getData("blog", ids, options);
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
function getQuery(params) {
    /**
     * @type {{table: String, ids: Array|String, columns: Array, filters: Array, calculations: Array}}
     * */
    var query = "";
    
    query = "?table=" + params.table;
    if (params.ids) {
        query += ("&ids=" + params.ids.join(';'));
    } 
    if (params.options) {
        if (params.options.hasOwnProperty('columns') && params.options.columns) {
            query += ("&columns=" + params.options.columns.join(';'));
        } 
        if (params.options.hasOwnProperty('filters') && params.options.filters) {
            query += ("&filters=" + params.options.filters.join(';'));
        } 
        if (params.options.hasOwnProperty('sort') && params.options.sort) {
            query += ("&sort=" + params.options.sort.join(';'));
        }
        if (params.options.hasOwnProperty('calculations') && params.options.calculations) {
            query += ("&calculations=" + params.options.calculations.join(';'));
        }
    }
    
    return query;
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
    if (params.ids) {
        params_json.ids = params.ids;
    } 
    if (params.options) {
        if (params.options.hasOwnProperty('columns') && params.options.columns) {
            params.json.columns = params.options.hasOwnProperty('columns') ? params.options.columns : "";
        } 
        if (params.options.hasOwnProperty('filters') && params.options.filters) {
            params.json.filters = params.options.hasOwnProperty('filters') ? params.options.filters : "";
        } 
        if (params.options.hasOwnProperty('sort') && params.options.sort) {
            params.json.sort = params.options.hasOwnProperty('sort') ? params.options.sort : "";
        } 
        if (params.options.hasOwnProperty('calculations') && params.options.calculations) {
            params.json.calculations = params.options.hasOwnProperty('calculations') ? params.options.calculations : "";
        }
    }
    
    return params_json;
}
		
