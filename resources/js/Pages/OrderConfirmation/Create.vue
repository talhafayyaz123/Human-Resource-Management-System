<template>
    <div class="order-confirmation-page">
        <PageHeader :items="breadcrumbItems" class="order-conf-page-header" />
        <Create :module="'order'" />
    </div>
</template>

<script>
import Create from "../Offers/Create.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        Create,
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
                    text: "Sales",
                    to: "/order-confirmation",
                },
                {
                    text: "Order Confirmation",
                    to: "/order-confirmation",
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

<style scoped></style>
