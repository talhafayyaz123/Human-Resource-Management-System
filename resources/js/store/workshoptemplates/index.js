export default {
    namespaced: true,
    state: {
        workshopTemplates: {
            data: [],
            links: [],
        },
        selectedWidget: null,
        selectedWidgetType: "",
        inputConfigurations: {
            widgets: {},
            groups: {},
        },
        isDragging: false,
        showPreview: false,
    },
    getters: {
        workshopTemplates: (state) => state.workshopTemplates,
        selectedWidget: (state) => state.selectedWidget,
        selectedWidgetType: (state) => state.selectedWidgetType,
        inputConfigurations: (state) => state.inputConfigurations,
        isDragging: (state) => state.isDragging,
        showPreview: (state) => state.showPreview,
    },
    mutations: {
        workshopTemplates: (state, payload) =>
            (state.workshopTemplates = payload),
        selectedWidget: (state, payload) => (state.selectedWidget = payload),
        inputConfigurations: (state, payload) =>
            (state.inputConfigurations = payload),
        selectedWidgetType: (state, payload) =>
            (state.selectedWidgetType = payload),
        isDragging: (state, payload) => (state.isDragging = payload),
        showPreview: (state, payload) => (state.showPreview = payload),
    },
    actions: {
        list({ commit }, queryParams = {}) {
            return this.$apiService
                .get("/workshop-templates", { params: queryParams })
                .then((res) => {
                    commit(
                        "workshopTemplates",
                        res?.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/workshop-templates", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/workshop-templates/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/workshop-templates/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/workshop-templates/${id}`);
        },
        uploadFiles({}, payload) {
            return this.$apiService.post(
                `/workshop-templates/upload-files`,
                payload,
                {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                }
            );
        },
        exportFile({}, payload) {
            return this.$apiService.post(
                "/workshop-templates/export-file",
                payload
            );
        },
        deleteFile({}, id) {
            return this.$apiService.delete(
                `/workshop-templates/delete-file/${id}`
            );
        },
    },
};
