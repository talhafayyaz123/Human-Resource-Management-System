export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    list({}, queryParams) {
      return this.$apiService
        .get("/licenses", {
          params: queryParams,
        })
        .then((res) => {
          return res?.data;
        });
    },
    create({}, payload) {
      return this.$apiService.post("/licenses", payload);
    },
    show({}, id) {
      return this.$apiService.get(`/licenses/${id}`);
    },
    update({}, { id, data }) {
      return this.$apiService.put(`/licenses/${id}`, data);
    },
    destroy({}, id) {
      return this.$apiService.delete(`/licenses/${id}`);
    },
    licenseStatistics({}, id) {
      return this.$apiService.get(`/licenses/license-statistics/${id}`);
    },
  },
};
