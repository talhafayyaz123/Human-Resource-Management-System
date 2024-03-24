<template>
    <div class="page-header">
        <div class="page-title">
            <div class="page-title-right">
                <ol class="breadcrumb m-0 align-items-center">
                    <li class="breadcrumb-item">
                        <router-link to="/dashboard">{{
                            $t("Admin Portal")
                        }}</router-link>
                    </li>
                    <li class="breadcrumb-item">
                        <router-link
                            :to="`/systems/on-premise?page=${returnPage}`"
                            >{{ $t("Master Data") }}</router-link
                        >
                    </li>
                    <li class="breadcrumb-item">
                        <router-link
                            :to="`/systems/on-premise?page=${returnPage}`"
                            >{{ $t("On Premise") }}</router-link
                        >
                    </li>
                    <li class="breadcrumb-item">
                        <span class="cursor-pointer">{{ $t("Update") }}</span>
                    </li>
                </ol>
            </div>
        </div>
        <div class="other-items d-flex justify-center">
            <div class="search mr-0">
                <div class="form-group m-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <CustomIcon name="search" class="mr-1" />
                            </span>
                        </div>
                        <input
                            v-model="searchGlobal"
                            type="search"
                            :placeholder="$t('Search here')"
                            class="form-control"
                        />
                    </div>
                </div>
            </div>
            <div class="divider mr-4"></div>
            <div class="polling">
                <h3>Polling Interval: 10 Secs</h3>
                <button class="polling-btn">Stop Polling</button>
            </div>
        </div>
    </div>
    <div>
        <div class="tab-header">
            <ul>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'systems'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `systems`"
                    >
                        {{ $t("System Detail") }}
                    </a>
                </li>
                <li v-if="form.operatingSystem == `linux`" class="nav-item">
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'products'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `products`"
                    >
                        {{ $t("Products") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'opc_health'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `opc_health`"
                    >
                        {{ $t("OPC Health Check") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'power_shell'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `power_shell`"
                    >
                        {{ $t("Power Shell") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'monitoring'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `monitoring`"
                    >
                        {{ $t("Monitoring") }}
                    </a>
                </li>
            </ul>
        </div>
        <form @submit.prevent="update">
            <!--===========================================-->
            <!--===========================================-->
            <div v-if="activeTab == `systems`">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Update Systems Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid items-center grid-cols-2 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        :required="true"
                                        :key="form.systemUser"
                                        :textLabel="$t('Customer')"
                                        v-model="form.systemUser"
                                        :options="companies.data"
                                        :multiple="false"
                                        label="companyName"
                                        trackBy="id"
                                        moduleName="companies"
                                    >
                                        <template #beforeList>
                                            <div
                                                class="grid p-2 gap-2"
                                                style="grid-template-columns: 60% 25%"
                                            >
                                                <p class="font-bold">
                                                    {{ $t("Name") }}
                                                </p>
                                                <p class="font-bold">
                                                    {{ $t("Type") }}
                                                </p>
                                            </div>
                                            <hr />
                                        </template>
                                        <template #option="{ props }">
                                            <div
                                                class="grid gap-2"
                                                style="grid-template-columns: 60% 25%"
                                            >
                                                <p class="overflow-text-users-table">
                                                    {{ props.option.companyName }}
                                                </p>
                                                <p
                                                    style="text-transform: capitalize"
                                                    class="overflow-text-users-table"
                                                >
                                                    {{ props.option.customerType }}
                                                </p>
                                            </div>
                                        </template>
                                    </MultiSelectInput>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.type"
                                        :label="$t('System Type')"
                                        :isReadonly="true"
                                        :error="errors?.type ?? ''"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <select-input
                                        :required="true"
                                        v-if="form.instanceType"
                                        v-model="form.instanceType"
                                        :label="$t('Instance Type')"
                                        :error="errors?.instanceType ?? ''"
                                    >
                                        <option value="database">
                                            {{ $t("Database System") }}
                                        </option>
                                        <!-- :error="form.errors.instanceType" -->
                                        <option value="development">
                                            {{ $t("Development System") }}
                                        </option>
                                        <option value="test">
                                            {{ $t("Test System") }}
                                        </option>
                                        <option value="productive">
                                            {{ $t("Productive System") }}
                                        </option>
                                    </select-input>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        :textLabel="$t('Operating System')"
                                        :required="true"
                                        :error="errors?.operatingSystem ?? ''"
                                        v-model="form.operatingSystem"
                                        :options="operatingSystem.data"
                                        :multiple="false"
                                        label="name"
                                        trackBy="id"
                                        :key="form.operatingSystem"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        :textLabel="$t('Reverse Shell Client Id')"
                                        :error="errors?.clients ?? ''"
                                        v-model="clientId"
                                        :options="clients"
                                        :multiple="false"
                                        label="hostName"
                                        trackBy="clientId"
                                        :key="clientId"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Fill Premise Details") }}
                            <span v-if="form.operatingSystem === 'windows'">
                                {{ $t("for RDP") }}</span
                            >
                            <span v-else-if="form.operatingSystem === 'linux'">
                                {{ $t("for Linux") }}</span
                            >
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid items-center grid-cols-2 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        v-model="form.systemName"
                                        :label="$t('System Name')"
                                        :error="errors?.systemName ?? ''"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.serverIp"
                                        :label="$t('Server Ip/Host')"
                                        :error="errors?.serverIp ?? ''"
                                    />
                                    <!-- :error="form.errors.serverIp" -->
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        :error="errors?.software ?? ''"
                                        v-model="form.software"
                                        :key="form.software"
                                        :options="softwares.data"
                                        :textLabel="$t('Software')"
                                        :required="true"
                                        :multiple="false"
                                        label="name"
                                        trackBy="id"
                                    />
                                    <!-- :error="form.errors.software" -->
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        :error="errors?.eloVersion ?? ''"
                                        v-model="form.eloVersion"
                                        :key="form.eloVersion"
                                        :options="versions"
                                        :required="true"
                                        :multiple="false"
                                        :textLabel="$t('Software Version')"
                                        label="name"
                                        :trackBy="'id'"
                                        :moduleName="'version'"
                                    />
                                    <!-- :error="form.errors.versions" -->
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.serverPort"
                                        :label="$t('Server Port')"
                                        :error="errors?.serverPort ?? ''"
                                    />
                                    <!-- :error="form.errors.serverPort" -->
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.username"
                                        :label="$t('Username')"
                                        :error="errors?.username ?? ''"
                                    />
                                    <!-- :error="form.errors.username" -->
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <CustomIcon name="lckIcon" />
                                            </span>
                                        </div>
                                    <text-input
                                        :key="form.inputType"
                                        :required="true"
                                        v-model="form.password"
                                        :label="$t('Password')"
                                        :type="inputType"
                                        :show-password="showPassword"
                                        :error="errors?.password ?? ''"
                                        @child-event="handleChildEvent"
                                    />
                                    </div>
                                    <!-- :error="form.errors.password" -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <router-link
                        :to="`/systems/on-premise?page=${returnPage}`"
                        class="primary-btn mr-3"
                    >
                        <span class="mr-1">
                            <CustomIcon name="cancelIcon" />
                        </span>
                        <span>{{ $t("Cancel") }}</span>
                    </router-link>
                    <loading-button :loading="isLoading" class="secondary-btn">
                        <span class="mr-1">
                            <CustomIcon name="updateIcon" />
                        </span>
                        {{ $t("Update") }}
                    </loading-button>
                </div>
            </div>
            <!--===========================================-->
            <!--===========================================-->
            <div v-else-if="activeTab == `products`">
                <product-tabs
                    :products="products"
                    @selected="addProducts"
                    :selectedProducts="form.systemProducts"
                ></product-tabs>
                <div class="flex items-center justify-end mt-4">
                    <router-link
                        :to="`/systems/on-premise?page=${returnPage}`"
                        class="primary-btn mr-3"
                    >
                        <span class="mr-1">
                            <CustomIcon name="cancelIcon" />
                        </span>
                        <span>{{ $t("Cancel") }}</span>
                    </router-link>
                    <loading-button :loading="isLoading" class="secondary-btn">
                        <span class="mr-1">
                            <CustomIcon name="updateIcon" />
                        </span>
                        {{ $t("Update") }}
                    </loading-button>
                </div>
            </div>
            <!--===========================================-->
            <!--===========================================-->
            <div v-else-if="activeTab == `opc_health`">
                <div class="table-responsive">
                    <!-- <table class="doc-table">
                        <thead>
                            <tr>
                                <th>
                                    <div class="flex items-center">
                                        <div class="cursor-pointer">
                                            <CustomIcon name="addCricleIcon" />
                                        </div>
                                    </div>
                                </th>
                                <th>
                                    <div class="flex items-center">
                                        <span>{{ $t("Name") }}</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="flex items-center">
                                        <span>{{ $t("Health") }}</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="flex items-center">
                                        <span>{{ $t("On State From") }}</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="flex items-center">
                                        <span>{{ $t("Last Execution") }}</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="flex items-center"></div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>Healthchecks</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>Healthchecks</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span
                                            >2023-11-27T22:53:23.754413+01:00</span
                                        >
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <div class="flex items-center mr-2">
                                            <div class="mr-1">
                                                <CustomIcon name="dateIcon" />
                                            </div>
                                            <span>11/27/2023</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="mr-1">
                                                <CustomIcon name="clockIcon" />
                                            </div>
                                            <span>11:52:01 PM</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table> -->
                </div>
                <div class="table-responsive roles-table">
                    <table class="doc-table">
                        <thead>
                            <tr>
                                <th style="width: 300px">
                                    <div class="flex items-center">
                                        <span>{{ $t("Name") }}</span>
                                    </div>
                                </th>
                                <!-- <th style="width: 250px">
                                    <div class="flex items-center">
                                        <span>{{ $t("Tags") }}</span>
                                    </div>
                                </th> -->
                                <th>
                                    <div class="flex items-center">
                                        <span>{{ $t("Health") }}</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="flex items-center">
                                        <span>{{ $t("Description") }}</span>
                                    </div>
                                </th>
                                <!-- <th>
                                    <div class="flex items-center">
                                        <span>{{ $t("Duration") }}</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="flex items-center">
                                        <span>{{ $t("Details") }}</span>
                                    </div>
                                </th> -->
                                <th>
                                    <div class="flex items-center">
                                        <span>{{ $t("On State From") }}</span>
                                    </div>
                                </th>
                                <th>
                                    <div class="flex items-center">
                                        <span>{{ $t("Last Execution") }}</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="healthCheckItem in healthCheckListing"
                                :key="healthCheckItem.id"
                                class="hover:bg-gray-100 focus-within:bg-gray-100"
                            >
                                <td>
                                    <div
                                        class="flex items-center whitespace-normal leading-5"
                                    >
                                        <span>{{ healthCheckItem.name }}</span>
                                    </div>
                                </td>
                                <!-- <td>
                                    <div class="flex items-center">
                                        <ul class="tags">
                                            <li>
                                                <span>CircuitBreaker</span>
                                            </li>
                                            <li>
                                                <span>Http</span>
                                            </li>
                                        </ul>
                                    </div>
                                </td> -->
                                <td>
                                    <div class="flex items-center">
                                        <div class="mr-1">
                                            <CustomIcon
                                                :name="
                                                    healthCheckItem.status ==
                                                    'Healthy'
                                                        ? 'tableCheckIcon'
                                                        : 'tableErrorIcon'
                                                "
                                            />
                                        </div>
                                        <span>{{
                                            healthCheckItem.status
                                        }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <span>{{
                                            healthCheckItem.description
                                        }}</span>
                                    </div>
                                </td>
                                <!-- <td>
                                    <div class="flex items-center">
                                        <span>00:00:00.0000011</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <div class="on-promise-history">
                                            <div
                                                class="cursor-pointer"
                                                @mouseover="showDropdown"
                                                @mouseout="hideDropdown"
                                            >
                                                <CustomIcon
                                                    name="tableHistoryIcon"
                                                />
                                            </div>
                                            <div
                                                class="on-promise-card"
                                                @mouseover="showDropdown"
                                                @mouseout="hideDropdown"
                                                v-show="isDropdownVisible"
                                            >
                                                <div class="title">
                                                    <h3 class="mr-2">
                                                        {{ $t("SQL Server") }}
                                                    </h3>
                                                    <CustomIcon name="iIcon" />
                                                </div>
                                                <div class="">
                                                    <div class="health">
                                                        <div class="mr-2">
                                                            <CustomIcon
                                                                name="tableCheckIcon"
                                                            />
                                                        </div>
                                                        <h3>Healthy</h3>
                                                    </div>
                                                    <div class="health-tree">
                                                        <ul>
                                                            <li
                                                                class="unhealthy"
                                                            >
                                                                <div
                                                                    class="health-tree-left justify-end"
                                                                >
                                                                    <div
                                                                        class="unhealth-btn"
                                                                    >
                                                                        <div
                                                                            class="mr-1"
                                                                        >
                                                                            <CustomIcon
                                                                                name="tableErrorIcon"
                                                                            />
                                                                        </div>
                                                                        <span
                                                                            >Unhealthy</span
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="health-tree-right"
                                                                >
                                                                    <div
                                                                        class="flex items-center metas"
                                                                    >
                                                                        <div
                                                                            class="flex items-center mr-1"
                                                                        >
                                                                            <div
                                                                                class="mr-1"
                                                                            >
                                                                                <CustomIcon
                                                                                    name="dateIcon"
                                                                                />
                                                                            </div>
                                                                            <span
                                                                                >11/27/2023</span
                                                                            >
                                                                        </div>
                                                                        <div
                                                                            class="flex items-center"
                                                                        >
                                                                            <div
                                                                                class="mr-1"
                                                                            >
                                                                                <CustomIcon
                                                                                    name="clockIcon"
                                                                                />
                                                                            </div>
                                                                            <span
                                                                                >11:52:01
                                                                                PM</span
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li class="healthy">
                                                                <div
                                                                    class="health-tree-right"
                                                                >
                                                                    <div
                                                                        class="flex items-center metas"
                                                                    >
                                                                        <div
                                                                            class="flex items-center mr-1"
                                                                        >
                                                                            <div
                                                                                class="mr-1"
                                                                            >
                                                                                <CustomIcon
                                                                                    name="dateIcon"
                                                                                />
                                                                            </div>
                                                                            <span
                                                                                >11/27/2023</span
                                                                            >
                                                                        </div>
                                                                        <div
                                                                            class="flex items-center"
                                                                        >
                                                                            <div
                                                                                class="mr-1"
                                                                            >
                                                                                <CustomIcon
                                                                                    name="clockIcon"
                                                                                />
                                                                            </div>
                                                                            <span
                                                                                >11:52:01
                                                                                PM</span
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="health-tree-left"
                                                                >
                                                                    <div
                                                                        class="health-btn"
                                                                    >
                                                                        <div
                                                                            class="mr-1"
                                                                        >
                                                                            <CustomIcon
                                                                                name="tableCheckIcon"
                                                                            />
                                                                        </div>
                                                                        <span
                                                                            >Healthy</span
                                                                        >
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li
                                                                class="unhealthy"
                                                            >
                                                                <div
                                                                    class="health-tree-left justify-end"
                                                                >
                                                                    <div
                                                                        class="unhealth-btn"
                                                                    >
                                                                        <div
                                                                            class="mr-1"
                                                                        >
                                                                            <CustomIcon
                                                                                name="tableErrorIcon"
                                                                            />
                                                                        </div>
                                                                        <span
                                                                            >Unhealthy</span
                                                                        >
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="health-tree-right"
                                                                >
                                                                    <div
                                                                        class="flex items-center metas"
                                                                    >
                                                                        <div
                                                                            class="flex items-center mr-1"
                                                                        >
                                                                            <div
                                                                                class="mr-1"
                                                                            >
                                                                                <CustomIcon
                                                                                    name="dateIcon"
                                                                                />
                                                                            </div>
                                                                            <span
                                                                                >11/27/2023</span
                                                                            >
                                                                        </div>
                                                                        <div
                                                                            class="flex items-center"
                                                                        >
                                                                            <div
                                                                                class="mr-1"
                                                                            >
                                                                                <CustomIcon
                                                                                    name="clockIcon"
                                                                                />
                                                                            </div>
                                                                            <span
                                                                                >11:52:01
                                                                                PM</span
                                                                            >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td> -->
                                <td>
                                    <div class="flex items-center">
                                        <span>{{
                                            healthCheckItem.onStateFrom
                                        }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center">
                                        <div class="flex items-center mr-2">
                                            <div class="mr-1">
                                                <CustomIcon name="dateIcon" />
                                            </div>
                                            <span>{{
                                                formatDate(
                                                    healthCheckItem.lastExecuted,
                                                    "date"
                                                )
                                            }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="mr-1">
                                                <CustomIcon name="clockIcon" />
                                            </div>
                                            <span>{{
                                                formatDate(
                                                    healthCheckItem.lastExecuted,
                                                    "time"
                                                )
                                            }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--===========================================-->
            <!--===========================================-->
            <div v-else-if="activeTab == `power_shell`">
                <iframe
                    :src="iframeSrc"
                    style="width: 100%; height: 700px"
                ></iframe>
            </div>
            <!--===========================================-->
            <!--===========================================-->
            <div v-else-if="activeTab == `monitoring`"></div>
            <!--===========================================-->
            <!--===========================================-->
            <div class="flex" v-if="form.operatingSystem == 'linux'">
                <div>
                    <button
                        id="dropdownDefaultButton"
                        data-dropdown-toggle="dropdown"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        type="button"
                        @click="isDropdown = !isDropdown"
                    >
                        {{ $t("Install Systems") }}
                        <svg
                            class="w-4 h-4 ml-2"
                            aria-hidden="true"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            ></path>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div
                        v-if="isDropdown"
                        id="dropdown"
                        class="z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700"
                    >
                        <ul
                            class="py-2 text-sm text-gray-700 dark:text-gray-200"
                            aria-labelledby="dropdownDefaultButton"
                        >
                            <li>
                                <a
                                    @click="install"
                                    href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                    >{{ $t("Install Products") }}</a
                                >
                            </li>
                            <li>
                                <a
                                    @click="
                                        () => {
                                            installedToggle = true;
                                            isDropdown = false;
                                        }
                                    "
                                    href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                    >{{ $t("View Installed Products") }}</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--===========================================-->
            <!--===========================================-->
        </form>
        <terminal
            v-if="isInstall"
            :isInstallation="isInstallation"
            :commands="commands"
            @cancelled="cancelTerminal"
            :id="this.$route.params.id"
            :isInvoice="false"
        ></terminal>
        <view-installed-products
            v-if="installedToggle"
            @cancelled="installedToggle = false"
            :products="form.installedProducts"
        ></view-installed-products>
        <system-select-products
            v-if="productToggle"
            @selected="addProducts"
            @cancelled="productToggle = false"
            :selectedItems="form.systemProducts"
            :products="products"
        ></system-select-products>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import PriceInput from "@/Components/PriceInput.vue";
import SelectLinkInput from "@/Components/SelectLinkInput.vue";
import TrashedMessage from "@/Components/TrashedMessage.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import SystemSelectProducts from "@/Components/SystemSelectProducts.vue";
import { NativeEventSource, EventSourcePolyfill } from "event-source-polyfill";
import Terminal from "../../Invoices/Terminal.vue";
import ViewInstalledProducts from "@/Components/ViewInstalledProducts.vue";
import ProductTabs from "../Components/ProductTabs.vue";
import Dropdown from "@/Components/Dropdown.vue";
export default {
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                isSystem: true,
            });
            await this.$store.dispatch("operatingSystem/list", {
                page: 1,
            });
            await this.$store.dispatch("softwares/list", {
                page: 1,
            });
            const productResponse = await this.$store.dispatch(
                "products/productsWithQuantity",
                { page: 1, type: "software" }
            );
            this.products = productResponse?.data ?? [];
        } catch (e) {}
        await this.refresh();
    },
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        TextAreaInput,
        PriceInput,
        SelectLinkInput,
        TrashedMessage,
        MultiSelectInput,
        SystemSelectProducts,
        Terminal,
        ViewInstalledProducts,
        ProductTabs,
        Dropdown,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies"]),
        ...mapGetters("operatingSystem", ["operatingSystem"]),
        ...mapGetters("softwares", ["softwares"]),
    },
    watch: {
        async "form.software"(val) {
            this.versions = [];
            //when page render first time this will not excute
            if (this.isData) {
                await this.$nextTick(() => {
                    this.form.eloVersion = ""; // Clear eloVersion after the next rendering cycle
                });
            } else this.isData = true;

            if (val != null) {
                const response = await this.$store.dispatch(
                    "versions/getVersions",
                    val.id
                );
                this.versions = response?.data ?? [];
            }
        },
        async clientId(val) {
            if (typeof val?.clientId !== "undefined") {
                this.clientId = val;
                this.shellId = new Date().toISOString();
                this.iframeSrc =
                    import.meta.env.VITE_CLIENT_SHELL_SERVICE_URL +
                    "ui/shell/?shellId=" +
                    this.shellId +
                    "&clientId=" +
                    this.clientId?.clientId +
                    "&access_token=" +
                    this.token;

                let healthCheckResponse = await this.$store.dispatch(
                    "systems/healthCheck",
                    this.clientId?.clientId
                );
                this.healthCheckListing = healthCheckResponse?.data;
            } else {
                this.clientId = '';
                this.shellId = '';
                this.iframeSrc = '';
                this.healthCheckListing = '';
            }
        },
    },
    data() {
        return {
            systems: {},
            returnPage: "",
            productToggle: false,
            form: {
                systemName: "",
                systemUser: "",
                type: "",
                systemProducts: [],
                serverIp: "",
                serverPort: "",
                username: "",
                password: "",
                namespace: "",
                instanceType: "",
                operatingSystem: "",
                software: "",
                eloVersion: "",
                installedProducts: [],
            },
            versions: [],
            isData: false,
            activeTab: "systems",
            activeClasses: "active",
            inactiveClasses: "inactive",
            products: [],
            isInstall: false,
            isInstallation: true,
            commands: [],
            clients: [],
            clientId: "",
            shellId: "",
            isDropdown: false,
            installedToggle: false,
            isDropdownVisible: false,
            token: "",
            iframeSrc: "",
            healthCheckListing: [],
            showPassword: false,
            inputType: "password"
        };
    },
    methods: {
        showDropdown() {
            this.isDropdownVisible = true;
        },
        hideDropdown() {
            this.isDropdownVisible = false;
        },
        async refresh() {
            if (this.$route.query.page) {
                this.returnPage = this.$route.query.page;
            }

            try {
                const response = await this.$store.dispatch(
                    "systems/show",
                    this.$route.params.id
                );
                this.systems = response?.data?.systems ?? {};
                document.title = "Edit Premise " + this.systems?.systemName;
                var systemUser =
                    this.companies.data.find(
                        (customer) => customer.id == this.systems.systemUser
                    ) ?? null;
                // if the company does not exist in the companies listing then use the show API to fetch the company separately
                if (!systemUser && this.systems.systemUser) {
                    const singleCompanyResponse = await this.$store.dispatch(
                        "companies/show",
                        this.systems.systemUser
                    );
                    systemUser = singleCompanyResponse?.data?.modelData ?? "";
                }

                this.form = {
                    systemName: this.systems?.systemName,
                    systemUser: systemUser,
                    type: this.systems.type,
                    systemProducts: this.systems.systemProducts,
                    serverIp: this.systems.serverIp,
                    serverPort: this.systems.serverPort,
                    username: this.systems.username,
                    password: this.systems.password,
                    namespace: this.systems.namespace,
                    instanceType: this.systems.instanceType,
                    operatingSystem: this.systems.operatingSystem ?? {},
                    software: this.systems.software ?? {},
                    eloVersion: this.systems.eloVersion ?? {},
                    installedProducts: this.systems.installedProducts,
                };

                let clients = await this.$store.dispatch(
                    "systems/getShellClients"
                );
                this.clients = clients?.data;
                this.clientId = this.clients?.find(
                    (client) => client.clientId == this.systems?.reverseClientId
                );
                this.token = localStorage.getItem("token");


                if (this.clientId != "") {
                    let healthCheckResponse = await this.$store.dispatch(
                        "systems/healthCheck",
                        this.clientId?.clientId
                    );
                    this.healthCheckListing = healthCheckResponse?.data;
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        cancelTerminal() {
            this.isInstall = false;
            this.refresh();
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("systems/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        operatingSystemId: this.form.operatingSystem?.id,
                        software: this.form.software?.id,
                        eloVersion: this.form.eloVersion?.id,
                        systemUser: this.form.systemUser?.id,
                        reverseClientId: this.clientId.clientId,
                    },
                });
                await this.$store.dispatch("systems/list", {
                    type: "premise",
                });

                this.$router.push(
                    "/systems/on-premise?page=" + this.returnPage
                );
            } catch (e) {}
        },
        addProducts(products) {
            this.form.systemProducts = products;
            this.productToggle = false;
        },
        install() {
            this.isDropdown = false;
            this.isInstall = true;
            this.commands = ["Starting Installations"];
            let es = new EventSourcePolyfill(
                "/api/install-products/" + this.$route.params.id,
                {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                }
            );
            let i = 0;
            es.addEventListener(
                "message",
                (event) => {
                    i++;
                    this.commands[i] = event.data;
                },
                false
            );
            es.onerror = (err) => {
                es.close();
                this.isInstallation = false;
                console.error("EventSource failed:", err);
            };
        },
        formatDate(dateString, format) {
            const date = new Date(dateString);

            if (format === "date") {
                // Format date as MM/DD/YYYY and add additional HTML
                return `${(date.getMonth() + 1)
                    .toString()
                    .padStart(2, "0")}/${date
                    .getDate()
                    .toString()
                    .padStart(2, "0")}/${date.getFullYear()}`;
            } else if (format === "time") {
                // Format time as hh:mm:ss A and add additional HTML
                return `${date.toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit",
                    hour12: true,
                })}`;
            }

            return "";
        },
        handleChildEvent() {
            this.showPassword = !this.showPassword;
            this.inputType = this.showPassword ? "text" : "password";
        },
    },
};
</script>
