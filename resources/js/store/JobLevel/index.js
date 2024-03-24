export default {
  namespaced: true,
  state: {
    jobLevel: {
      data: [],
      links: [],
    },
  },
  getters: {
    jobLevel: (state) => state.jobLevel,
  },
  mutations: {
    jobLevel: (state, payload) => (state.jobLevel = payload),
  },
  actions: {
    list({ commit }, queryParams) {
      return this.$apiService
        .get("/job-level", {
          params: queryParams,
        })
        .then((res) => {
          commit(
            "jobLevel",
            res?.data?.jobLevel ?? {
              data: [],
              links: [],
            }
          );
        });
    },
    create({}, payload) {
      return this.$apiService.post("/job-level", payload);
    },
    show({}, id) {
      return this.$apiService.get(`/job-level/${id}`);
    },
    update({}, { id, data }) {
      return this.$apiService.put(`/job-level/${id}`, data);
    },
    destroy({}, id) {
      return this.$apiService.delete(`/job-level/${id}`);
    },
  },
};
