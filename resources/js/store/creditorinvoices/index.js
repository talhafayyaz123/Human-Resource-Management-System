export default {
  namespaced: true,
  state: {
    invoices: {
      data: [],
      links: [],
    },
    invoiceTypes: [],
  },
  getters: {
    invoices: (state) => state.invoices,
  },
  mutations: {
    invoices: (state, payload) => (state.invoices = payload),
  },
  actions: {
    list({ commit }, queryParams) {
      return this.$apiService
        .get("/creditor-invoices", { params: queryParams })
        .then((res) => {
          commit("invoices", res?.data ?? { data: [], links: [] });
        });
    },
    create({}, payload) {
      return this.$apiService.post("/creditor-invoices", payload);
    },
    show({}, id) {
      return this.$apiService.get(`/creditor-invoices/${id}`);
    },
    update({}, { id, data }) {
      return this.$apiService.put(`/creditor-invoices/${id}`, data);
    },
    destroy({}, id) {
      return this.$apiService.delete(`/creditor-invoices/${id}`);
    },
  },
};
