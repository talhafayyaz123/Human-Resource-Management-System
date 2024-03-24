export default {
  namespaced: true,
  state: {
    performanceRecords: {
      data: [],
      links: [],
    },
    count: 0,
  },
  getters: {
    performanceRecords: (state) => state.performanceRecords,
    count: (state) => state.count,
  },
  mutations: {
    performanceRecords: (state, payload) =>
      (state.performanceRecords = payload),
    count: (state, payload) => (state.count = payload),
  },
  actions: {
    list({ commit }, queryParams) {
      return this.$apiService
        .get("/performance-records", {
          params: queryParams,
        })
        .then((res) => {
          commit(
            "performanceRecords",
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
      return this.$apiService.post("/store-performance-records", payload);
    },
    createManually({}, payload) {
      return this.$apiService.post(
        "/store-performance-records/manual",
        payload
      );
    },
    show({}, queryParams) {
      return this.$apiService.get(`/performance-records/show`, {
        params: queryParams,
      });
    },
    import({}, payload) {
      return this.$apiService.post("/import-performance-records", payload, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
    },
    update({}, { id, data }) {
      return this.$apiService.put(`/performance-records/${id}`, data);
    },
    destroy({}, id) {
      return this.$apiService.delete(`/performance-records/${id}`);
    },
    editEntries({}, { id, data }) {
      return this.$apiService.put(
        `performance-record-entries/update/${id}`,
        data
      );
    },
    addEntries({}, { id, data }) {
      return this.$apiService.post(
        `performance-record-entries/store/${id}`,
        data
      );
    },
    changeOrder({}, { id, data }) {
      return this.$apiService.post(
        `performance-record-entries/change-order/${id}`,
        data
      );
    },
    createInvoices({}, payload) {
      return this.$apiService.post(
        `/performance-records/create-multiple`,
        payload
      );
    },
    download({}, id) {
      return this.$apiService
        .get(`/performance-record/download-csv/${id}`)
        .then((response) => {
          // Get the data from the response
          const data = response.data;
          let fileName = "performace-record.csv";
          const fileNameHeader = response.headers["filename"] ?? "";
          if (fileNameHeader) {
            fileName = fileNameHeader;
          }

          // Create a Blob from the data
          const blob = new Blob([data], { type: "text/csv" });

          // Create a URL for the file
          const url = window.URL.createObjectURL(blob);

          // Create a link element
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", fileName);

          // Add the link to the DOM and click it to initiate the download
          document.body.appendChild(link);
          link.click();
        });
    },
    showEloRequestForPerformanceRecord({}, payload) {
      return this.$apiService.post(`/performance-record-elo-request`, payload);
    },
  },
};
