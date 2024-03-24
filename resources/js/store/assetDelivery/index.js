export default {
  namespaced: true,
  state: {
    assetDeliveries: {
      data: [],
      links: [],
    },
  },
  getters: {
    assetDeliveries: (state) => state.assetDeliveries,
  },
  mutations: {
    assetDeliveries: (state, payload) => (state.assetDeliveries = payload),
  },
  actions: {
    list({ commit }, queryParams) {
      return this.$apiService
        .get("/asset-deliveries", {
          params: queryParams,
        })
        .then((res) => {
          commit(
            "assetDeliveries",
            res?.data?.asset_deliveries ?? {
              data: [],
              links: [],
            }
          );
        });
    },
    create({}, payload) {
      return this.$apiService.post("/asset-deliveries", payload);
    },
    show({}, id) {
      return this.$apiService.get(`/asset-deliveries/${id}`);
    },
    update({}, { id, data }) {
      return this.$apiService.put(`/asset-deliveries/${id}`, data);
    },
    destroy({}, id) {
      return this.$apiService.delete(`/asset-deliveries/${id}`);
    },
  },
};
