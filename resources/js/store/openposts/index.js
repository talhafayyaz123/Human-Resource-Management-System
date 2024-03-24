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
    invoiceTypes: (state) => state.invoiceTypes,
  },
  mutations: {
    invoices: (state, payload) => (state.invoices = payload),
    invoiceTypes: (state, payload) => (state.invoiceTypes = payload),
  },
  actions: {
    list({ commit }, queryParams) {
      return this.$apiService
        .get("/open-posts", { params: queryParams })
        .then((res) => {
          commit("invoices", res?.data?.invoices ?? { data: [], links: [] });
          commit("invoiceTypes", res?.data?.invoiceTypes ?? []);
        });
    },

    update({}, { id, data }) {
      return this.$apiService.put(`/open-posts/${id}`, data);
    },
    updateEmailReminder({}, id) {
      return this.$apiService.patch(`/open-posts-reminder/${id}`);
    },
  },
};
