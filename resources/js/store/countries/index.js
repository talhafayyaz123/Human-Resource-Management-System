export default {
  namespaced: true,
  state: {
    countries: {
      data: [],
      links: [],
    },
    count: 0,
  },
  getters: {
    countries: (state) => state.countries,
  },
  mutations: {
    countries: (state, payload) => (state.countries = payload),
  },
  actions: {
    list({ commit }, queryParams) {
      return this.$apiService
        .get("/countries", {
          params: queryParams,
        })
        .then((res) => {
          commit("countries", res.data);
          return res;
        });
    },
    create({}, payload) {
      return this.$apiService.post("/countries", payload);
    },
    show({}, id) {
      return this.$apiService.get(`/countries/${id}`);
    },
    update({}, { id, data }) {
      return this.$apiService.put(`/countries/${id}`, data);
    },
    destroy({}, id) {
      return this.$apiService.delete(`/countries/${id}`);
    },
    uploadCsv({}, payload) {
      return this.$apiService.post(`/upload-countries`, payload);
    },
    storeCsv({}, payload) {
      return this.$apiService.post("/store-countries-csv", payload);
    },
    getAllCountries({}) {
      return this.$apiService.get("/get-all-countries");
    },
  },
};
