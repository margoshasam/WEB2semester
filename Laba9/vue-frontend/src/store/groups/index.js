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
    addItem: (state, item) => {
      state.items.push(item);
    },
    removeItem: (state, idRemove) => {
      state.items = state.items.filter(({ id }) => id !== idRemove);
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
    addItem: async ({ commit }, { name_group }) => {
      const item = await api.create({ name_group })
      commit('addItem', item)
    },
    updateItem: async ({ commit }, { id, name_group }) => {
      const item = await api.update({ id, name_group })
      commit('updateItem', item)
    }
  },
}
