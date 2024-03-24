export default {
    methods: {
        /**
         * Fetches the module history based on moduleName and id
         */
        async getModuleHistory() {
            try {
                const response = await this.$store.dispatch(
                    "globalSettings/moduleHistory",
                    {
                        id: this.id,
                        queryParams: {
                            moduleName: this.moduleName,
                            perPage: this.perPage,
                            page: this.page,
                        },
                    }
                );
                this.moduleHistory = [
                    ...this.moduleHistory,
                    ...(response?.data?.data ?? []),
                ];
                this.page = response?.data?.current_page ?? this.page;
                this.total = response?.data?.total ?? this.total;
                this.feeds =response?.data?.feeds ?? [];
            } catch (e) {
                console.log(e);
            }
        },
    },
};
