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
    ).then(
        response => response.text()
    ).then (function (response) {
        console.log(response);
        return JSON.parse(response);
    });
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
    ).then(
        response => response.text()
    ).then (function (response) {
        console.log(response);
        return JSON.parse(response);
    });
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
    ).then(
        response => response.text()
    ).then (function (response) {
        console.log(response);
        return JSON.parse(response);
    });
}

/**
 * getBlogs(user)
 * 
 * @param {String} id
 *  
 *  @return {Promise}
 */
function getBlogs(id) {
    return getData("blog", "read", {
        "id": id
    });
}

/**
 * getBlog(id)
 * 
 * @param {String} id
 *  
 *  @return {Promise}
 */
function getBlog(id) {
    return getData("blog", "read_one", {
        "id": id
    });
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
    return putData("blog", id, {
        "title": title,
        "text": text
    });
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
/**
 * getItemsSearch(table, filter)
 * @param {String} table
 * @param {Object} filter
 *  
 *  @return {Promise}
 */
function getItemsSearch(table, filter) {
    return getData(table, "search", {"filter": filter});
}

/**
 * searchBook(filter)
 * @param {Object} filter
 *  
 *  @return {Promise}
 */
function searchBooks(filter) {
    return getItemsSearch("books", filter);
}

/**
 * searchEvents(filter)
 * @param {Object} filter
 * 
 *  @return {Promise}
 */
function searchEvents(filter) {
    return getItemsSearch("events", filter);
}

/**
 * searchActivities(filter)
 * @param {Object} filter
 * 
 *  @return {Promise}
 */
function searchActivities(filter) {
    return getItemsSearch("activitys", filter);
}

/**
 * searchPeoples(filter)
 * @param {Object} filter
 *  
 *  @return {Promise}
 */
function searchPeoples(filter) {
    return getItemsSearch("peoples", filter);
}

/**
 * searchLocations(filter)
 * @param {Object} filter
 *  
 *  @return {Promise}
 */
function searchLocations(filter) {
    return getItemsSearch("locations", filter);
}

/**
 * searchSpecials(filter)
 * @param {Object} filter
 *  
 *  @return {Promise}
 */
function searchSpecials(filter) {
    return getItemsSearch("specials", filter);
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
		
