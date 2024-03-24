<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                :isFilter="false"
                v-model="form.search"
                @reset="reset"
                class="mr-4 w-full max-w-md"
            >
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('name')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
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
                        @click="sort('ProductSoftware.name')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Software") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'ProductSoftware.name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('ContractType.name')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Contract type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'ContractType.name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('template')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Template") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'template'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('prefix')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Prefix") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'prefix'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('version')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Version") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'version'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('createdAt')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Created At") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'createdAt'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>

                    <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="attachment in attachments?.data ?? []"
                    :key="attachment.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @click="
                        $router.push(
                            `/attachments/${attachment.id}/edit?page=${page}`
                        )
                    "
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/attachments/${attachment.id}/edit?page=${page}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ attachment.name }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ attachment.software?.name ?? "" }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ attachment.contractType?.name ?? "" }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ attachment.template }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ attachment.prefix }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ attachment.version }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{
                                $dateFormatter(
                                    attachment.createdAt,
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                    <td class="w-px border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="mx-2"
                            :to="`/attachments/${attachment.id}/edit?page=${page}`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click.stop="destroy(attachment.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="(attachments?.data.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No attachments found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="attachments"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import SearchFilter from "../../Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";
import { debounce } from "@/utils/debounce";

export default {
    computed: {
        ...mapGetters("attachments", ["attachments"]),
        ...mapGetters("documentService", ["documentServices"]),
    },
    components: {
        Pagination,
        SearchFilter,
        PageHeader,
    },
    data() {
        return {
            page: 1,
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Attachments"),
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Attachments"),
                btn2Path: "/attachments/create",
            },
            modifiedAttachments: {
                data: [],
                links: [],
            },
            form: {
                search: "",
                sortBy: localStorage.getItem("sort_news_column") ?? "createdAt",
                sortOrder: localStorage.getItem("sort_news_order") ?? "asc",
            },
            window,
        };
    },
    async created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch("attachments/list", {
                    page: this.page,
                    ...this.pickBy(this.form),
                });
                this.modifiedAttachments.data =
                    this.attachments?.data?.map((attachment) => {
                        return {
                            ...attachment,
                            template:
                                this.documentServices?.data?.find(
                                    (document) =>
                                        document.id == attachment.template
                                )?.name ?? attachment.template,
                        };
                    }) ?? [];
            } catch (e) {}
        }, 300);
    },
    watch: {
        "form.search"(...val) {
            // Handle the change in the search property
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            // Handle the change in the type property
            this.debouncedFetch(...val);
        },
        "form.sortOrder"(...val) {
            // Handle the change in the type property
            this.debouncedFetch(...val);
        },
    },
    async mounted() {
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
        }
        // get the attachments listing
        this.refresh();

        // fetch the documentServices listing
        try {
            if (!this.documentServices?.data?.length)
                await this.$store.dispatch("documentService/list", {
                    limit_start: 0,
                    limit_count: 25,
                });
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("attachments/list", {
                page: page,
                search: this.form.search,
            });
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
        // fetch the attachments listing
        async refresh() {
            await this.$store.dispatch("attachments/list", {
                ...this.pickBy(this.form),
            });
            // map the attachments listing to include the template object after finding it from documentServices listing
            this.modifiedAttachments.data =
                this.attachments?.data?.map((attachment) => {
                    return {
                        ...attachment,
                        template:
                            this.documentServices?.data?.find(
                                (document) => document.id == attachment.template
                            )?.name ?? attachment.template,
                    };
                }) ?? [];
        },
        /**
         * delete the attachment and refetch the listing
         * @param {id} id of the attachment to be deleted
         */
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
                        await this.$store.dispatch("attachments/destroy", id);
                        this.refresh();
                    } catch (e) {}
                }
            });
        },
    },
};
</script>

<style scoped>
:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}
:deep(.page-link) {
    color: #2957a4;
}
</style>
