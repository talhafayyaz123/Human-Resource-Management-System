export default {
    namespaced: true,
    state: {
        templates: [],
    },
    getters: {
        templates: (state) => state.templates,
    },
    mutations: {
        templates: (state, payload) => (state.templates = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$mailApiService
                .get("/mail-service/list-mail-templates", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "templates",
                        typeof res?.data === "object" ? res?.data : []
                    );
                });
        },
        create({}, payload) {
            return this.$mailApiService.post(
                "/mail-service/create-mail-template",
                payload
            );
        },
        show({}, id) {
            return this.$mailApiService.get(
                "/mail-service/list-mail-template-by-id",
                {
                    params: {
                        id: id,
                    },
                }
            );
        },
        update({}, payload) {
            return this.$mailApiService.post(
                "/mail-service/update-mail-template",
                payload
            );
        },
        destroy({}, payload) {
            return this.$mailApiService.post(
                "/mail-service/delete-mail-template",
                payload
            );
        },
        mailTemplateAssignmentList() {
            return this.$apiService.get("/mail-template-assignments");
        },
        mailTemplateAssignmentSave({}, payload) {
            return this.$apiService.post("/mail-template-assignments", payload);
        },
        sendMailTemplate({}, payload) {
            return this.$mailApiService.post(
                "/mail-service/send-mail",
                payload
            );
        },
        mailTemplateByModule({}, queryParams) {
            return this.$apiService.get(
                "/mail-template-assignments/get-by-module",
                {
                    params: queryParams,
                }
            );
        },
    },
};
