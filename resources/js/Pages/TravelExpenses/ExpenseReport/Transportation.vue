<template>
    <div class="card px-7 pb-7 pt-8 relative">
        <div
            v-for="(item, index) in transportationData"
            :key="index"
            class="grid items-center grid-cols-2 gap-6"
        >
            
            <div class="w-full">
                <div class="form-group">
                    <select-input
                :isReadOnly="show"
                :required="true"
                v-model="item.carType"
                @update:model-value="
                    calculateAmount(index);
                    setFleetCar(index);
                "
                :key="item.carType"
                :error="errors?.carType"
                :label="$t('Car Type')"
            >
                <option value="private-car">{{ $t("Private") }}</option>
                <option value="fleet-car">{{ $t("Fleet") }}</option>
            </select-input>
                </div>
            </div>
            <div class="w-full" v-if="item.carType === 'fleet-car'">
                <div class="form-group">
                    <MultiSelectInput
                
                :textLabel="$t('Fleet Car')"
                :required="true"
                :key="item.fleetCarId"
                v-model="item.fleetCarId"
                :error="errors?.fleetCarId"
                :isDisabled="show"
                label="licenceNumber"
                trackBy="id"
                moduleName="fleetCars"
                :multiple="false"
                :options="fleetCars?.data ?? []"
                :query="{ driverId: user.id }"
            />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <text-input
                :isReadonly="true"
                :required="true"
                v-model="item.departureCity"
                :key="item.departureCity"
                :error="errors?.departureCity"
                :label="$t('Departure City')"
            />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <text-input
                :isReadonly="true"
                :required="true"
                v-model="item.arrivalCity"
                :key="item.arrivalCity"
                :error="errors?.arrivalCity"
                :label="$t('Arrival City')"
            />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <number-input
                :isReadonly="show"
                :required="true"
                :showPrefix="false"
                @inputEvent="calculateAmount(index)"
                v-model="item.drivenKilometers"
                :error="errors?.drivenKilometers"
                :label="$t('Driven Kilometers')"
            />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <number-input
                :isReadonly="true"
                :required="true"
                :showPrefix="true"
                v-model="item.amount"
                :error="errors?.amount"
                :label="$t('Amount')"
            />
                </div>
            </div>
            <div class="absolute right-3 top-2" v-if="!show">
                <button
                
                role="button"
                type="button"
                @click="deleteTransportation(index)"
                class="text-red-500 text-right"
            >
                <font-awesome-icon ref="icon" icon="fa-regular fa-trash-can" />
            </button>
            </div>
            
            
            
            
            
        </div>
    </div>

    <div v-if="!show" class="flex justify-between mt-3">
        <button
            role="button"
            type="button"
            @click="
                transportationData.push({
                    drivenKilometers: '',
                    fleetCarId: '',
                    carType: 'private-car',
                    amount: 0,
                    departureCity: departureCity,
                    arrivalCity: arrivalCity,
                })
            "
            class="secondary-btn gap-2"
        >
            <font-awesome-icon icon="fa-solid fa-plus" />
            <span>{{ $t("Add transportation") }}</span>
        </button>
        <loading-button
            class="secondary-btn"
            @click="save"
            :loading="isLoading"
            >{{ $t("Save and Proceed") }}</loading-button
        >
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    props: {
        show: {
            type: Boolean,
            default: false,
        },
        departureCity: {
            type: String,
            default: "",
        },
        arrivalCity: {
            type: String,
            default: "",
        },
    },
    components: {
        TextInput,
        LoadingButton,
        NumberInput,
        MultiSelectInput,
        SelectInput,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("fleetCars", ["fleetCars"]),
        ...mapGetters("auth", ["user"]),
    },
    data() {
        return {
            transportationData: [
                {
                    drivenKilometers: "",
                    fleetCarId: "",
                    carType: "private-car",
                    amount: 0,
                    departureCity: this.arrivalCity,
                    arrivalCity: this.departureCity,
                },
            ],
        };
    },
    unmounted() {
        this.$store.commit("fleetCars/fleetCars", {
            data: [],
            links: [],
        });
    },
    async mounted() {
        this.refresh();
        if (!this.fleetCars?.data?.length) {
            this.$store.dispatch("fleetCars/list", {
                driverId: this.user.id,
            });
        }
    },
    methods: {
        /**
         * sets automatically the fleeCarId of the current user
         * @param {index} index of transportationData for which we set the fleeCarId
         */
        async setFleetCar(index) {
            await this.$nextTick();
            if (this.transportationData[index].carType === "fleet-car") {
                this.transportationData[index].fleetCarId =
                    this.fleetCars?.data?.[0] ?? "";
            } else {
                this.transportationData[index].fleetCarId = "";
            }
        },
        /**
         * calculates the amount from driven kilometers
         * @param {index} index of the record for which we are calculating amount
         */
        async calculateAmount(index) {
            try {
                this.$nextTick();
                if (this.transportationData[index].carType === "private-car") {
                    const multiplier =
                        this.transportationData[index].drivenKilometers <= 20
                            ? 0.3
                            : 0.35;
                    this.transportationData[index].amount =
                        +this.transportationData[index].drivenKilometers *
                        multiplier;
                } else {
                    this.transportationData[index].amount = 0;
                }
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * delete transportaion
         * @param {index} index of transportation to be deleted
         */
        async deleteTransportation(index) {
            if (!!this.transportationData[index]?.id) {
                try {
                    await this.$store.dispatch(
                        "travelExpensePrivateTransportations/destroy",
                        this.transportationData[index]?.id
                    );
                    this.refresh();
                } catch (e) {
                    console.log(e);
                }
            } else {
                this.transportationData[index].splice(index, 1);
            }
        },
        /**
         * get transportation listing
         */
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "travelExpensePrivateTransportations/list",
                    {
                        travelExpenseId: this.$route.params.id,
                    }
                );
                this.transportationData = response?.data?.data ?? [
                    {
                        drivenKilometers: "",
                        fleetCarId: "",
                        carType: "private-car",
                        amount: 0,
                        departureCity: this.departureCity,
                        arrivalCity: this.arrivalCity,
                    },
                ];
                this.$store.commit(
                    "travelExpensePrivateTransportations/privateTransportations",
                    this.transportationData
                );
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * save transportation
         */
        async save() {
            this.$store.commit("isLoading", true);
            try {
                await this.$store.dispatch(
                    "travelExpensePrivateTransportations/create",
                    {
                        travelExpenseId: this.$route.params.id,
                        data: this.transportationData.map((item) => {
                            return {
                                ...item,
                                fleetCarId:
                                    item.fleetCarId?.[0]?.id ??
                                    item.fleetCarId?.id,
                            };
                        }),
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
