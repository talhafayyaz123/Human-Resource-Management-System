<template>
    <button
        class="secondary-btn px-3 py-2 self-center"
        @click.prevent="toggleModal = true"
    >
        {{ $t("Add Products") }}
    </button>

    <SelectOfferProductsModal
        v-if="toggleModal"
        :type="type"
        :companyId="companyId"
        @selected="
            $emit('selected', $event);
            toggleModal = false;
        "
        @toggle-modal="toggleModal = $event"
    />
</template>

<script>
import SelectOfferProductsModal from "@/Components/SelectOfferProductsModal.vue";

export default {
    emits: ["selected"],
    props: {
        type: {
            type: String,
            default: "offer",
        },
        companyId: {
            type: String,
        },
    },
    components: {
        SelectOfferProductsModal,
    },
    data() {
        return {
            toggleModal: false,
        };
    },
};
</script>

<style scoped></style>
