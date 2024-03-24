export default {
  namespaced: true,
  state: {
    databaseClouds: {
      data: [],
      links: [],
    },
    count: 0,
  },
  getters: {
    databaseClouds: (state) => state.databaseClouds,
    count: (state) => state.count,
  },
  mutations: {
    databaseClouds: (state, payload) => (state.databaseClouds = payload),
    count: (state, payload) => (state.count = payload),
  },
  actions: {
    list({ commit }, queryParams) {
      return this.$apiService
        .get("/database-cloud", {
          params: queryParams,
        })
        .then((res) => {
          commit("databaseClouds", res.data);
          return res;
        });
    },
    create({}, payload) {
      return this.$apiService.post("/database-cloud", payload);
    },
    show({}, id) {
      return this.$apiService.get(`/database-cloud/${id}`);
    },
    update({}, { id, data }) {
      return this.$apiService.put(`/database-cloud/${id}`, data);
    },
    destroy({}, id) {
      return this.$apiService.delete(`/database-cloud/${id}`);
    },
  },
};
