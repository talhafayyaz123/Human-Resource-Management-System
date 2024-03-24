<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6"></div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">{{ $t("Subject") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Status") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="item in templates"
                    :key="item.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/mail-templates/${item.id}/edit`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <router-link
                            :to="`/mail-templates/${item.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.subject }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/mail-templates/${item.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.active ? $t("Active") : $t("Inactive") }}
                        </router-link>
                    </td>
                    <td class="w-px border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-1 mr-2"
                            :to="`/mail-templates/${item.id}/edit`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click="destroy(item.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="templates.length === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No templates found") }}.
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("mailTemplates", ["templates"]),
    },
    components: {
        PageHeader,
    },
    props: {
        filters: Object,
        can: Object,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Mail Templates",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Mail Template"),
                btn2Path: "/mail-templates/create",
            },
            window,
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh(bypass) {
            try {
                if (!this.templates?.length || bypass)
                    await this.$store.dispatch("mailTemplates/list");
            } catch (e) {}
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
                    await this.$store.dispatch("mailTemplates/destroy", {
                        id: id,
                    });
                    this.refresh(true);
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
