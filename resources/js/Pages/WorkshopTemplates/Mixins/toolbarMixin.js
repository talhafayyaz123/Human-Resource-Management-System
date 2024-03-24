import { mapGetters } from "vuex";

export default {
    data() {
        return {
            showToolbar: false, //responsible for toggling the action toolbar in/around the row/column
        };
    },
    computed: {
        ...mapGetters("workshopTemplates", ["showPreview"]),
    },
    methods: {
        /**
         * sets showToolbar to true
         * showToolbar shows the toolbar in/around the row/column, contains row/column actions
         */
        addToolbar() {
            // if the showPreview state is set to true then prevent toolbar addition
            if (!this.showPreview) {
                this.showToolbar = true;
            }
        },
        /**
         * sets showToolbar to flase
         * showToolbar shows the toolbar in/around the row/column, contains row/column actions
         */
        removeToolbar() {
            this.showToolbar = false;
        },
    },
};
