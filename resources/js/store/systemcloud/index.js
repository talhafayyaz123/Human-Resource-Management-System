export default {
    namespaced: true,
    state: {
        systemCloud: {
            data: [],
        },
        systemCloudForTenant: {
            data: [],
        },
        count: 0,
    },
    getters: {
        systemCloud: (state) => state.systemCloud,
        systemCloudForTenant: (state) => state.systemCloudForTenant,
        count: (state) => state.count,
    },
    mutations: {
        systemCloud: (state, payload) => (state.systemCloud = payload),
        count: (state, payload) => (state.count = payload),
        systemCloudForTenant: (state, payload) =>
            (state.systemCloudForTenant = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/cloud-infrastructure", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "systemCloud",
                        res?.data?.data ?? {
                            data: [],
                        }
                    );
                    commit("count", res?.data?.total ?? 0);
                });
        },
        listCloud({ commit }, queryParams) {
            return this.$apiService
                .get("/system-clouds/tenant", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "systemCloudForTenant",
                        res?.data?.data ?? {
                            data: [],
                        }
                    );
                });
        },
        show({}, id) {
            return this.$apiService.get(`/cloud-infrastructure/${id}`);
        },
        showTenant({}, id) {
            return this.$apiService.get(`/system-clouds/tenant/${id}`);
        },
        deleteTenant({}, id) {
            return this.$apiService.delete(`/system-clouds/tenant/${id}`);
        },
        create({}, payload) {
            return this.$apiService.post("/cloud-infrastructure", payload);
        },
        createTenantRepository({}, payload) {
            return this.$apiService.post("/system-cloud-infrastructure/tenant/repository/create", payload);
        },
        createTenant({}, payload) {
            return this.$apiService.post("/save/system-cloud/tenant", payload);
        },
        updateTenant({}, { id, data }) {
            return this.$apiService.put(`/system-cloud/tenant/${id}`, data);
        },
        showTenants({}, id) {
            return this.$apiService.get(
                `/system-cloud-infrastructure/tenants/${id}`
            );
        },
        deleteInfrastrctureTenant({}, id) {
            return this.$apiService.delete(
                `/system-cloud-infrastructure/tenants/${id}`
            );
        },
        deleteTenantRepository({}, id) {
            return this.$apiService.delete(
                `/system-cloud-infrastructure/tenant/repository/${id}`
            );
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/cloud-infrastructure/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/cloud-infrastructure/${id}`);
        },
        getSystemServers({}, id) {
            return this.$apiService.get(`/get-server/${id}`);
        },
        getSystemTenant({}, id) {
            return this.$apiService.get(`/system-cloud/tenant/${id}`);
        },
        businessSolutionList({},$id) {
           
            return this.$apiService.get(`/elo_business_solution/list`);
        },
    },
};
