export default {
    namespaced: true,
    state: {
        serverPools: [],
        serverPoolId: "",
        count: 0,
        fileConfigData: "",
        selectTenetForConfigChange : "",
    },
    getters: {
        serverPools: (state) => state.serverPools,
        count: (state) => state.count,
        serverPoolId: (state) => state.serverPoolId,
        fileConfigData: (state) => state.fileConfigData,
        selectTenetForConfigChange: (state) => state.selectTenetForConfigChange,
    },
    mutations: {
        serverPools: (state, payload) => (state.serverPools = payload),
        count: (state, payload) => (state.count = payload),
        serverPoolId: (state, payload) => (state.serverPoolId = payload),
        fileConfigData: (state, payload) => (state.fileConfigData = payload),
        selectTenetForConfigChange: (state, payload) => (state.selectTenetForConfigChange = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$dhc2ApiService
                .get("/list-dhc2-clients", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("serverPools", res?.data?.data ?? []);
                    commit("count", res?.data?.count ?? 0);
                    return res;
                });
        },
        show({}, queryParams) {
            return this.$dhc2ApiService.get(`/list-dhc2-client-by-id`, {
                params: queryParams,
            });
        },
        update({}, payload) {
            return this.$dhc2ApiService.post(`/update-dhc2-client`, payload);
        },
        destroy({}, payload) {
            return this.$dhc2ApiService.post(`/delete-dhc2-client`, payload);
        },

        attachServerPool({}, payload) {
            return this.$apiService.post(`/cloud-pool`, payload);
        },

        detachServerPool({}, payload) {
            return this.$apiService.post(`/cloud-pool/delete`, payload);
        },
        refreshServerPool({}, payload) {
            return this.$dhc2ApiService.post(`/restart`, payload);
        },
        getServerPods({}, params) {
            return this.$dhc2ApiService.get(`/list-elo-pods`, {
                params: params,
            });
        },

        getServer({ commit }, id) {
            return this.$apiService
                .get(`/get-server/${id}`)
                .then((res) => {
                    commit("serverPoolId", res?.data?.data?.server_pool_id);
                })
                .catch((e) => {});
        },
        createTanent({}, payload) {
            return this.$dhc2ApiService
                .post(`/create-tenant`, payload)
                .then((res) => {})
                .catch((e) => {
                    console.log(e);
                });
        },
        deleteTanent({}, payload) {
            return this.$dhc2ApiService
                .post(`/delete-tenant`, payload)
                .then((res) => {})
                .catch((e) => {
                    console.log(e);
                });
        },
        deleteRepository({}, payload) {
            return this.$dhc2ApiService
                .post(`/delete-repository`, payload)
                .then((res) => {})
                .catch((e) => {
                    console.log(e);
                });
        },

        createRepository({}, payload) {
            return this.$dhc2ApiService
                .post(`/create-repository`, payload)
                .then((res) => {})
                .catch((e) => {
                    console.log(e);
                });
        },
        installBusinessPlan({}, payload) {
            return this.$dhc2ApiService
                .post(`/install-business-solutions`, payload)
                .then((res) => {

                    return res;
                })
                .catch((e) => {
                    console.log(e);
                });
        },

        restartRepository({}, payload) {
            return this.$dhc2ApiService
                .post(`/restart-repository`, payload)
                .then((res) => {})
                .catch((e) => {
                    console.log(e);
                });
        },
        
        fileConfigData({ commit }, data) {
            commit("fileConfigData", data);
        },
        selectedTenentData({ commit }, data) {
            commit("selectTenetForConfigChange", data);
        },
    },
};
