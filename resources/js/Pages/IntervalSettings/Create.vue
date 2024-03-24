<template>
  <div>
    <h1 class="header mb-8 text-3xl font-bold">
      <router-link class="secondary-color" to="/interval-settings">{{
        $t("Interval Settings")
      }}</router-link>
      <span class="secondary-color font-medium">/</span>
      <span class="text-color">{{ $t("Create") }}</span>
    </h1>
    <h6 class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800">
      {{ $t("Fill Interval Settings Details") }}
    </h6>
    <form @submit.prevent="store">
      <div class="max-w-3xl bg-white rounded-md shadow margin-bottom-3rem">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            :required="true"
            v-model="form.months"
            :error="errors.months"
            class="pb-8 pr-6 w-full lg:w-1/2"
            :label="$t('Months')"
          />

          <select-input
            class="pb-8 pr-6 w-1/2"
            v-model="form.type"
            label="Type"
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
            :isDisabled="isEdit"
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
              <div class="grid" style="grid-template-columns: 50% 50%">
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
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button
            v-if="$can(`${$route.meta.permission}.create`)"
            :loading="isLoading"
            class="secondary-btn"
            >{{ $t("Create interval settings") }}
          </loading-button>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
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
  async mounted() {
    await this.$store.dispatch("auth/list", {
      limit_start: 0,
      limit_count: 25,
      type: "employee",
    });
    this.form.isApiCalled = true;
  },
  data() {
    return {
      form: {
        months: "",
        managers: [],
        isApiCalled: false,
        type: "",
      },
    };
  },
  methods: {
    customLabel(props) {
      return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
    },
    async store() {
      try {
        this.$store.commit("isLoading", true);
        await this.$store.dispatch("intervalSettings/create", {
          ...this.form,
        });
        await this.$store.dispatch("intervalSettings/list");
        this.$router.push("/interval-settings");
      } catch (e) {}
    },
  },
};
</script>
