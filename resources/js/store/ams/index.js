export default {
    namespaced: true,
    state: {
        ams: {
            data: [],
            links: [],
        },
        count: 0,
    },
    getters: {
        ams: (state) => state.ams,
        count: (state) => state.count,
    },
    mutations: {
        ams: (state, payload) => (state.ams = payload),
        count: (state, payload) => (state.count = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/application-management-services", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "ams",
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
                "/application-management-services",
                payload
            );
        },
        show({}, id) {
            return this.$apiService.get(
                `/application-management-services/${id}`
            );
        },
        update({}, { id, data }) {
            return this.$apiService.put(
                `/application-management-services/${id}`,
                data
            );
        },
        destroy({}, id) {
            return this.$apiService.delete(
                `/application-management-services/${id}`
            );
        },
        getByCustomer({}, id) {
            return this.$apiService.get(
                `/application-management-services/get-by-customer/${id}`
            );
        },
        getByCustomerAndAttachment({}, id) {
            return this.$apiService.get(
                `/application-management-services/get-by-customer-and-attachment/${id}`
            );
        },
        showRevamp({}, id) {
            return this.$apiService.get(`/application-management-show/${id}`);
        },
        getTickets({}, { id, queryParams }) {
            return this.$apiService.get(`/get-tickets/${id}`, {
                params: queryParams,
            });
        },
        timeTrackerRecordsByAMS({}, data) {
            return this.$apiService.post("/ams/time-tracker", data);
        },
    },
};
