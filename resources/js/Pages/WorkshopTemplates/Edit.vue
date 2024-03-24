<template>
    <Main :action="'update'" :actionForm="form" />
</template>

<script>
import Main from "./Components/Main.vue";

export default {
    name: "WorkshopTemplatesEdit",
    components: {
        Main,
    },
    data() {
        return {
            form: {
                templateName: "",
                templateVersion: "",
                consultant: "",
                assignedProducts: [],
            },
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "workshopTemplates/show",
                this.$route.params.id
            );
            // document.title =  "Edit Workshop Templates " + response?.data?.data?.templateNumber ?? "";
            this.form = response?.data?.data ?? this.form;
            this.form["createdAt"] = new Date(this.form.createdAt);
        } catch (e) {
            console.log(e);
        }
        finally {
            this.$store.commit("showLoader", false);
        }
    },
};
</script>

<style scoped>
.color-blue {
    color: #2957a4;
}
.bg-blue {
    background-color: #2957a4;
}

th,
td {
    border: 1px solid black;
}
</style>
