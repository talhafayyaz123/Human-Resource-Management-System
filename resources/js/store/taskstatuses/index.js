export default {
    namespaced: true,
    state: {
        statuses: [],
        assignees: [],
    },
    getters: {
        statuses: (state) => state.statuses,
        assignees: (state) => state.assignees,
    },
    mutations: {
        statuses: (state, payload) => (state.statuses = payload),
        assignees: (state, payload) => (state.assignees = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/task-status", {
                    params: queryParams,
                })
                .then((res) => {
                    // if setStore is true then set the store
                    if (!!queryParams.setStore) {
                        commit("statuses", res?.data?.data ?? []);
                        commit("assignees", res?.data?.assignees ?? []);
                    }
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/task-status", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/task-status/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.patch(`/task-status/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/task-status/${id}`);
        },
        createStatusLink({}, payload) {
            return this.$apiService.post("/task-transition", payload);
        },
        removeStatusLink({}, payload) {
            return this.$apiService.post("/remove-task-transition", payload);
        },
        getStatusLinks({}, id) {
            return this.$apiService.get(`/get-transitions/${id}`);
        },
        changeStatusOrder({}, payload) {
            return this.$apiService.post(`/order-status`, payload);
        },
    },
};
