<template>
    <label class="flex justify-between p-2">
        <h6 class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800">
            {{ $t("Usage Statistics") }}
        </h6>
    </label>
    <div class="tab-content border p-2">
        <div class="mt-7 overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th
                            style="width: 25%"
                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                        >
                            {{ $t("Event Name") }}
                        </th>
                        <th
                            style="width: 25%"
                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                        >
                            {{ $t("Value") }}
                        </th>
                        <th
                            style="width: 25%"
                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                        >
                            {{ $t("Next Reset") }}
                        </th>
                        <th
                            style="width: 25%"
                            class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-xs uppercase border-l-0 border-r-0 font-semibold text-left"
                        >
                            {{ $t("Last Reset") }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="(stat, index) in usageStatistics"
                        :key="index"
                        :tabindex="index"
                        class="focus:outline-none h-16 border border-gray-100 rounded"
                    >
                        <td class="pl-5">
                            <div class="">
                                <input
                                    class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                    type="text"
                                    :class="{
                                        Perror: errors[`stat.${index}.event`],
                                    }"
                                    readonly
                                    v-model="stat.event_name"
                                />
                            </div>
                        </td>
                        <td class="pl-5">
                            <div class="">
                                <input
                                    class="appearance-none rounded w-full py-2 px-2 text-gray-700 leading-tight focus:outline-none focus:border-purple-500"
                                    type="text"
                                    :class="{
                                        Perror: errors[`stat.${index}.value`],
                                    }"
                                    readonly
                                    v-model="stat.value"
                                />
                            </div>
                        </td>
                        <td class="pl-5">
                            <div class="">
                                {{ stat.next_reset }}
                            </div>
                        </td>
                        <td class="pl-5">
                            <div class="">
                                {{ stat.last_reset }}
                            </div>
                        </td>
                    </tr>
                    <tr
                        class="p-5 flex"
                        v-if="!usageStatistics.length"
                    >
                        <p class="text-sm">
                            {{ $t("No Records Added") }}
                        </p>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    props: {
        usageStatistics: {
            type: Array,
            default: [],
        },
    },
    computed: {
        ...mapGetters(["errors"]),
    },
};
</script>
