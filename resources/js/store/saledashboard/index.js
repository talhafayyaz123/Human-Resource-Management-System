export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    offersStatistics({}, queryParams) {
      return this.$apiService("/sale-dashboard/offer-statistics", {
        params: queryParams,
      });
    },
    productProportionsStatistics({}, queryParams) {
      return this.$apiService("/sale-dashboard/product-proportion-statistics", {
        params: queryParams,
      });
    },
    leadChannelsStatistics({}, queryParams) {
      return this.$apiService("/sale-dashboard/lead-channels-statistics", {
        params: queryParams,
      });
    },
    offersData({}, queryParams) {
      return this.$apiService("/sale-dashboard/offer-data", {
        params: queryParams,
      });
    },
  },
};
