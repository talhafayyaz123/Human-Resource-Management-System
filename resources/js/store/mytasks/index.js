export default {
    namespaced: true,
    state: {
        myTasks: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        myTasks: (state) => state.myTasks,
        count: (state) => state.count,
    },
    mutations: {
        myTasks: (state, payload) => (state.myTasks = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/my-tasks", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "myTasks",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                    commit("count", res?.data?.total ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/my-tasks", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/my-tasks/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.patch(`/my-tasks/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/my-tasks/${id}`);
        },
        changeStatus({}, payload) {
            return this.$apiService.post(`/my-task/update`, payload);
        },
        returnTask({}, payload) {
            return this.$apiService.post(`/return-task`, payload);
        },
        handOver({}, payload) {
            return this.$apiService.post(`/hand-over`, payload);
        },
        assumeTask({}, payload) {
            return this.$apiService.post(`/assume-task`, payload);
        },
        changeTaskOrder({}, payload) {
            return this.$apiService.post(`/order-task`, payload);
        },
        resubmit({}, payload) {
            return this.$apiService.post(`/my-task/resubmit`, payload);
        },
        imageUpload({}, payload) {
            return this.$apiService.post("/image-upload", payload, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
    },
};
