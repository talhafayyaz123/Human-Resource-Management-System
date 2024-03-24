<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="flex items-center justify-between mb-6">
            <loading-button
                v-if="$can(`${$route.meta.permission}.create`)"
                class="secondary-btn"
                :loading="importLoading"
                @click.stop="this.$refs.fileInput.click()"
            >
                <span>{{ $t("Import Country Csv") }}</span>
            </loading-button>
        </div>
        <input @input="uploadFile" ref="fileInput" class="hidden" type="file" />
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Name") }}
                    </th>
                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("ISO") }}
                    </th>
                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Region") }}
                    </th>

                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Subregion") }}
                    </th>

                    <th class="pb-4 pt-6 px-6 cursor-pointer">
                        {{ $t("Action") }}
                    </th>
                </tr>
                <tr
                    v-for="(country, index) in form.countries ?? []"
                    :key="index"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t">
                        <div
                            class="flex items-center whitespace-normal px-6 py-4 focus:text-indigo-500"
                        >
                            {{ country.name }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div
                            class="flex whitespace-normal items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ country.iso }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div
                            class="flex whitespace-normal items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ country.region }}
                        </div>
                    </td>
                    <td class="border-t">
                        <div
                            class="flex whitespace-normal items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ country.subregion }}
                        </div>
                    </td>

                    <td class="w-px border-t text-center">
                        <!--  <font-awesome-icon icon="fa-regular fa-pen-to-square"/> -->

                        <button @click="form?.countries.splice(index, 1)">
                            <font-awesome-icon icon="fa-regular fa-trash-can"/>
                        </button>
                    </td>
                </tr>
                <tr v-if="(form.countries?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No countries imported") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div>
            <loading-button
                v-if="(form.countries?.length ?? 0) != 0"
                :loading="isLoading"
                style="cursor: pointer"
                class="secondary-btn mt-8 float-right mb-4"
                @click="createCountries"
                >Save</loading-button
            >
        </div>
        <div
            style="margin-bottom: 60px"
            v-if="(form.countries?.length ?? 0) != 0"
            class="mb-4"
        ></div>
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import SearchFilter from "../../Components/SearchFilter.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["isLoading", "errors"]),
    },
    components: { SearchFilter, LoadingButton ,PageHeader},
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Countries",
                    to: "/countries",
                },
                {
                    text: "Import Countries",
                    active: true,
                },
            ],
            form: {
                countries: [],
            },
            isModalOpen: false,
            importLoading: false,
            window,
        };
    },
    methods: {
        async uploadFile(event) {
            try {
                this.importLoading = true;
                const file = event?.target?.files?.[0] ?? null;
                console.log(file);
                const formData = new FormData();
                formData.set("csv", file);
                const response = await this.$store.dispatch(
                    "countries/uploadCsv",
                    formData
                );
                const countries = response?.data?.data ?? [];
                this.form.countries = [];
                (countries instanceof Array ? countries : []).forEach(
                    (record) => {
                        const modifiedRecord = {
                            iso: record.iso,
                            name: record.name,
                            subregion: record.subregion,
                            region: record.region,
                        };

                        this.form.countries = [
                            ...this.form.countries,
                            { ...modifiedRecord },
                        ];
                    }
                );
            } catch (e) {
                console.log(e);
            } finally {
                this.importLoading = false;
                this.isModalOpen = false;
            }
        },
        async createCountries() {
            await this.$nextTick();
            let wasCreateSuccessfull = false;
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("countries/storeCsv", {
                    countries: this.form.countries,
                });
                wasCreateSuccessfull = true;
            } catch (e) {
                console.log(e);
            }
            if (wasCreateSuccessfull) {
                await this.$store.dispatch("countries/list");
                this.$router.push("/countries");
            }
        },
    },
};
</script>

<style></style>
