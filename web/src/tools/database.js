/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* global fetch */

/**
 * getData(table, id, options)
 * @param {String} table
 * @param {String} id
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
function getData(table, id, options) {
    var url = "http://localhost/web/api/" + table;
    var query = getQuery({"id": id, "options": options});
    
    return fetch(url + query, {
            method: 'GET'
        }
    ).then(response => response.json());
}

function postData(table, data) {
    var url = "http://localhost/web/api/" + table;
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
    var url = "http://localhost/web/api/" + table;
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
    var url = "http://localhost/web/api/" + table;
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
 * getBlogs(id, options)
 * @param {String} id
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getBlogs(id, options) {
    if ((typeof(id) === "undefined") || 
            (typeof(options) === "undefined")) {
        options = {
            sort: ["id desc"],
            columns: ["id", "title", "text", "user", "date"]
        };
    }
    return getData("blog", id, options);
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
 * getBooks(id, options)
 * @param {String} id
 * @param options
 *  - Filter
 *  - Columns to return
 *  - Calculations to return
 *  
 *  @return {Promise}
 */
function getBooks(id, options) {
    return getData("books", id, options);
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
    if (params.options) {
        query = checkAndAddToQuery(query, params.options, 'columns');
        query = checkAndAddToQuery(query, params.options, 'filters');
        query = checkAndAddToQuery(query, params.options, 'sort');
        query = checkAndAddToQuery(query, params.options, 'limit');
        query = checkAndAddToQuery(query, params.options, 'offset');
        query = checkAndAddToQuery(query, params.options, 'calculations');
        query = checkAndAddToQuery(query, params.options, 'to');
    }
    
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
		
