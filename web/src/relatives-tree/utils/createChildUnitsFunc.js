import { getSpouseNodesFunc } from './getSpouseNodesFunc';
import { newUnit } from './units';
const toArray = (nodes) => nodes.map(node => Array.of(node));
export const createChildUnitsFunc = (store) => {
    // Create a function that will return the spouses based on the child
    const getSpouseNodes = getSpouseNodesFunc(store);
    
    // Return a function with familyID and child as arguments
    return (familyId, child) => {
        const { left, middle, right } = getSpouseNodes([child]);
        // Return an array of child units
        return [...toArray(left), middle, ...toArray(right)]
            .map((nodes) => newUnit(familyId, nodes, true));
    };
};