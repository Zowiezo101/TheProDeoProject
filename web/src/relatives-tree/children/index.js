import { withType } from '../utils/family';
import { hasChildren, nodeIds } from '../utils/units';
import { FamilyType } from '../types';
import { createFamilyFunc } from './create';
import { updateFamilyFunc } from './update';
import { arrangeFamiliesFunc } from './arrange';
const getUnitsWithChildren = (family) => (family.children.filter(hasChildren).reverse());
export const inChildDirection = (store) => {
    const createFamily = createFamilyFunc(store);
    const updateFamily = updateFamilyFunc(store);
    const arrangeFamilies = arrangeFamiliesFunc(store);
    store.familiesArray
        .filter(withType(FamilyType.root))
        .forEach((rootFamily) => {
        let stack = getUnitsWithChildren(rootFamily);
        while (stack.length) {
            console.log("===\n\tNew Family\n===");
            
            // The parentUnit in the stack
            const parentUnit = stack.pop();
            if (parentUnit.fid == 18) {
                console.log(JSON.stringify(parentUnit));  
            }
            
            // The family that is being created
            const family = createFamily(nodeIds(parentUnit), FamilyType.child);
//            console.log(JSON.stringify(family));
//            console.log(JSON.stringify(getUnitsWithChildren(family)));
            
            updateFamily(family, parentUnit);
            arrangeFamilies(family);
            store.families.set(family.id, family);
            stack = stack.concat(getUnitsWithChildren(family));
            
        }
    });
    return store;
};