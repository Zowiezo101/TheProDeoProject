/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global fetch, base_url */

/**
 * getData(table, type, data)
 * @param {String} table
 * @param {String} type
 * @param {Object} data
 *  
 *  @return {Promise}
 * 
 */
function getData(table, type, data) {
    var url = base_url + "/web/api/" + table + "/" + type + ".php";
    var query = getQuery(data);
    
    return fetch(url + query, {
            method: 'GET'
        }
    ).then(
        response => response.text()
    ).then (function (response) {
        console.log(response);
        return JSON.parse(response);
    });
}

function postData(table, data) {
    var url = base_url + "/web/api/" + table + "/create.php";
    var params = getParams({"data": data});
    
    return fetch(url, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            },
            body: JSON.stringify(params)
        }
    ).then(response => response.json());
}

function putData(table, id, data) {
    var url = base_url + "/web/api/" + table + ".php";
    var params = getParams({"id": id, "data": data});
    
    return fetch(url, {
            method: 'PUT',
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            },
            body: JSON.stringify(params)
        }
    ).then(response => response.json());
}

function deleteData(table, id) {
    var url = base_url + "/web/api/" + table + ".php";
    var params = getParams({"id": id});
    
    return fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-type': 'application/json; charset=UTF-8'
            },
            body: JSON.stringify(params)
        }
    ).then(response => response.json());
}

/**
 * getBlogs()
 *  
 *  @return {Promise}
 */
function getBlogs() {
    return getData("blog", "read", {});
}

function postBlog(title, text, user, date) {
    return postData("blog", {
        "title": title,
        "text": text,
        "user": user,
        "date": date
    });
}

function putBlog(id, title, text) {
    
}

function deleteBlog(id) {
    
}

/**
 * getItemPage(table, page, sort, search)
 * @param {String} table
 * @param {Number} page
 * @param {String} sort
 * @param {String} filter
 *  
 *  @return {Promise}
 */
function getItemPage(table, page, sort, filter) {
    return getData(table, "read_paging", {"page": page, "sort": sort, "filter": filter});
}

/**
 * getItem(table, id)
 * @param {String} table
 * @param {Number} id
 *  
 *  @return {Promise}
 */
function getItem(table, id) {
    return getData(table, "read_one", {"id": id});
}

function getItemsSearch(table) {
    
}

/**
 * getBookPage(page, sort, search)
 * @param {Number} page
 * @param {String} sort
 * @param {String} filter
 *  
 *  @return {Promise}
 */
function getBookPage(page, sort, filter) {
    return getItemPage("book", page, sort, filter);
}

/**
 * getBook(id)
 * @param {Number} id
 *  
 *  @return {Promise}
 */
function getBook(id) {
    return getItem("book", id);
}

/**
 * getEvents(id, options)
 * @param {String} id
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getEvents(id, options) {
    return getData("events", id, options);
}

/**
 * getActivities(id, options)
 * @param {String} id
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getActivities(id, options) {
    return getData("activitys", id, options);
}

/**
 * getPeoples(id, options)
 * @param {String} id
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getPeoples(id, options) {
    return getData("peoples", id, options);
}

/**
 * getLocations(id, options)
 * @param {String} id
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getLocations(id, options) {
    return getData("locations", id, options);
}

/**
 * getSpecials(id, options)
 * @param {String} id
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getSpecials(id, options) {
    return getData("specials", id, options);
}

/**
 * @param {{id: String, data: <*>, options: <*>}} params
 * */
function getQuery(params) {
    /** @type String */
    var query = "";
    
    query = checkAndAddToQuery(query, params, 'id');
    query = checkAndAddToQuery(query, params, 'sort');
    query = checkAndAddToQuery(query, params, 'page');
    query = checkAndAddToQuery(query, params, 'filter');
    
    return query;
}

/**
 * @param {{id: String, data: <*>, options: <*>}} params
 * */
function getParams(params) {
    /**
     * @type {{id: String, data: Object, columns: Array, filters: Array, sort: Array, limit: Number, offset: Number}}
     * */
    var params_json = {};
    
    params_json = checkAndAddToParams(params_json, params, 'id');
    params_json = checkAndAddToParams(params_json, params, 'data');
    if (params.options) {
        params_json = checkAndAddToParams(params_json, params.options, 'columns');
        params_json = checkAndAddToParams(params_json, params.options, 'filters');
        params_json = checkAndAddToParams(params_json, params.options, 'sort');
        params_json = checkAndAddToParams(params_json, params.options, 'limit');
        params_json = checkAndAddToParams(params_json, params.options, 'offset');
        params_json = checkAndAddToParams(params_json, params.options, 'calculations');
        params_json = checkAndAddToParams(params_json, params.options, 'joins');
        params_json = checkAndAddToParams(params_json, params.options, 'to');
    }
    
    return params_json;
}

function checkAndAddToQuery(query, object, property) {
    var new_query = query;
    if (object.hasOwnProperty(property) && object[property]) {
        new_query += ((query ? "&" : "?") + property + "=" + (typeof object[property] === "Array" ? object[property].join(',') : object[property]));
    }
    return new_query;
}

function checkAndAddToParams(params, object, property) {
    var new_params = params;
    if (object.hasOwnProperty(property) && object[property]) {
        new_params[property] = object[property];
    }
    return new_params;
}
		
