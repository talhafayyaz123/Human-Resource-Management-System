<template>
    <PageHeader :items="breadcrumbItems" />
    <CreateEditEmployeeInterviewForm
        :recoId="$route.params.id"
        :isEdit="true"
    />
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
                    text: "Edit",
                    active: true,
                },
            ],
        };
    },
};
</script>
