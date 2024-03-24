export default {
    props: {
        filterBy: {
            type: String,
        },
        month: {
            type: Object,
        },
        year: {
            type: String,
        },
        companyId: {
            type: String,
        },
    },
    watch: {
        filterBy: {
            handler: function () {
                this.triggerFetchData();
            },
            deep: true,
        },
        month: {
            handler: function () {
                this.triggerFetchData();
            },
            deep: true,
        },
        year: {
            handler: function () {
                this.triggerFetchData();
            },
            deep: true,
        },
        companyId: {
            handler: function () {
                this.triggerFetchData();
            },
            deep: true,
        },
    },
    methods: {
        /**
         * trigger the fetchData method after adding a check for existance
         */
        triggerFetchData() {
            if (typeof this.fetchData === "function") this.fetchData();
            if (typeof this.getOfferData === "function") this.getOfferData();
            if (typeof this.getOrderData === "function") this.getOrderData();
        },
    },
    mounted() {
        this.triggerFetchData();
    },
};
