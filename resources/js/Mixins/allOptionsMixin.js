import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("surveys", ["allOptions", "allConditions"]),
    },
    data() {
        return {
            allOptionsCopy: { ...this.allOptions },
            allConditionsCopy: { ...this.allConditions },
        };
    },
    watch: {
        // when the allOptions state changes, set the latest value to 'allOptionsCopy'
        allOptions: {
            handler: function (val) {
                // check if 'val' is spreadable
                if (typeof val === "object") this.allOptionsCopy = { ...val };
            },
            deep: true,
        },
        // when the allConditions state changes, set the latest value to 'allConditionsCopy'
        allConditions: {
            handler: function (val) {
                // check if 'val' is spreadable
                if (typeof val === "object")
                    this.allConditionsCopy = { ...val };
            },
            deep: true,
        },
    },
    methods: {
        /**
         * sets the allOptions state in survey store to the value of 'allOptionsCopy'
         */
        setAllOptions() {
            this.$store.commit("surveys/allOptions", {
                ...this.allOptionsCopy,
            });
        },
        /**
         * mutates the 'allOptions' state in surveys based on the option
         * @param {option} option that is to be added to the 'allOptions' state in surveys
         */
        addOptionMixin(option) {
            // check if the option is spreadable
            if (typeof option === "object")
                this.allOptionsCopy[option?.id] = { ...option };
            // set the state
            this.setAllOptions();
        },
        /**
         *
         * @param {id} id of the option to be removed from allOptions state
         */
        removeOptionMixin(id) {
            // wrap in try/catch because 'delete' can fail
            try {
                delete this.allOptionsCopy[id];
                // set the state
                this.setAllOptions();
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * sets the allConditions state in survey store to the value of 'allConditionsCopy'
         */
        setAllConditions() {
            this.$store.commit("surveys/allConditions", {
                ...this.allConditionsCopy,
            });
        },
        /**
         * mutates the 'allConditions' state in surveys based on the option
         * @param {condition} condition that is to be added to the 'allConditions' state in surveys
         */
        addConditionMixin(condition) {
            // check if the option is spreadable
            if (typeof condition === "object")
                this.allConditionsCopy[condition?.id] = { ...condition };
            // set the state
            this.setAllConditions();
        },
        /**
         *
         * @param {id} id of the condition to be removed from allConditions state
         */
        removeConditionMixin(id) {
            // wrap in try/catch because 'delete' can fail
            try {
                delete this.allConditionsCopy[id];
                // set the state
                this.setAllConditions();
            } catch (e) {
                console.log(e);
            }
        },
    },
};
