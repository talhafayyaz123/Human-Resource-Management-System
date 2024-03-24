<template>
    <PageHeader :items="breadcrumbItems" />
    <CreateEditEmployeeInterviewForm />
</template>

<script>
import CreateEditEmployeeInterviewForm from "./Components/CreateEditEmployeeInterviewForm.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        PageHeader,
        CreateEditEmployeeInterviewForm,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Management"),
                    to: "/employee-interview",
                },
                {
                    text: this.$t("Employee Interview"),
                    to: "/employee-interview",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
        };
    },
};
</script>
