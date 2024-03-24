<template>
    <div>
        <h1 class="header mb-8 text-3xl font-bold">
            <router-link class="secondary-color" to="/product-groups">{{
                $t("Interval Settings")
            }}</router-link>
            <span class="secondary-color font-medium">/</span>
            <span class="text-color">{{ $t("Update") }}</span>
        </h1>
        <h6 class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800">
            {{ $t("Update Interval Settings Details") }}
        </h6>
        <form @submit.prevent="update">
            <div
                class="max-w-3xl bg-white rounded-md shadow margin-bottom-3rem"
            >
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <text-input
                        v-model="form.months"
                        :error="errors.months"
                        :required="true"
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        :label="$t('Months')"
                    />
                    <select-input
                        class="pb-8 pr-6 w-1/2"
                        v-model="form.type"
                        label="Type"
                        v-if="form.isApiCalled"
                        :error="errors.type"
                        :required="true"
                    >
                        <option value="licence">
                            {{ $t("Licence Test") }}
                        </option>
                        <option value="uvv">
                            {{ $t("UVV") }}
                        </option>
                        <option value="fuel">
                            {{ $t("Fuel Monitoring") }}
                        </option>
                        <option value="cost">
                            {{ $t("Cost Monitoring") }}
                        </option>
                    </select-input>
                    <MultiSelectInput
                        v-if="form.isApiCalled"
                        v-model="form.managers"
                        :showLabels="false"
                        :required="true"
                        :options="users"
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        :multiple="true"
                        :textLabel="$t('Managers')"
                        label="first_name"
                        trackBy="id"
                        moduleName="auth"
                        :search-param-name="'search_string'"
                        :customLabel="customLabel"
                        :error="errors.managers"
                    >
                        <template #beforeList>
                            <div
                                class="grid p-2 gap-2"
                                style="grid-template-columns: 50% 50%"
                            >
                                <p class="font-bold">
                                    {{ $t("First Name") }}
                                </p>
                                <p class="font-bold">
                                    {{ $t("Last Name") }}
                                </p>
                            </div>
                            <hr />
                        </template>
                        <template #singleLabel="{ props }">
                            <p>
                                {{ props.option.first_name }}
                                {{ props.option.last_name }}
                            </p>
                        </template>
                        <template #option="{ props }">
                            <div
                                class="grid"
                                style="grid-template-columns: 50% 50%"
                            >
                                <p class="overflow-text-users-table">
                                    {{ props.option.first_name }}
                                </p>
                                <p class="overflow-text-users-table">
                                    {{ props.option.last_name }}
                                </p>
                            </div>
                        </template>
                    </MultiSelectInput>
                </div>
            </div>

            <div
                style="justify-content: space-between"
                class="mt-4 max-w-3xl flex"
            >
                <loading-button
                    v-if="$can(`${$route.meta.permission}.edit`)"
                    :loading="isLoading"
                    class="secondary-btn"
                    >{{ $t("Update interval setting") }}</loading-button
                >
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", {
            users: "users",
        }),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        SelectInput,
    },
    data() {
        return {
            form: {
                type: "",
                months: "",
                managers: "",
                isApiCalled: false,
            },
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "intervalSettings/show",
                    this.$route.params.id
                );
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "employee",
                });
                const intervalSetting = response?.data?.intervalSetting ?? {};
                const managers = intervalSetting?.managers?.map((obj) => {
                    const matchingUser = this.users.find(
                        (user) => user.id === obj.manager_id
                    );
                    return {
                        id: obj.manager_id,
                        first_name: matchingUser?.first_name || "",
                        last_name: matchingUser?.last_name || "",
                    };
                });
                this.form.months = intervalSetting.months;
                this.form.managers = managers;
                this.form.type = intervalSetting.interval_setting_type;
                this.form.isApiCalled = true;
            } catch (e) {}
        },
        async update() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("intervalSettings/update", {
                id: this.$route.params.id,
                data: { ...this.form },
            });
            await this.$store.dispatch("intervalSettings/list");
            this.$router.push("/interval-settings");
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        }
    },
};
</script>
