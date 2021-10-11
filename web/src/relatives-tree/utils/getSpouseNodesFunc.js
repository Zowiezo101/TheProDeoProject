import { NODES_IN_COUPLE } from '../constants';
import { RelType } from '../types';
import { byGender, relToNode, withRelType } from './index';
const inDescOrderOfChildCount = (a, b) => (b.children.length - a.children.length);
const getSpouse = (store, spouses) => {
    const toNode = relToNode(store);
    const married = spouses.find(withRelType(RelType.married));
    if (married)
        return toNode(married);
    if (spouses.length >= 1)
        return spouses.map(toNode).sort(inDescOrderOfChildCount)[0];
    return;
};
const getCoupleNodes = (store, target) => {
    // Return target and spouse if there is any
    return [target, getSpouse(store, target.spouses)]
        .filter((node) => Boolean(node))
        .sort(byGender(store.root.gender));
};
const excludeRel = (target) => (rel) => rel.id !== target.id;
export const getSpouseNodesFunc = (store) => {
    // Function that will return a node, based on the relation
    const toNode = relToNode(store);
    
    // Returning a function with parents as parameter
    return (parents) => {
        let middle = parents;
        // If not two parents
        if (middle.length !== NODES_IN_COUPLE) {
            middle = getCoupleNodes(store, middle[0]);
        }
        const result = { left: [], middle, right: [] };
        
        // Any other spouses?
        if (middle.length === NODES_IN_COUPLE) {
            const [first, second] = middle;
            result.left = first.spouses.filter(excludeRel(second)).map(toNode);
            result.right = second.spouses.filter(excludeRel(first)).map(toNode);
        }
        return result;
    };
};