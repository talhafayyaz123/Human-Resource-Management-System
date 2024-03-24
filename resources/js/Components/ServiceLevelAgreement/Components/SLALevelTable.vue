<template>
    <table class="w-full doc-table">
        <tr class="text-left font-bold">
            <th @click="sort('name')" class="pb-4 pt-6 px-6 cursor-pointer">
                {{ $t("Name") }}
                <font-awesome-icon
                    v-if="form.sortBy === 'name'"
                    class="cursor-pointer ml-2"
                    :icon="`fa-solid fa-sort-${
                        form.sortOrder === 'asc' ? 'up' : 'down'
                    }`"
                />
            </th>
            <th
                @click="sort('reactionTimeUrgent')"
                class="pb-4 pt-6 px-6 cursor-pointer"
            >
                {{ $t("Reaction Time (Urgent)") }}
                <font-awesome-icon
                    v-if="form.sortBy === 'reactionTimeUrgent'"
                    class="cursor-pointer ml-2"
                    :icon="`fa-solid fa-sort-${
                        form.sortOrder === 'asc' ? 'up' : 'down'
                    }`"
                />
            </th>

            <th
                @click="sort('reactionTimeHigh')"
                class="pb-4 pt-6 px-6 cursor-pointer"
            >
                {{ $t("Reaction Time (High)") }}
                <font-awesome-icon
                    v-if="form.sortBy === 'reactionTimeHigh'"
                    class="cursor-pointer ml-2"
                    :icon="`fa-solid fa-sort-${
                        form.sortOrder === 'asc' ? 'up' : 'down'
                    }`"
                />
            </th>

            <th
                @click="sort('reactionTimeLow')"
                class="pb-4 pt-6 px-6 cursor-pointer"
            >
                {{ $t("Reaction Time (Low)") }}
                <font-awesome-icon
                    v-if="form.sortBy === 'reactionTimeLow'"
                    class="cursor-pointer ml-2"
                    :icon="`fa-solid fa-sort-${
                        form.sortOrder === 'asc' ? 'up' : 'down'
                    }`"
                />
            </th>

            <th @click="sort('factor')" class="pb-4 pt-6 px-6 cursor-pointer">
                {{ $t("Factor") }}
                <font-awesome-icon
                    v-if="form.sortBy === 'factor'"
                    class="cursor-pointer ml-2"
                    :icon="`fa-solid fa-sort-${
                        form.sortOrder === 'asc' ? 'up' : 'down'
                    }`"
                />
            </th>

            <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
        </tr>
        <tr
            v-for="(item, index) in slaLevels?.data"
            :key="index"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
            <td class="border-t">
                <a
                    href="javascript:void(0)"
                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                >
                    {{ item?.name }}
                </a>
            </td>
            <td class="border-t">
                <a
                    href="javascript:void(0)"
                    class="flex items-center px-6 py-4 focus:text-indigo-500 capitalize"
                >
                    {{ item.reactionTimeUrgent }}
                </a>
            </td>
            <td class="border-t">
                <a
                    href="javascript:void(0)"
                    class="flex items-center px-6 py-4 focus:text-indigo-500 capitalize"
                >
                    {{ item.reactionTimeHigh }}
                </a>
            </td>
            <td class="border-t">
                <a
                    href="javascript:void(0)"
                    class="flex items-center px-6 py-4 focus:text-indigo-500 capitalize"
                >
                    {{ item.reactionTimeLow }}
                </a>
            </td>
            <td class="border-t">
                <a
                    href="javascript:void(0)"
                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                >
                    {{ item.factor ?? 0 }}
                </a>
            </td>
            <td class="w-px border-t">
                <button
                    v-if="$can(`${$route.meta.permission}.edit`)"
                    @click="show(item?.id)"
                    class="mr-2"
                >
                    <font-awesome-icon icon="fa-regular fa-pen-to-square" />
                </button>
                <button
                    v-if="$can(`${$route.meta.permission}.delete`)"
                    @click="destroy(item?.id)"
                >
                    <font-awesome-icon icon="fa-regular fa-trash-can" />
                </button>
            </td>
        </tr>
    </table>

    <div style="margin-top: 3rem" class="flex justify-center">
        <pagination
            :limit="10"
            align="center"
            :data="slaLevels"
            @pagination-change-page="changePageOrSearch"
        ></pagination>
        <br />
        <br />
    </div>

    <SLALevelModal
        @edit="edit"
        :form="editSlaForm"
        @toggleModal="toggleModal = $event"
        :toggleModal="toggleModal"
    />
</template>

<script>
import SLALevelModal from "./SLALevelModal.vue";
import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";

export default {
    emits: ["refresh"],
    data() {
        return {
            editSlaForm: {},
            toggleModal: false,
            page: 1,
            form: {
                search: "",
                sortBy: "",
                sortOrder: "",
            },
        };
    },
    components: {
        SLALevelModal,
        Pagination,
    },
    computed: {
        ...mapGetters("slaLevel", ["slaLevels"]),
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        await this.$store.dispatch(
                            "slaLevel/list",
                            this.pickBy(this.form)
                        );
                    } catch (e) {}
                }, 150)();
            },
        },
    },
    methods: {
        async edit(form) {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch(`slaLevel/update`, {
                id: form?.id,
                data: form,
            });
            this.toggleModal = false;
            this.$emit("refresh", true);
            this.editSlaForm = {};
        },
        async show(id) {
            const response = await this.$store.dispatch(`slaLevel/show`, id);
            this.editSlaForm = response?.data?.data;
            this.toggleModal = true;
        },
        async destroy(id) {
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    try {
                        await this.$store.dispatch(`slaLevel/destroy`, id);
                        this.$emit("refresh", true);
                    } catch (e) {
                        console.log(e);
                    }
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;

            await this.$store.dispatch("slaLevel/list", {
                page: this.page,
            });
        },
    },
};
</script>
