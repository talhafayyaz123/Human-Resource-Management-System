<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6"></div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Regex Content") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Regex Subject") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Regex From Mail") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Data Eval") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Active") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Url") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="item in mailWebhooks"
                    :key="item.id"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/mail-webhooks/${item.id}/edit`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t">
                        <router-link
                            :to="`/mail-webhooks/${item.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.name }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/mail-webhooks/${item.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.reg_ex_content }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/mail-webhooks/${item.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.reg_ex_subject }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/mail-webhooks/${item.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.data_eval }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/mail-webhooks/${item.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.reg_ex_from_mail }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/mail-webhooks/${item.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.active ? $t("Active") : $t("Inactive") }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/mail-webhooks/${item.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ item.url }}
                        </router-link>
                    </td>
                    <td class="w-px border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-1 mr-2"
                            :to="`/mail-webhooks/${item.id}/edit`"
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
                <tr v-if="mailWebhooks.length === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No mail webhooks found") }}.
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
        ...mapGetters("mailWebhooks", ["mailWebhooks"]),
    },
    components: {
        PageHeader,
    },
    mounted() {
        this.refresh();
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Mail Webhooks",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Mail Webhook"),
                btn2Path: "/mail-webhooks/create",
            },
            window,
        };
    },
    methods: {
        async refresh(bypass) {
            try {
                if (!this.mailWebhooks?.length || bypass)
                    await this.$store.dispatch("mailWebhooks/list");
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
                    await this.$store.dispatch("mailWebhooks/destroy", {
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
