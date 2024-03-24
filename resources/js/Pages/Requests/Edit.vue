<template>
    <PageHeader :items="breadcrumbItems" />
    <CreateEditRequestFormVue :recordId="$route.params.id" />
</template>

<script>
import CreateEditRequestFormVue from "./Components/CreateEditRequestForm.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        CreateEditRequestFormVue,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Requests",
                    to: "/requests",
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
