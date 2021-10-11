import { Gender, RelType } from '../types';
import { relToNode } from '../utils/index.js';
const createRel = (id, type = RelType.blood) => ({ id, type });
const createNode = (gender) => ({
    id: `${gender}-ph`,
    placeholder: true,
    gender: gender,
    parents: [],
    siblings: [],
    spouses: [],
    children: [],
});
const createParents = (store) => {
    // Generate parents
    const father = createNode(Gender.male);
    const mother = createNode(Gender.female);
    father.spouses = [createRel(mother.id, RelType.married)];
    mother.spouses = [createRel(father.id, RelType.married)];
    
    return [father, mother].map(node => {
        // Add the siblings of the root and the root itself as children of these parents
        node.children = store.root.siblings.concat(createRel(store.root.id));
        store.nodes.set(node.id, node);
        return createRel(node.id);
    });
};
const setParents = (parents) => ((node) => node.parents = parents.slice());
export const placeholders = (store) => {
    
    // If the root has no parents
    if (!store.root.parents.length) {
        // A function to assign these nodes as parents
        const setParentsTo = setParents(createParents(store));
        
        // Assign these nodes to the root
        setParentsTo(store.root);
        
        // Assign these nodes to the siblings of the root
        store.root.siblings
            .map(relToNode(store))
            .forEach(setParentsTo);
    }
    // Return the updates
    return store;
};