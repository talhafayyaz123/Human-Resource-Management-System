<template>
    <div class="bg-white rounded-md shadow overflow-x-auto mx-2">
        <div class=" justify-between items-center px-4 pt-2">
            <p class="text-md font-normal leading-normal mt-0 text-cyan-800">
                {{ $t("Application Management Service-Customer") }}
            </p>
            
        </div>
        <table class="w-full whitespace-nowrap">
            <tr class="text-left font-bold text-sm">
                <th
                    @click="sort('amsNumber')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("Ams Number")
                    }}<font-awesome-icon
                        v-if="form.sortBy === 'amsNumber'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    @click="sort('customerId.companyName')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("Company")
                    }}<font-awesome-icon
                        v-if="form.sortBy === 'customerId.companyName'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
              
                <th
                    @click="sort('title', 'surveys')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("SW-PUS")
                    }}<font-awesome-icon
                        v-if="form.sortBy === 'title'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    @click="sort('assignee', 'surveys')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("ams")
                    }}<font-awesome-icon
                        v-if="form.sortBy === 'assignee'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                 <th
                    @click="sort('state', 'surveys')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("Service Hours Year ")
                    }}<font-awesome-icon
                        v-if="form.sortBy === 'state'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    @click="sort('updatedAt', 'surveys')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("Remaining Hours")
                    }}<font-awesome-icon
                        v-if="form.sortBy === 'updatedAt'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
            </tr>
           
            <tr
                v-for="ams in amsData?.data ?? []"
                :key="ams.id"
                class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm"
                @click.right="
                    (e) => {
                        e.preventDefault();
                       
                    }
                "
            >
                <td class="border-t">
                    <a
                        class="flex items-center cursor-pointer px-6 small-table-text py-4 focus:text-indigo-500"
                    >
                        {{ ams.amsNumber }}
                    </a>
                </td>
                <td class="border-t">
                    <a
                        class="flex items-center cursor-pointer px-6 small-table-text py-4"
                        tabindex="-1"
                    >
                        {{ ams.customerId.companyName }}
                    </a>
                </td>
                <td class="border-t">
                    <a
                        class="flex items-center cursor-pointer px-6 small-table-text py-4"
                        tabindex="-1"
                    >
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="software-license-accordion"
                        :disabled="true"
                        :checked="
                         isInputChecked(ams.swPus)
                         "
                    />
                    </a>
                </td>
                <td class="border-t">
                    <a
                        class="flex items-center cursor-pointer px-6 small-table-text py-4"
                        tabindex="-1"
                    >
                 
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="software-license-accordion"
                        :disabled="true"
                        :checked="
                         isInputChecked(ams.ams)
                         "
                    />
                    </a>
                </td>
                 <td class="border-t">
                    <a
                        class="flex items-center cursor-pointer px-6 small-table-text py-4"
                        tabindex="-1"
                    >
                    {{ams.serviceHoursYear }}
                    </a>
                </td>
                <td class="border-t">
                    <a
                        class="flex items-center cursor-pointer px-6 small-table-text py-4"
                        tabindex="-1"
                    >
                        {{ams.remainingHours }}
                    </a>
                </td>
            </tr>
            <tr v-if="(amsData?.data?.length ?? 0) === 0">
                <td class="px-6 py-4 border-t" colspan="4">
                    {{ $t("No Ams found") }}
                </td>
            </tr>
        </table>
        <div class="flex justify-center">
            <br />
            <br />
            <pagination
                :limit="10"
                align="center"
                :data="amsData"
                @pagination-change-page="onPageChange"
            ></pagination>
        </div>
    </div>
</template>

<script>
import pagination from "laravel-vue-pagination";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["pageChangedAms"],
    props: {
        amsData: {
            type: Object,
            default: () => ({}),
        },
    },
    mounted() {
        if (!this.users?.length) {
            this.$store.dispatch("auth/list");
        }
    },
    computed: {
        ...mapGetters("auth", ["users"]),
    },
    components: {
        MultiSelectInput,
        pagination,
    },
    watch: {
        form: {
            handler: function (val) {
                this.$emit("pageChanged", {
                    page: 1,
                    ...val,
                    assignee: val.assignee?.id,
                });
            },
            deep: true,
        },
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        onPageChange(page) {
            this.$emit("pageChangedAms", { page: page });
        },
        isInputChecked(ams) {
           if(ams){
            return true;
           }else{

            return false;
           }
           }
    },
    data() {
        return {
            window,
            form: {
                assignee: "",
                sortBy: "",
                sortOrder: "",
            },
            openTickets: [],
        };
    },
};
</script>

<style scoped>
.small-table-text {
    padding: 0px 0px 0px 15px !important;
}
</style>
