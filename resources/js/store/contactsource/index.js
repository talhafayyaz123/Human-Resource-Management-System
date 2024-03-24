export default {
    namespaced: true,
    state: {
        contactSources: {
            data: [],
            links: [],
        },
    },
    getters: {
        contactSources: (state) => state.contactSources,
    },
    mutations: {
        contactSources: (state, payload) => (state.contactSources = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/contact-report-source", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "contactSources",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/contact-report-source", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/contact-report-source/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/contact-report-source/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/contact-report-source/${id}`);
        },
    },
};
