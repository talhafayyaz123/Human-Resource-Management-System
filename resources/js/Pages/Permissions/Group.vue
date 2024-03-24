<template>
    <div
        v-if="groupTemp"
        class="flex flex-wrap -mr-6 pt-2"
        style="z-index: 1"
    >
        <div class="grid gap-2 pb-2" style="grid-template-columns: 95% 5%">
            <text-input
                :error="nameErrorText"
                v-model="groupTemp.name"
                :label="$t('Name')"
            />
            <button
                v-if="isOriginal"
                @click.prevent="$emit('deleteParentGroup', true)"
                class="mt-8 cursor-pointer"
            >
                <font-awesome-icon icon="fa-regular fa-trash-can"/>
            </button>
            <button
                v-if="parent"
                @click.prevent="parent.child = null"
                class="mt-8 cursor-pointer"
            >
                <font-awesome-icon icon="fa-regular fa-trash-can"/>
            </button>
        </div>
        <div v-if="groupTemp.child" class="pl-5">
            <Group
                :isOriginal="false"
                :triggered="triggered"
                :parent="groupTemp"
                :group="groupTemp.child"
            />
        </div>
        <button
            @click="addSubGroup"
            v-else
            class="px-3 py-2 mt-2 docsHeroColorBlue rounded"
        >
            + Add Child Group
        </button>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";

export default {
    name: "Group",
    props: {
        isOriginal: {
            type: Boolean,
            default: false,
        },
        triggered: {
            type: Boolean,
            default: false,
        },
        parent: {
            type: Object,
        },
        group: {
            type: Object,
            default: null,
        },
    },
    components: {
        TextInput,
    },
    mounted() {
        this.groupTemp = this.group;
    },
    computed: {
        /**
         * checks if the name field length is greater than 0, if yes then trigger the error for name field
         */
        nameErrorText() {
            if (this.groupTemp.name.length == 0 && this.triggered) {
                return "Name is required";
            }
            return "";
        },
    },
    methods: {
        /**
         * adds a child or a sub group to parent group
         */
        addSubGroup() {
            this.groupTemp.child = {
                name: "",
                child: null,
            };
        },
    },
    data() {
        return {
            groupTemp: null,
        };
    },
};
</script>

<style scoped></style>
