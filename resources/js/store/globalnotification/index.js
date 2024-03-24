export default {
    namespaced: true,
    state: {
        globalNotification: {
            userIds: [],
        },
    },
    getters: {
        globalNotification: (state) => state.globalNotification,
    },
    mutations: {
        globalNotification: (state, payload) =>
            (state.globalNotification = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/global-notification", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "globalNotification",
                        res?.data?.userIds ?? {
                            userIds: [],
                        }
                    );
                    return res;
                });
        },
        create({}, payload) {
            return this.$apiService.post("/global-notification", payload);
        },
    },
};
