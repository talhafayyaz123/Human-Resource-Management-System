export default {
  namespaced: true,
  state: {
    timeChecking: {
      data: [],
      links: [],
    },
  },
  getters: {
    timeChecking: (state) => state.timeChecking,
  },
  mutations: {
    timeChecking: (state, payload) => (state.timeChecking = payload),
  },
  actions: {
    list({ commit }, queryParams) {
      return this.$apiService
        .get("/time-checking", {
          params: queryParams,
        })
        .then((res) => {
          commit(
            "timeChecking",
            res?.data ?? {
              data: [],
              links: [],
            }
          );
        });
    },
    update({}, { id, data }) {
      return this.$apiService.put(`/time-checking/${id}`, data);
    },
  },
};
