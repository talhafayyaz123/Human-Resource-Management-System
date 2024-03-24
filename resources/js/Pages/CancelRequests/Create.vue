<template>
    <PageHeader :items="breadcrumbItems" />
    <CreateEditRequestFormVue />
</template>

<script>
import CreateEditRequestFormVue from "./Components/CreateEditRequestForm.vue"
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        PageHeader,
        CreateEditRequestFormVue,
    },
    data(){
        return{
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Requests",
                    to: "/cancel-requests",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
        }
    }
};
</script>
