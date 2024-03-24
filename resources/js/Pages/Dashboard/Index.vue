<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <h3 class="card-title">
            {{ $t("Hey there! Welcome to") }}
            <a
                class="text-indigo-500 hover:text-orange-600 underline"
                href="#"
                >{{ $t("DocsHero") }}</a
            >
            {{ $t("Admin Panel") }}.
        </h3>

        <div class="mt-3" v-if="$can('changelog.list')">
            <div class="flex justify-between">
                <h1 class="header mb-8 text-3xl font-bold secondary-color">
                    {{ $t("Changelog") }}
                </h1>
                <div class="flex mb-6">
                    <router-link
                        v-if="$can('changelog.create')"
                        class="secondary-btn"
                        to="/changelog/create"
                    >
                        <span>{{ $t("Create Changelog") }}</span>
                    </router-link>
                </div>
            </div>
            <div class="bg-white rounded-md shadow overflow-x-auto">
                <div class="grid grid-cols-1 gap-6">
                    <div
                        v-for="changelog in changelogs?.data"
                        :key="changelog.id"
                        class="card mb-2"
                    >
                        <div
                            class="px-4 pt-4 flex justify-between items-center"
                        >
                            <h3 class="card-title">{{ changelog.subject }}</h3>
                            <div class="flex space-x-2">
                                <router-link
                                    v-if="$can('changelog.edit')"
                                    class="text-indigo-500"
                                    :to="`/changelog/${changelog.id}/edit`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                                <button
                                    v-if="$can('changelog.delete')"
                                    class="text-red-500"
                                    @click="destroy(changelog.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </div>
                        </div>
                        <div class="p-4 changelog">
                            <QuillTextEditor
                                :isReadonly="true"
                                class=""
                                :content="changelog?.description"
                                @delta="changelog.description= $event"
                                height="auto"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        PageHeader,
        QuillTextEditor,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Management"),
                    to: "/dashboard",
                },
                {
                    text: "Dashboard",
                    active: true,
                },
            ],
            page: 1,
            window,
        };
    },
    computed: {
        ...mapGetters("changelog", ["changelogs"]),
    },
    async mounted() {
        await this.refresh();
    },

    methods: {
        async refresh() {
            await this.$store.dispatch("changelog/list", {
                page: this.page,
            });
            await window.scrollTo(0, 0);
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
                    await this.$store.dispatch("changelog/destroy", id);
                    this.refresh(true);
                }
            });
        },
    },
};
</script>

<style scoped>
:deep(.ql-toolbar) {
    display: none !important;
}
:deep(.ql-container) {
    border: none !important;
}
</style>
<style lang="scss">
.changelog {
    .form-quilleditor {
        .ql-editor {
            padding: 0;
        }
    }
}
</style>
