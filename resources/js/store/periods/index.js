export default {
  namespaced: true,
  state: {
    periods: {
      data: [],
      links: [],
    },
  },
  getters: {
    periods: (state) => state.periods,
  },
  mutations: {
    periods: (state, payload) => (state.periods = payload),
  },
  actions: {
    list({ commit }, queryParams) {
      return this.$apiService
        .get("/periods", {
          params: queryParams,
        })
        .then((res) => {
          commit(
            "periods",
            res?.data?.periods ?? {
              data: [],
              links: [],
            }
          );
        });
    },
    create({}, payload) {
      return this.$apiService.post("/periods", payload);
    },
    show({}, id) {
      return this.$apiService.get(`/periods/${id}`);
    },
    update({}, { id, data }) {
      return this.$apiService.put(`/periods/${id}`, data);
    },
    destroy({}, id) {
      return this.$apiService.delete(`/periods/${id}`);
    },
  },
};
