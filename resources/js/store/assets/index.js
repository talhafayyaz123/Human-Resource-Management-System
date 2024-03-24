export default {
    namespaced: true,
    state: {
        assets: {
            data: [],
            links: [],
        },
        users:""
    },
    getters: {
        assets: (state) => state.assets,
    },
    mutations: {
        assets: (state, payload) => (state.assets = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/assets", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "assets",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/assets", payload);
        },
        createFeed({}, payload) {
            return this.$apiService.post("/my-feed", payload);
        },
        listEmployees({ commit }, queryParams) {
            return this.$authApiService
                .get("/list-users?type=employee")
                .then((res) => {
                    // commit("users", res?.data?.data ?? []);
                    return res;
                });
        },
        getModuleFeed({}, payload) {
            return this.$apiService.post("/my-feed/module-stream", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/assets/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/assets/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/assets/${id}`);
        },
        myAssets({}, queryParams) {
            return this.$apiService.get("/my-assets", {
                params: queryParams,
            });
        },
    },
};
