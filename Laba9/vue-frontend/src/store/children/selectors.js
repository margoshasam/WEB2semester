export const fetchItems = (store) => {
  const { dispatch } = store;
  dispatch('children/fetchItems');
};

export const selectItems = (store) => {
  const { getters } = store;
  return getters['children/items']
}

export const removeItem = (store, id) => {
  const { dispatch } = store;
  dispatch('children/removeItem', id);
}

export const addItem = (store, { name, year_of_birth, id_group, bio, img_path }) => {
  const { dispatch } = store;
  dispatch('children/addItem', { name, year_of_birth, id_group, bio, img_path });
}

export const updateItem = (store, { id, name, year_of_birth, id_group, bio, img_path }) => {
  const { dispatch } = store;
  dispatch('children/updateItem', { id, name, year_of_birth, id_group, bio, img_path });
}

export const selectItemById = (store, id) => {
  const { getters } = store;
  return getters['children/itemsByKey'][id] || {};
}
