export default {
  namespaced: true,
  state: {
    leadStatus: {
      data: [],
      links: [],
    },
  },
  getters: {
    leadStatus: (state) => state.leadStatus,
  },
  mutations: {
    leadStatus: (state, payload) => (state.leadStatus = payload),
  },
  actions: {
    list({ commit }, queryParams) {
      return this.$apiService
        .get("/sales/lead-status", {
          params: queryParams,
        })
        .then((res) => {
          commit(
            "leadStatus",
            res?.data?.operatingSystem ?? {
              data: [],
              links: [],
            }
          );
        });
    },
    create({}, payload) {
      return this.$apiService.post("/sales/lead-status", payload);
    },
    show({}, id) {
      return this.$apiService.get(`/sales/lead-status/${id}`);
    },
    update({}, { id, data }) {
      return this.$apiService.put(`/sales/lead-status/${id}`, data);
    },
    destroy({}, id) {
      return this.$apiService.delete(`/sales/lead-status/${id}`);
    },
  },
};
