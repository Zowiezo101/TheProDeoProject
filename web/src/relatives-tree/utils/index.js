export const nextIndex = (index) => index + 1;

// A function that returns a function
// The second function returns the property given in the first function
export const prop = (name) => (item) => item[name];

// A function that returns a function
// The second function compares the ID given in the first function to its argument
export const withId = (id) => (item) => item.id === id;
export const withIds = (ids, include = true) => ((item) => ids.includes(item.id) === include);
export const unique = (item, index, arr) => arr.indexOf(item) === index;
export const inAscOrder = (v1, v2) => v1 - v2;
export const pipe = (...fus) => (init) => fus.reduce((res, fn) => fn(res), init);
export const min = (arr) => Math.min.apply(null, arr);
export const max = (arr) => Math.max.apply(null, arr);

// Create a map with [id, item] from the given items
export const toMap = (items) => (new Map(items.map((item) => [item.id, Object.assign({}, item)])));

// Returns the types of this nodes parents and returns true if all the values are unique
export const hasDiffParents = (node) => node.parents.map(prop('type')).filter(unique).length > 1;

export const byGender = (target) => (_, b) => (b.gender !== target) ? -1 : 1;

// A function that returns a function
// The second function returns the node of the given relation
export const relToNode = (store) => (rel) => store.getNode(rel.id);
export const withRelType = (...types) => (item) => types.includes(item.type);