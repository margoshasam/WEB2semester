import api from './api'

export default {
  namespaced: true,
  state: {
    items: [],
  },
  getters: {
    items: state => state.items,
    itemsByKey: state => state.items.reduce((res, cur) => {
      res[cur['id']] = cur;
      return res;
    }, {}),
  },
  mutations: {
    setItems: (state, items) => {
      state.items = items;
    },
    setItem: (state, item) => {
      state.items.push(item);
    },
    removeItem: (state, idRemove) => {
      state.items = state.items.filter(({ id }) => id !== idRemove)
    },
    updateItem: (state, updateItem) => {
      const index = state.items.findIndex(item => +item.id === +updateItem.id);
      state.items[index] = updateItem;
    }
  },
  actions: {
    fetchItems: async ({ commit }) => {
      const response = await api.list()
      const items = await response.json()
      commit('setItems', items)
    },
    removeItem: async ({ commit }, id) => {
      const idRemovedItem = await api.delete(id)
      commit('removeItem', idRemovedItem)
    },
    addItem: async ({ commit }, { name, year_of_birth, id_group, bio, img_path }) => {
      const item = await api.create({ name, year_of_birth, id_group, bio, img_path })
      commit('setItem', item)
    },
    updateItem: async ({ commit }, { id, name, year_of_birth, id_group, bio, img_path }) => {
      const item = await api.update({ id, name, year_of_birth, id_group, bio, img_path })
      commit('updateItem', item)
    }
  },
}
