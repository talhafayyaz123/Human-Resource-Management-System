export default {
    namespaced: true,
    state: {
        premiseSystems: {
            data: [],
            links: [],
        },
        cloudSystems: {
            data: [],
            links: [],
        },
        hostingSystems: {
            data: [],
            links: [],
        },
        premiseSystemsCount: 0,
        cloudSystemsCount: 0,
        hostingSystemsCount: 0,
        count: 0,
    },
    getters: {
        premiseSystems: (state) => state.premiseSystems,
        cloudSystems: (state) => state.cloudSystems,
        hostingSystems: (state) => state.hostingSystems,
        premiseSystemsCount: (state) => state.premiseSystemsCount,
        cloudSystemsCount: (state) => state.cloudSystemsCount,
        hostingSystemsCount: (state) => state.hostingSystemsCount,
        count: (state) => state.count,
    },
    mutations: {
        premiseSystems: (state, payload) => (state.premiseSystems = payload),
        cloudSystems: (state, payload) => (state.cloudSystems = payload),
        hostingSystems: (state, payload) => (state.hostingSystems = payload),
        premiseSystemsCount: (state, payload) =>
            (state.premiseSystemsCount = payload),
        cloudSystemsCount: (state, payload) =>
            (state.cloudSystemsCount = payload),
        hostingSystemsCount: (state, payload) =>
            (state.hostingSystemsCount = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/systems", {
                    params: queryParams,
                })
                .then((res) => {
                    if (queryParams.type == "premise") {
                        commit(
                            "premiseSystems",
                            res?.data?.data ?? {
                                data: [],
                                links: [],
                            }
                        );
                        commit("premiseSystemsCount", res?.data?.total ?? 0);
                    } else if (queryParams.type == "hosting") {
                        commit(
                            "hostingSystems",
                            res?.data?.data ?? {
                                data: [],
                                links: [],
                            }
                        );
                        commit("premiseSystemsCount", res?.data?.total ?? 0);
                    } else {
                        commit(
                            "cloudSystems",
                            res?.data?.data ?? {
                                data: [],
                                links: [],
                            }
                        );
                        commit("premiseSystemsCount", res?.data?.total ?? 0);
                    }
                    commit("count", res?.data?.total ?? 0);
                });
        },
        create({}, payload) {
            return this.$apiService.post("/systems", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/systems/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/systems/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/systems/${id}`);
        },
        getShellClients({}) {
            return this.$clientShellApiService.get(`/shell/activeConnections`);
        },
        healthCheck({}, id) {
            return this.$clientShellApiService.get(`/ClientHealthChecks/${id}`);
        },
        download({}, id) {
            return this.$apiService
                .get(`/systems/download/${id}/rdf`)
                .then((response) => {
                    let fileContents = response.data.file;

                    let fileName = response.data.fileName;
                    let blob = new Blob([fileContents], {
                        type: "text/plain",
                    });
                    let url = URL.createObjectURL(blob);
                    let link = document.createElement("a");
                    link.href = url;
                    link.download = fileName;
                    link.click();
                    URL.revokeObjectURL(url);
                });
        },
        search({}, id) {
            return this.$apiService.post(`/systems-search`, { id: id });
        },
    },
};
