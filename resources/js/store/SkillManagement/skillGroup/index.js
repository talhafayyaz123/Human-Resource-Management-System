export default {
  namespaced: true,
  state: {
    skillGroup: {
      data: [],
      links: [],
    },
  },
  getters: {
    skillGroup: (state) => state.skillGroup,
  },
  mutations: {
    skillGroup: (state, payload) => (state.skillGroup = payload),
  },
  actions: {
    list({ commit }, queryParams) {
      return this.$apiService
        .get("/skill-group", {
          params: queryParams,
        })
        .then((res) => {
          commit(
            "skillGroup",
            res?.data?.skillGroup ?? {
              data: [],
              links: [],
            }
          );
        });
    },
    create({}, payload) {
      return this.$apiService.post("/skill-group", payload);
    },
    show({}, id) {
      return this.$apiService.get(`/skill-group/${id}`);
    },
    update({}, { id, data }) {
      return this.$apiService.put(`/skill-group/${id}`, data);
    },
    destroy({}, id) {
      return this.$apiService.delete(`/skill-group/${id}`);
    },
  },
};
