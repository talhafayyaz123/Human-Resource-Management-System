<template>
    <div>
        <h1 class="header mb-8 text-3xl font-bold">
            <router-link class="secondary-color" :to="`/open-posts?page=${returnPage}`">{{
                $t("Open Posts")
            }}</router-link>
            <span class="secondary-color font-medium">/</span>
            <span class="text-color">{{ $t("Update") }}</span>
        </h1>
        <div class="max-w-3xl bg-white rounded-md shadow margin-bottom-3rem">
            <form @submit.prevent="updateStatus">
                <div
                    class="max-w-3xl bg-white rounded-md shadow margin-bottom-3rem"
                >
                    <div class="flex flex-wrap -mr-6 p-8">
                        <MultiSelectInput
                            class="lg:w-100 pr-6 w-full"
                            v-model="form.levelId"
                            v-if="isApiCalled"
                            :options="invoiceReminder?.data"
                            :multiple="false"
                            :textLabel="$t('Invoice Reminder Level')"
                            label="levelName"
                            :trackBy="'id'"
                            :moduleName="'reminderLevels'"
                            :taggable="true"
                            :error="errors.levelId"
                        />
                    </div>
                    <div
                        v-if="!!form.reminderStop"
                        class="flex flex-wrap pt-0 p-8"
                    >
                        <label class="form-label"
                            >{{ $t("Reminder Stop Until") }}:</label
                        >
                        <datepicker
                            :clearable="true"
                            :enable-time-picker="false"
                            auto-apply
                            :close-on-auto-apply="false"
                            class="w-1/3"
                            v-model="form.reminderStopUntil"
                            :class="errors.reminderStopUntil ? 'error' : ''"
                        />
                        <div
                            v-if="errors?.reminderStopUntil"
                            class="form-error"
                        >
                            {{ errors.reminderStopUntil }}
                        </div>
                    </div>
                    <div class="flex flex-wrap pt-0 p-8">
                        <label class="mr-5" for="remove-from-statistics"
                            >{{ $t("Reminder Stop") }}:</label
                        >
                        <input
                            id="remove-from-statistics"
                            type="checkbox"
                            :checked="form.reminderStop"
                            v-model="form.reminderStop"
                        />
                    </div>
                    <div
                        class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
                    >
                        <loading-button
                            v-if="$can(`${$route.meta.permission}.create`)"
                            :loading="isLoading"
                            class="secondary-btn"
                            >{{ $t("Update Status") }}
                        </loading-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import SelectInput from "../../Components/SelectInput.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
export default {
    components: { SelectInput, LoadingButton, MultiSelectInput },
    data() {
        return {
            returnPage:'',
            form: {
                levelId: "",
                reminderStop: false,
                reminderStopUntil: null,
            },
            isApiCalled: false,
        };
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("invoicereminder", ["invoiceReminder"]),
    },
    async mounted() {
        if(this.$route.query.page){
            this.returnPage=this.$route.query.page; 
        }
        const response = await this.$store.dispatch(
            "invoices/show",
            this.$route.params.id
        );
        await this.$store.dispatch("invoicereminder/list");
        this.form.levelId = this.invoiceReminder?.data.find((reminder) => {
            return reminder.id == response?.data?.pInvoices?.reminderLevelId;
        });
        let responseData = response?.data?.pInvoices;
        this.form.reminderStop = responseData?.reminderStop;
        this.form.reminderStopUntil = responseData?.reminderStopUntil;
        this.isApiCalled = true;
        document.title =  "Edit Open Post " + responseData?.invoiceNumber ?? "";
    },
    methods: {
        async updateStatus() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("openposts/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        reminderStopUntil: !!this.form.reminderStop
                            ? this.form.reminderStopUntil
                            : null,
                        levelId: this.form.levelId?.id,
                    },
                });
                await this.$store.dispatch("openposts/list");
                this.$router.push("/open-posts?page="+this.returnPage);
            } catch (e) {}
        },
    },
};
</script>

<style></style>
