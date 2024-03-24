export default {
    namespaced: true,
    state: {
        fleetDrivers: {
            data: [],
            links: [],
        },
    },
    getters: {
        fleetDrivers: (state) => state.fleetDrivers,
    },
    mutations: {
        fleetDrivers: (state, payload) => (state.fleetDrivers = payload),
    },
    actions: {
        list({ commit }, queryParams) {
            return this.$apiService
                .get("/fleet-drivers", {
                    params: queryParams,
                })
                .then((res) => {
                    commit(
                        "fleetDrivers",
                        res.data ?? {
                            data: [],
                            links: [],
                        }
                    );
                });
        },
        create({}, payload) {
            return this.$apiService.post("/fleet-drivers", payload);
        },
        show({}, id) {
            return this.$apiService.get(`/fleet-drivers/${id}`);
        },
        update({}, { id, data }) {
            return this.$apiService.put(`/fleet-drivers/${id}`, data);
        },
        vehicleClasses({}) {
            return this.$apiService.get("/vehicle-classes");
        },
        driverCarHistory({}, queryParams) {
            return this.$apiService.get("/fleet-driver-history", {
                params: queryParams,
            });
        },
    },
};
