import { min, prop } from './index';
import { arrangeParentsIn } from './arrangeParentsIn';
import { arrangeInOrder, correctUnitsShift } from './units';
export const setDefaultUnitShift = (family) => {
    const units = [family.parents, family.children];
    units.forEach(arrangeInOrder);
    arrangeParentsIn(family);
    const start = min(units.flat().map(prop('pos')));
    if (start !== 0)
        units.forEach(items => correctUnitsShift(items, start * -1));
};