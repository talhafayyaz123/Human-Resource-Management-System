export default {
    namespaced: true,

    state: {
        requests: [],
        count: 0,
    },
    getters: {
        requests: (state) => state.requests,
        count: (state) => state.count,
    },
    mutations: {
        licenses: (state, payload) => (state.requests = payload),
        count: (state, payload) => (state.count = payload),
    },

    actions: {
    list({ commit }, queryParams) {
      
        return this.$apiService.get("/cancelation-requests",{
            params: queryParams,
          } ).then((res) => {
            commit("licenses", res.data.CancelRequests?.data ?? []);
            commit("count", res?.data?.count ?? 0);
            return res;
        });
    },
      create({}, payload) {
        return this.$apiService.post("/cancelation-requests", payload);
      },
      show({}, id) {
        return this.$apiService.get(`/cancelation-requests/${id}`);
      },
      update({}, { id, data }) {
        return this.$apiService.put(`/cancelation-requests/${id}`, data);
      },
      destroy({}, id) {
        return this.$apiService.delete(`/cancelation-requests/${id}`);
      },
    },
  };
  