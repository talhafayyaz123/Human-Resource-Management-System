export default {
    namespaced: true,
    state: {
        fleetCars: {
            data: [],
            links: [],
        },
    },
    getters: {
        fleetCars: (state) => state.fleetCars,
        fleetCarsFilteredByDriverIdNull: (state) =>
            state.fleetCars?.data.filter(
                (fleetCar) => fleetCar.driverId === null
            ),
    },
    mutations: {
        fleetCars: (state, payload) => (state.fleetCars = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/fleet-cars", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "fleetCars",
                        res.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/fleet-cars", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/fleet-cars/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/fleet-cars/${id}`, data);
        },
        destroy({}, id) {
            return this.$apiService.delete(`/fleet-cars/${id}`);
        },
        uploadFiles({}, payload) {
            return this.$apiService.post(`/upload-fleet-documents`, payload, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
        updateFiles({}, payload) {
            return this.$apiService.post(`/update-fleet-documents`, payload, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
        createUVV({}, payload) {
            return this.$apiService.post(`/create-uvv`, payload, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });
        },
    },
};
