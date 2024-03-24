export default {
    namespaced: true,
    state: {
        eventNames: []
    },
    getters: {
        eventNames: (state) => state.eventNames
    },
    mutations: {
        eventNames: (state, payload) =>
            (state.eventNames = payload)
    },
    actions: {
        list({ commit }) {
            return this.$licenseApiService
                .post("/list-event-names")
                .then((res) => {
                    commit("eventNames", res?.data ?? []);
                    return res;
                });
        },
        create({ }, payload) {
            return this.$licenseApiService.post(`/create-event-name`, payload);
        },
        show({ }, payload) {
            return this.$licenseApiService.post(`/list-event-name-by-id`, payload);
        },
        update({ },  payload ) {
            return this.$licenseApiService.post(`/update-event-name`, payload);
        },
        destroy({ }, payload ) {
            return this.$licenseApiService.post(`/delete-event-name`, payload);
        },
    },
};
