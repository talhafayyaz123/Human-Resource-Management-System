import axios from "axios";

export default {
    namespaced: true,
    state: {
        items: [],
        documentServices: [],
        count: 0,
        selectedFile: null,
        openedFiles: [],
        unsavedFiles: [],
        previewFiles: [],
    },
    getters: {
        selectedFile: (state) => state.selectedFile,
        openedFiles: (state) => state.openedFiles,
        items: (state) => state.items,
        documentServices: (state) => state.documentServices,
        count: (state) => state.count,
        unsavedFiles: (state) => state.unsavedFiles,
        previewFiles: (state) => state.previewFiles,
    },
    mutations: {
        selectedFile: (state, payload) => (state.selectedFile = payload),
        openedFiles: (state, payload) => (state.openedFiles = payload),
        items: (state, payload) => (state.items = payload),
        documentServices: (state, payload) =>
            (state.documentServices = payload),
        count: (state, payload) => (state.count = payload),
        unsavedFiles: (state, payload) => (state.unsavedFiles = payload),
        previewFiles: (state, payload) => (state.previewFiles = payload),
    },
    actions: {
        download({}, params) {
            return this.$docApiService
                .get(`/files/` + params.id)
                .then((response) => {
                    // Get the data from the response
                    const data = response.data;

                    // Create a Blob from the data
                    const blob = new Blob([data]);

                    // Create a URL for the file
                    const url = window.URL.createObjectURL(blob);

                    // Create a link element
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", params.name);

                    // Add the link to the DOM and click it to initiate the download
                    document.body.appendChild(link);
                    link.click();
                });
        },
        list({ commit }, queryParams) {
            return this.$docApiService
                .get("/list-files", {
                    params: queryParams,
                })
                .then((res) => {
                    commit("documentServices", res?.data ?? []);
                    commit("count", res?.data?.count ?? 0);
                    return res;
                });
        },
        create({}, payload) {
            return this.$docApiService.post(`/upload-file`, payload, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
        show({}, id) {
            return this.$docApiService.get(`/list-file-by-id?id=${id}`);
        },
        destroy({}, payload) {
            return this.$docApiService.post(`/delete-file`, payload);
        },
        processTemplate({}, payload) {
            return this.$docApiService
                .post(`/process-template`, payload.data, {
                    responseType: "blob",
                })
                .then((response) => {
                    // Get the data from the response
                    const data = response.data;

                    // Create a Blob from the data
                    const blob = new Blob([data], {
                        type: `application/${
                            payload.documentType === "pdf"
                                ? "pdf"
                                : "vnd.openxmlformats-officedocument.wordprocessingml.document"
                        }`,
                    });

                    // Create a URL for the file
                    const url = window.URL.createObjectURL(blob);

                    // Create a link element
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", payload.filename);

                    // Add the link to the DOM and click it to initiate the download
                    document.body.appendChild(link);
                    link.click();
                    return blob;
                });
        },
        processTemplateBase64({}, payload) {
            return this.$docApiService
                .post(`/process-template`, payload.data, {
                    responseType: "blob",
                })
                .then((response) => {
                    // Get the data from the response
                    const data = response.data;

                    // Create a Blob from the data
                    const blob = new Blob([data], {
                        type: `application/${
                            payload.documentType === "pdf"
                                ? "pdf"
                                : "vnd.openxmlformats-officedocument.wordprocessingml.document"
                        }`,
                    });

                    return blob;
                });
        },
        downloadOriginalFile({}, payload) {
            return this.$docApiService
                .get(`/files/${payload.id}`, {
                    responseType: "blob",
                })
                .then((response) => {
                    console.log({ response });
                    // Get the data from the response
                    const data = response.data;

                    // Create a Blob from the data
                    const blob = new Blob([data], {
                        type: `application/${
                            payload.documentType === "pdf"
                                ? "pdf"
                                : "vnd.openxmlformats-officedocument.wordprocessingml.document"
                        }`,
                    });

                    // Create a URL for the file
                    const url = window.URL.createObjectURL(blob);

                    // Create a link element
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", payload.filename);

                    // Add the link to the DOM and click it to initiate the download
                    document.body.appendChild(link);
                    link.click();
                });
        },
        documentPreviewFiles({}, id) {
            return this.$docApiService.get(`/files-preview/${id}`, {
                responseType: "blob",
            });
        },
        variables({}, payload) {
            return this.$docApiService.post("/get-template-variables", payload);
        },
    },
};
