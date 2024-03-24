<template>
    <div class="order-confirmation-page">
        <PageHeader :items="breadcrumbItems" class="order-conf-page-header" />
        <Show :module="'order'" />
    </div>
</template>

<script>
import Show from "../Offers/Show.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        Show,
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
                    to: "/order-confirmation?page="+this.$route.query.page,
                },
                {
                    text: "Show",
                    active: true,
                },
            ],
        };
    },
    
};
</script>

<style scoped></style>
