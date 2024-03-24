export default {
  namespaced: true,
  state: {
    contractTypes: {
      data: [],
      links: [],
    },
  },
  getters: {
    contractTypes: (state) => state.contractTypes,
  },
  mutations: {
    contractTypes: (state, payload) => (state.contractTypes = payload),
  },
  actions: {
    list({ commit }, queryParams) {
      return this.$apiService
        .get("/contract-types", {
          params: queryParams,
        })
        .then((res) => {
          commit(
            "contractTypes",
            res?.data ?? {
              data: [],
              links: [],
            }
          );
        });
    },
    create({}, payload) {
      return this.$apiService.post("/contract-types", payload);
    },
    show({}, id) {
      return this.$apiService.get(`/contract-types/${id}`);
    },
    update({}, { id, data }) {
      return this.$apiService.put(`/contract-types/${id}`, data);
    },
    destroy({}, id) {
      return this.$apiService.delete(`/contract-types/${id}`);
    },
    attachmentsByContractType({}, id) {
      return this.$apiService.get(`/contract-types/attachments/${id}`);
    },
  },
};
