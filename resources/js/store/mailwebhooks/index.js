export default {
    namespaced: true,
    state: {
        mailWebhooks: [],
    },
    getters: {
        mailWebhooks: (state) => state.mailWebhooks,
    },
    mutations: {
        mailWebhooks: (state, payload) => (state.mailWebhooks = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$mailApiService
                .get("/mail-service/list-webhooks", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "mailWebhooks",
                        typeof res?.data === "object" ? res?.data : []
                    );
                });
        },
        create({}, payload) {
            return this.$mailApiService.post(
                "/mail-service/create-webhook",
                payload
            );
        },
        show({}, id) {
            return this.$mailApiService.get(
                "/mail-service/list-webhook-by-id",
                {
                    params: {
                        id: id,
                    },
                }
            );
        },
        update({}, payload) {
            return this.$mailApiService.post(
                "/mail-service/update-webhook",
                payload
            );
        },
        destroy({}, payload) {
            return this.$mailApiService.post(
                "/mail-service/delete-webhook",
                payload
            );
        },
    },
};
