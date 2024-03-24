export default {
    namespaced: true,

    state: {
        requests: [],
        count: 0,
    },
    getters: {
        requests: (state) => state.requests,
        count: (state) => state.count,
    },
    mutations: {
        licenses: (state, payload) => (state.requests = payload),
        count: (state, payload) => (state.count = payload),
    },

    actions: {
    list({ commit }, queryParams) {
        return this.$apiService.get("/product-store-request",{
            params: queryParams,
          } ).then((res) => {
            commit("licenses", res?.data?.productStoreRequests ?? []);
            commit("count", res?.data?.count ?? 0);
            return res;
        });
    },


      create({}, payload) {
        return this.$apiService.post("/product-store-request", payload);
      },
      show({}, id) {
        return this.$apiService.get(`/product-store-request/${id}`);
      },
      update({}, { id, data }) {
        return this.$apiService.put(`/product-store-request/${id}`, data);
      },
      destroy({}, id) {
        return this.$apiService.delete(`/product-store-request/${id}`);
      },

      downloadRequestAttachment({}, { id, queryParams }) {
        this.$apiService
            .get(`/product-store-request-comment/download/${id}`, { responseType: "blob" })
            .then((response) => {
                const contentType = response.headers["content-type"];
                const url = window.URL.createObjectURL(
                    new Blob([response.data], { type: contentType })
                );
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", queryParams.name);
                document.body.appendChild(link);
                link.click();
                link.remove();
            });
    },
    downloadRequestAttachmentBase64({}, { id, queryParams }) {
      return this.$apiService.get(`/product-store-request-comment/download/${id}`, {
          responseType: "blob",
      });
  },
    },
  };
  