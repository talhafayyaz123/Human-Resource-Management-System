<template>
    <div class="order-confirmation-page">
        <PageHeader :items="breadcrumbItems" class="order-conf-page-header" />
        <Edit :module="'order'" />
    </div>
</template>

<script>
import Edit from "../Offers/Edit.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        Edit,
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
                    text: "Edit",
                    active: true,
                },
            ],
        };
    },
};
</script>

<style scoped></style>
