import Store from './store.js';
import { placeholders } from './middle/placeholders.js';
import { inMiddleDirection } from './middle/index.js';
import { inParentDirection } from './parents/index.js';
import { inChildDirection } from './children/index.js';
import { connectors } from './connectors/index.js';
import { correctPositions } from './utils/correctPositions.js';
import { getCanvasSize } from './utils/getCanvasSize.js';
import { getExtendedNodes } from './utils/getExtendedNodes.js';
import { draw } from './render';
import { pipe } from './utils/index.js';

// Creating a new function that executes all the argument functions
// The given parameter in the new fucntion is the initial value all the functions are executed with
const calcFamilies = pipe(inMiddleDirection, inParentDirection, inChildDirection, correctPositions);
const calcFamilies2 = pipe(inMiddleDirection);

window.generateTree = function (el, nodes, options) {
    // Turning the nodes to a map
    const store = new Store(nodes, options.rootId);
    const store2 = new Store(nodes, options.rootId);
    
    // If we want a placeholder for parents to connect all the siblings
    if (options.placeholders)
        placeholders(store);
    
//    console.log(JSON.stringify(store));
//    var nodes = [...store.nodes.entries()];
//    var fam = [...store.families.entries()];
//    console.log(JSON.stringify(nodes));
//    console.log(JSON.stringify(fam));
    
    const families2 = calcFamilies2(store2);

    console.log(JSON.stringify(families2));
    var nodes = [...families2.nodes.entries()];
    var fam = [...families2.families.entries()];
    console.log(JSON.stringify(nodes));
    console.log(JSON.stringify(fam));
    
    const families = calcFamilies(store).familiesArray;
    const tree = {
        families: families,
        canvas: getCanvasSize(families),
        nodes: getExtendedNodes(families),
        connectors: connectors(families),
    };
    
    const canvas = document.createElement('canvas');
    el.append(canvas);
    draw(canvas, tree, { root: options.rootId, debug: false });
};

//// Creating a new function that executes all the argument functions
//// The given parameter in the new fucntion is the initial value all the functions are executed with
////const calcFamilies = pipe(inMiddleDirection, inParentDirection, inChildDirection, correctPositions);
//const calcFamilies = pipe(inMiddleDirection, inParentDirection, inChildDirection, correctPositions);
//
//window.generateTree = function (el, nodes, options) {
//    // Turning the nodes to a map
//    const store = new Store(nodes, options.rootId);
//    console.log(JSON.stringify(store));
//    
//    // If we want a placeholder for parents to connect all the siblings
//    if (options.placeholders)
//        placeholders(store);
//    
//    const families = calcFamilies(store).familiesArray;
//    const tree = {
//        families: families,
//        canvas: getCanvasSize(families),
//        nodes: getExtendedNodes(families),
//        connectors: connectors(families),
//    };
////    console.log(JSON.stringify(tree));
//    const canvas = document.createElement('canvas');
//    el.append(canvas);
//    draw(canvas, tree, { root: options.rootId, debug: false });
//};