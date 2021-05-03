/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function setParameters(url) {
    var newUrl = url;
    
    // TODO: Get languague from window.location.url
    if (true) {
        newUrl = "/en" + (url[0] === "/" ? "" : "/") + url;
    }
    
    return newUrl;
}


