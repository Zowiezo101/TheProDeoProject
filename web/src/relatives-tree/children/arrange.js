import { correctUnitsShift, getUnitX, sameAs, sameAsLeft } from '../utils/units';
import { rightOf } from '../utils/family';
import { nextIndex, withId } from '../utils/index.js';
import { arrangeParentsIn } from '../utils/arrangeParentsIn';
const arrangeNextFamily = (family, nextFamily) => {
//    console.log("family");
//    console.log(family.parents[0]);
//    console.log("nextFamily");
//    console.log(nextFamily.children);
    const unit = family.parents[0];
    const index1 = nextFamily.children.findIndex(sameAs(unit));
    const index2 = nextFamily.children.findIndex(sameAsLeft(unit));
    
//    if (index1 !== index2) {
//        console.log("Index1: " + index1 + "\nIndex2: " + index2);
//        console.log("Child1: " + (index1 !== -1 ? JSON.stringify(nextFamily.children[index1]) : "-1") + "\nParent1: " + JSON.stringify(unit));
//        console.log("Child2: " + (index2 !== -1 ? JSON.stringify(nextFamily.children[index2]) : "-1") + "\nParent2: " + JSON.stringify(unit));
//    }
    if (index1 !== -1) {
        index1 === 0
            ? nextFamily.X = getUnitX(family, unit) - nextFamily.children[index1].pos
            : nextFamily.children[index1].pos = getUnitX(family, unit) - nextFamily.X;
        const nextIdx = nextIndex(index1);
        if (nextFamily.children[nextIdx]) {
            correctUnitsShift(nextFamily.children.slice(nextIdx), rightOf(family) - getUnitX(nextFamily, nextFamily.children[nextIdx]));
        }
    } else if (index2 !== -1) {
        index2 === 0
            ? nextFamily.X = getUnitX(family, unit) - nextFamily.children[index2].pos
            : nextFamily.children[index2].pos = getUnitX(family, unit) - nextFamily.X;
        const nextIdx = nextIndex(index2);
        if (nextFamily.children[nextIdx]) {
            correctUnitsShift(nextFamily.children.slice(nextIdx), rightOf(family) - getUnitX(nextFamily, nextFamily.children[nextIdx]));
        }
    }
};
const arrangeMiddleFamilies = (families, fid, startFrom) => {
    const start = nextIndex(families.findIndex(withId(fid)));
    const family = families[start];
    if (family) {
        const shift = startFrom - family.X;
        for (let i = start; i < families.length; i++)
            families[i].X += shift;
    }
};
export const arrangeFamiliesFunc = (store) => ((family) => {
    while (family.pid) {
        const nextFamily = store.getFamily(family.pid);
        arrangeNextFamily(family, nextFamily);
        arrangeParentsIn(nextFamily);
        if (!nextFamily.pid)
            arrangeMiddleFamilies(store.rootFamilies, nextFamily.id, rightOf(nextFamily));
        family = nextFamily;
    }
});