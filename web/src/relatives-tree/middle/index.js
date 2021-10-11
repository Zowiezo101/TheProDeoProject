import { hasDiffParents } from '../utils/index.js';
import { rightOf } from '../utils/family';
import { createBloodFamilies, createDiffTypeFamilies, createFamilyWithoutParents } from './create';
const arrangeFamilies = (families) => {
    for (let i = 1; i < families.length; i++) {
        families[i].X = rightOf(families[i - 1]);
    }
};
export const inMiddleDirection = (store) => {
    // If the root has parents
    const families = store.root.parents.length
        // Check if it's the same of parents
        ? (hasDiffParents(store.root))
            // Different types
            ? createDiffTypeFamilies(store)
            // Same type
            : createBloodFamilies(store)
        // No parents
        : createFamilyWithoutParents(store);
        
    arrangeFamilies(families);
    families.forEach(family => store.families.set(family.id, family));
    return store;
};