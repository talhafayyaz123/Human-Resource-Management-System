<template>
    <div>
        <!-- TODO: IMPLEMENT LATER -->
        <!-- <h1 class="header mb-8 text-3xl font-bold">
            <router-link class="secondary-color" to="/contract-types">{{
                $t("Contract Types")
            }}</router-link>
            <span class="secondary-color font-medium">/</span>
            <span class="text-color">{{ $t("Edit") }}</span>
        </h1>
        <h6 class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800">
            {{ $t("Fill Contract Type Details") }}
        </h6>
        <form @submit.prevent="update">
            <div
                class="max-w-3xl bg-white rounded-md shadow margin-bottom-3rem"
            >
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <text-input
                        v-model="form.name"
                        :error="errors.name"
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        :label="$t('Name')"
                        :required="true"
                    />
                </div>
                <div
                    class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
                >
                    <loading-button
                        v-if="$can(`${$route.meta.permission}.edit`)"
                        :loading="isLoading"
                        class="secondary-btn"
                        >{{ $t("Update Contract Type") }}
                    </loading-button>
                </div>
            </div>
        </form> -->
    </div>
</template>

<script>
// import LoadingButton from "../../Components/LoadingButton.vue";
// import TextInput from "../../Components/TextInput.vue";
// import { mapGetters } from "vuex";

// export default {
//     computed: {
//         ...mapGetters(["errors", "isLoading"]),
//     },
//     components: {
//         LoadingButton,
//         TextInput,
//     },
//     async mounted() {
//         // fetch the contract type details
//         try {
//             let response = await this.$store.dispatch(
//                 "contractTypes/show",
//                 this.$route.params.id
//             );
//             this.form.name = response?.data?.data?.name ?? "";
//         } catch (e) {
//             console.log(e);
//         }
//     },
//     data() {
//         return {
//             form: {
//                 name: "",
//             },
//         };
//     },
//     methods: {
//         // hit the contract type update API and redirect to listing page upon success
//         async update() {
//             try {
//                 this.$store.commit("isLoading", true);
//                 await this.$store.dispatch("contractTypes/update", {
//                     id: this.$route.params.id,
//                     data: {
//                         ...this.form,
//                     },
//                 });
//                 await this.$store.dispatch("contractTypes/list");
//                 this.$router.push("/contract-types");
//             } catch (e) {}
//         },
//     },
// };
</script>
