export default {
    namespaced: true,
    state: {
        continuousImprovementProcesses: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        continuousImprovementProcesses: (state) =>
            state.continuousImprovementProcesses,
        count: (state) => state.count,
    },
    mutations: {
        continuousImprovementProcesses: (state, payload) =>
            (state.continuousImprovementProcesses = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/continuous-improvement-processes", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "continuousImprovementProcesses",
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
            return this.$apiService.post(
                "/continuous-improvement-processes",
                payload,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );
        },
        show({}, id) {
            return this.$apiService.get(
                `/continuous-improvement-processes/${id}`
            );
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/continuous-improvement-processes/${id}`,
                data,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(
                `/continuous-improvement-processes/${id}`
            );
        },
        approvalListing() {
            return this.$apiService.get(
                `/continuous-improvement-processes/get-approval-listing`
            );
        },
        setApprovalStatus({}, payload) {
            return this.$apiService.post(
                `/continuous-improvement-processes/set-approval-status`,
                payload
            );
        },
    },
};
