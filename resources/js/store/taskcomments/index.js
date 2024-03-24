export default {
    namespaced: true,
    state: {},
    getters: {},
    mutations: {},
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService.get("/my-task/comments", {
                params: queryParams,
            });
        },
        create({}, payload) {
            return this.$apiService.post("/my-task/comments", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/my-task/comments/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.patch(`/my-task/comments/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/my-task/comments/${id}`);
        },
        getLatest({}, payload) {
            return this.$apiService.post("task/latest-comment", payload);
        },
    },
};
