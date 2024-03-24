<template>
    <div>
        <PageHeader :items="breadcrumbItems"  />
        <h6 class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800">
            {{ $t("Configuration") }}
        </h6>

        <div class="w-full bg-white rounded-md shadow">
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <MultiSelectInput
                    v-if="shouldShow"
                    v-model="form.customer"
                    :required="true"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :textLabel="$t('Customer')"
                    :placeholder="$t('Select Customer')"
                    :options="companies.data"
                    :multiple="false"
                    label="companyName"
                    trackBy="id"
                    moduleName="companies"
                    :error="errors.customer"
                />
                <MultiSelectInput
                    v-if="shouldShow"
                    v-model="form.system"
                    :required="true"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :textLabel="$t('System')"
                    :placeholder="$t('Select System')"
                    :options="premiseSystems.data"
                    :multiple="false"
                    label="systemName"
                    trackBy="id"
                    moduleName="systems"
                    :query="{ type: 'premise' }"
                    :error="errors.system"
                />
                <div class="pr-6 w-full lg:w-1/3 mb-8">
                    <label class="form-label"
                        ><span style="color: red">*</span>&nbsp;{{
                            $t("Creation Date")
                        }}:</label
                    >
                    <datepicker
                        :style="dropdownStyles"
                        :clearable="false"
                        :enable-time-picker="false"
                        auto-apply
                        :close-on-auto-apply="false"
                        v-model="form.createDate"
                        :class="errors.createDate ? 'error' : ''"
                    />
                    <div v-if="errors?.createDate" class="form-error">
                        {{ errors.createDate }}
                    </div>
                </div>
                <MultiSelectInput
                    v-if="shouldShow"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    v-model="form.consultant"
                    :isDisabled="true"
                    :options="userProfiles?.data ?? []"
                    :multiple="false"
                    :text-label="$t('Consultant')"
                    label="employee"
                    trackBy="userId"
                    moduleName="userProfile"
                />
            </div>
            <button class="docsHeroColorBlue px-3 py-2 rounded ml-8 mb-8">
                Save
            </button>
            <button
                v-if="$route.params.id == 999"
                @click="generate"
                class="docsHeroColorBlue px-3 py-2 rounded ml-8 mb-8"
            >
                Generate
            </button>
        </div>

        <br />
        <br />
        <div v-if="$route.params.id == 999">
            <p>
                Die folgende Workshop Checkliste dient zur Unterstützung in
                einem ELO/SAP ERP Einführungsprojekt und dient als
                Kalkulationsgrundlage für die Angebotserstellung.
            </p>

            <br />

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="first-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="first-accordion"
                    >
                        {{ $t("Informationen zum SAP-System") }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 p-8">
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue font-bold ml-2"
                                    >SAP ERP ECC &nbsp;&nbsp;&nbsp;<span
                                        class="font-normal"
                                        >(Auch „SAP R/3“ oder „SAP NetWeaver
                                        Plattform“ genannt)</span
                                    ></label
                                >
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 90%"
                            >
                                <label class="color-blue font-bold ml-2"
                                    >ERP Version</label
                                >
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >5.0</label
                                    >
                                    &nbsp; &nbsp;
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >6.0</label
                                    >
                                </div>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 90%"
                            >
                                <label class="color-blue font-bold ml-2"
                                    >EHP Package</label
                                >
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >1</label
                                    >
                                    &nbsp; &nbsp;
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >2</label
                                    >
                                    &nbsp; &nbsp;
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >3</label
                                    >
                                    &nbsp; &nbsp;
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >4</label
                                    >
                                    &nbsp; &nbsp;
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >5</label
                                    >
                                    &nbsp; &nbsp;
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >6</label
                                    >
                                    &nbsp; &nbsp;
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >7</label
                                    >
                                    &nbsp; &nbsp;
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >8</label
                                    >
                                </div>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 90%"
                            >
                                <label class="font-normal ml-2"
                                    >Beispiele</label
                                >
                                <p>
                                    SAP ERP ECC 6.0 Enhancement Package 7 SAP
                                    R/3 5.0 EHP 4
                                </p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 100%"
                            >
                                <p class="ml-2">
                                    Welche Module setzt der Kunde ein?
                                </p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label class="ml-2 color-blue font-bold" for=""
                                    >Module</label
                                >
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >FI</label
                                    >
                                </div>
                                <p class="color-blue">
                                    (Externes Rechnungswesen &
                                    Finanzbuchhaltung)
                                </p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >CO</label
                                    >
                                </div>
                                <p class="color-blue">
                                    (Internes Rechnungswesen & Controlling)
                                </p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >TR</label
                                    >
                                </div>
                                <p class="color-blue">(Treasury)</p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >SD</label
                                    >
                                </div>
                                <p class="color-blue">(Verkauf/ Vertrieb)</p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >MM</label
                                    >
                                </div>
                                <p class="color-blue">
                                    (Materialwirtschaft / Einkauf / Lieferant /
                                    Lager)
                                </p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >PP</label
                                    >
                                </div>
                                <p class="color-blue">
                                    (Produktionsplanung & -steuerung)
                                </p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >HCM</label
                                    >
                                </div>
                                <p class="color-blue">(Personalmanagement)</p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >QM</label
                                    >
                                </div>
                                <p class="color-blue">(Qualitätsmanagement)</p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >PM</label
                                    >
                                </div>
                                <p class="color-blue">(Instandhaltung)</p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >PLM</label
                                    >
                                </div>
                                <p class="color-blue">
                                    (Produkt Lifecycle Management)
                                </p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >CS</label
                                    >
                                </div>
                                <p class="color-blue">(Kunden Service)</p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >LE</label
                                    >
                                </div>
                                <p class="color-blue">
                                    (Transport und Versand)
                                </p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >PS</label
                                    >
                                </div>
                                <p class="color-blue">(Projekt System)</p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >FSCM</label
                                    >
                                </div>
                                <p class="color-blue">
                                    (Financial Supply Chain Management)
                                </p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 10% 10% 80%"
                            >
                                <label
                                    class="ml-2 color-blue font-bold"
                                    for=""
                                ></label>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >RE</label
                                    >
                                </div>
                                <p class="color-blue">(Immobilienmanagement)</p>
                            </div>
                            &nbsp;&nbsp;
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue font-bold ml-2"
                                    >SAP S/4HANA</label
                                >
                            </div>
                            <div class="ml-8">
                                <input type="checkbox" />
                                <label class="font-bold color-blue ml-2" for=""
                                    >On Premise</label
                                >
                            </div>
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: 5% 20% 65%"
                            >
                                <div></div>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >Inhouse System</label
                                    >
                                </div>
                                <p class="color-blue">(Selbstbetrieb)</p>
                            </div>
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: 5% 20% 65%"
                            >
                                <div></div>
                                <div>
                                    <input type="checkbox" />
                                    <label
                                        class="font-normal color-blue ml-2"
                                        for=""
                                        >Rechenzentrum System
                                    </label>
                                </div>
                                <p class="color-blue">(Hosted)</p>
                            </div>
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: 5% 20% 65%"
                            >
                                <div></div>
                                <label class="font-bold color-blue" for=""
                                    >Version:
                                </label>
                                <div
                                    class="grid gap-2"
                                    style="
                                        grid-template-columns: repeat(
                                            auto-fit,
                                            minmax(100px, 1fr)
                                        );
                                    "
                                >
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="font-normal color-blue ml-2"
                                            for=""
                                            >1511
                                        </label>
                                    </div>
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="font-normal color-blue ml-2"
                                            for=""
                                            >1605
                                        </label>
                                    </div>
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="font-normal color-blue ml-2"
                                            for=""
                                            >1610
                                        </label>
                                    </div>
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="font-normal color-blue ml-2"
                                            for=""
                                            >1709
                                        </label>
                                    </div>
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="font-normal color-blue ml-2"
                                            for=""
                                            >1809
                                        </label>
                                    </div>
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="font-normal color-blue ml-2"
                                            for=""
                                            >1909
                                        </label>
                                    </div>
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="font-normal color-blue ml-2"
                                            for=""
                                            >2020
                                        </label>
                                    </div>
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="font-normal color-blue ml-2"
                                            for=""
                                            >2021
                                        </label>
                                    </div>
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="font-normal color-blue ml-2"
                                            for=""
                                            >2022
                                        </label>
                                    </div>
                                </div>
                            </div>
                            &nbsp;&nbsp;
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue font-bold ml-2"
                                    >Hybrid Cloud &nbsp;&nbsp;&nbsp;<span
                                        class="font-normal"
                                        >(Vereint beide Welten On Premise mit
                                        Cloud)
                                    </span></label
                                >
                            </div>
                            &nbsp;&nbsp;
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue font-bold ml-2"
                                    >Cloud</label
                                >
                            </div>
                            <div class="ml-8">
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 20% 80%"
                                >
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="color-blue font-normal ml-2"
                                            >SAP S/4HANA Cloud</label
                                        >
                                    </div>
                                    <span class="font-normal color-blue"
                                        >(Multi Tenant Edition / Public Cloud /
                                        Essential Edition)
                                    </span>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 20% 80%"
                                >
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="color-blue font-normal ml-2"
                                            >SAP S/4HANA Cloud PE</label
                                        >
                                    </div>
                                    <span class="font-normal color-blue"
                                        >(Single Tenant Edition / Private Cloud
                                        / Extended Edition)
                                    </span>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 20% 80%"
                                >
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="color-blue font-normal ml-2"
                                            >SAP HEC</label
                                        >
                                    </div>
                                    <span class="font-normal color-blue"
                                        >(SAP HANA Enterprise Cloud / Hosted by
                                        SAP / Kaufsystem)
                                    </span>
                                </div>
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 20% 85%"
                                >
                                    <label class="font-bold color-blue" for=""
                                        >Version:
                                    </label>
                                    <div>
                                        <div
                                            class="grid gap-2"
                                            style="
                                                grid-template-columns: repeat(
                                                    auto-fit,
                                                    minmax(200px, 1fr)
                                                );
                                            "
                                        >
                                            <div>
                                                <input type="checkbox" />
                                                <label
                                                    class="font-normal color-blue ml-2"
                                                    for=""
                                                    >1908
                                                </label>
                                            </div>
                                            <div>
                                                <input type="checkbox" />
                                                <label
                                                    class="font-normal color-blue ml-2"
                                                    for=""
                                                    >1911
                                                </label>
                                            </div>
                                            <div>
                                                <input type="checkbox" />
                                                <label
                                                    class="font-normal color-blue ml-2"
                                                    for=""
                                                    >2002
                                                </label>
                                            </div>
                                            <div>
                                                <input type="checkbox" />
                                                <label
                                                    class="font-normal color-blue ml-2"
                                                    for=""
                                                    >2005
                                                </label>
                                            </div>
                                            <div></div>
                                        </div>
                                        <div
                                            class="grid gap-2"
                                            style="
                                                grid-template-columns: repeat(
                                                    auto-fit,
                                                    minmax(200px, 1fr)
                                                );
                                            "
                                        >
                                            <div>
                                                <input type="checkbox" />
                                                <label
                                                    class="font-normal color-blue ml-2"
                                                    for=""
                                                    >2008
                                                </label>
                                            </div>
                                            <div>
                                                <input type="checkbox" />
                                                <label
                                                    class="font-normal color-blue ml-2"
                                                    for=""
                                                    >2011
                                                </label>
                                            </div>
                                            <div>
                                                <input type="checkbox" />
                                                <label
                                                    class="font-normal color-blue ml-2"
                                                    for=""
                                                    >2102
                                                </label>
                                            </div>
                                            <div>
                                                <input type="checkbox" />
                                                <label
                                                    class="font-normal color-blue ml-2"
                                                    for=""
                                                    >2105
                                                </label>
                                            </div>
                                            <div></div>
                                        </div>
                                        <div
                                            class="grid gap-2"
                                            style="
                                                grid-template-columns: repeat(
                                                    auto-fit,
                                                    minmax(200px, 1fr)
                                                );
                                            "
                                        >
                                            <div>
                                                <input type="checkbox" />
                                                <label
                                                    class="font-normal color-blue ml-2"
                                                    for=""
                                                    >2108
                                                </label>
                                            </div>
                                            <div>
                                                <input type="checkbox" />
                                                <label
                                                    class="font-normal color-blue ml-2"
                                                    for=""
                                                    >2110
                                                </label>
                                            </div>
                                            <div>
                                                <input type="checkbox" />
                                                <label
                                                    class="font-normal color-blue ml-2"
                                                    for=""
                                                    >2202
                                                </label>
                                            </div>
                                            <div>
                                                <input type="checkbox" />
                                                <label
                                                    class="font-normal color-blue ml-2"
                                                    for=""
                                                    >2208
                                                </label>
                                            </div>
                                            <div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="second-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="second-accordion"
                    >
                        {{ $t("SAP-System Spezifikationen") }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 p-8">
                            <div>
                                <p class="color-blue font-bold">
                                    SAP-System-ID
                                </p>
                                &nbsp;
                                <div class="ml-8">
                                    <p>
                                        Der Name des SAP-Systems. Dieser ist
                                        eindeutig und besteht aus mindestens 3
                                        Zeichen.
                                    </p>
                                    <p>
                                        <span class="font-bold">Beispiel:</span>
                                        TS1
                                    </p>
                                    <TextInput
                                        v-model="sapSystemID"
                                        class="w-1/3 mb-2"
                                        placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                                    ></TextInput>
                                </div>
                            </div>
                            &nbsp;
                            <div>
                                <p class="color-blue font-bold">
                                    Mandant/Client
                                </p>
                                &nbsp;
                                <div class="ml-8">
                                    <p>
                                        SAP-Client bzw. SAP-Mandant, zu dem die
                                        Verbindung hergestellt werden soll.
                                    </p>
                                    <p>
                                        <span class="font-bold">Beispiel:</span>
                                        900
                                    </p>
                                    <TextInput
                                        v-model="client"
                                        class="w-1/3 mb-2"
                                        placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                                    ></TextInput>
                                </div>
                            </div>
                            &nbsp;
                            <div>
                                <p class="color-blue font-bold">
                                    SAP Anwendungsserver / Hostname
                                </p>
                                &nbsp;
                                <div class="ml-8">
                                    <p>
                                        IP-Adresse oder FQDN des SAP-Systems für
                                        die RFC-Kommunikation
                                    </p>
                                    <p>
                                        <span class="font-bold">Beispiel:</span>
                                        SAPDEMO oder 10.99.120.160
                                    </p>
                                    <TextInput
                                        v-model="host"
                                        class="w-1/3 mb-2"
                                        placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                                    ></TextInput>
                                </div>
                            </div>
                            &nbsp;
                            <div>
                                <p class="color-blue font-bold">
                                    SAP-Instanz Nummer / System Nr.
                                </p>
                                &nbsp;
                                <div class="ml-8">
                                    <p>
                                        SAP-Instanz Nummer für die
                                        RFC-Kommunikation mit dem
                                        Applikationsserver
                                    </p>
                                    <p>
                                        <span class="font-bold">Beispiel:</span>
                                        00
                                    </p>
                                    <TextInput
                                        v-model="systemNr"
                                        class="w-1/3 mb-2"
                                        placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                                    ></TextInput>
                                </div>
                            </div>
                            &nbsp;

                            <div>
                                <p class="color-blue font-bold">
                                    Schnittstellen Benutzer
                                </p>
                                &nbsp;
                                <div class="ml-8">
                                    <TextInput
                                        v-model="user"
                                        class="w-1/3 mb-2"
                                        placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                                    ></TextInput>
                                </div>
                            </div>
                            &nbsp;
                            <div>
                                <p class="color-blue font-bold">
                                    Schnittstellen Passwort
                                </p>
                                &nbsp;
                                <div class="ml-8">
                                    <TextInput
                                        v-model="password"
                                        class="w-1/3 mb-2"
                                        placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                                    ></TextInput>
                                </div>
                            </div>
                            &nbsp;
                            <div>
                                <p class="color-blue font-bold">
                                    RFC-Einstellungen
                                </p>
                                &nbsp;
                                <div
                                    class="grid gap-2 ml-8"
                                    style="grid-template-columns: 20% 80%"
                                >
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="color-blue ml-2 font-bold"
                                            for=""
                                            >SAProuter-String
                                        </label>
                                    </div>
                                    <div>
                                        <TextInput
                                            class="w-1/3 mb-2"
                                            placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                                        ></TextInput>
                                    </div>
                                </div>
                                <div class="ml-8">
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="color-blue ml-2 font-bold"
                                            for=""
                                            >Anmeldung via Message-Server
                                        </label>
                                    </div>
                                    <div
                                        class="grid gap-2 ml-8"
                                        style="grid-template-columns: 20% 80%"
                                    >
                                        <p class="color-blue font-bold">
                                            Logon-Gruppe
                                        </p>
                                        <div>
                                            <TextInput
                                                class="w-1/3 mb-2"
                                                placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                                            ></TextInput>
                                        </div>
                                    </div>
                                    <div
                                        class="grid gap-2 ml-8"
                                        style="grid-template-columns: 20% 80%"
                                    >
                                        <p class="color-blue font-bold">
                                            SAP-System-ID
                                        </p>
                                        <div>
                                            <TextInput
                                                class="w-1/3 mb-2"
                                                placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                                            ></TextInput>
                                        </div>
                                    </div>
                                    <div
                                        class="grid gap-2 ml-8"
                                        style="grid-template-columns: 20% 80%"
                                    >
                                        <p class="color-blue font-bold">
                                            Message-Server
                                        </p>
                                        <div>
                                            <TextInput
                                                class="w-1/3 mb-2"
                                                placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                                            ></TextInput>
                                        </div>
                                    </div>
                                    <div
                                        class="grid gap-2 ml-8"
                                        style="grid-template-columns: 20% 80%"
                                    >
                                        <p class="color-blue font-bold">
                                            Name oder Port des Dienstes
                                        </p>
                                        <div>
                                            <TextInput
                                                class="w-1/3 mb-2"
                                                placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                                            ></TextInput>
                                        </div>
                                    </div>
                                </div>
                                &nbsp;
                                <div class="ml-8">
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="color-blue ml-2 font-bold"
                                            for=""
                                            >Sichere Anmeldung via SNC
                                        </label>
                                    </div>
                                </div>
                                &nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="third-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="third-accordion"
                    >
                        {{ $t("Anzeige von Dokumenten aus SAP") }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 p-8">
                            <div class="ml-8">
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Absprung in ELO Java Client
                                </label>
                            </div>
                            <div class="ml-8">
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Absprung in ELO Web Client
                                </label>
                            </div>
                            <p>
                                <span class="font-bold"
                                    >Zusätzlich Optional</span
                                >(wenn komplette ELO/SAP-Suite lizenziert ist)
                            </p>
                            <div class="ml-8">
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >ELO Integration Client in SAP Toolbox
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="fourth-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="fourth-accordion"
                    >
                        {{ $t("Zu archivierende Dokumente") }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 py-8 pr-8">
                            <div class="flex justify-between">
                                <p class="color-blue font-bold self-center">
                                    In SAP erzeugte Dokumente
                                </p>
                                <button
                                    @click="
                                        inSAPErzeugteDokumenteToggle =
                                            !inSAPErzeugteDokumenteToggle
                                    "
                                    class="docsHeroColorBlue px-3 py-2 self-center rounded"
                                >
                                    + Add
                                </button>
                            </div>
                            &nbsp;
                            <table class="w-full whitespace-nowrap">
                                <tr class="text-left font-bold">
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("SAP System") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Content Repository") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Objekttyp") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Beschreibung") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Dokumentenart (OAC2)*") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Dokumenttyp (OAD2)") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Verknüpfungstabelle (OAC3)") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Actions") }}
                                    </th>
                                </tr>
                                <tr
                                    v-for="(
                                        item, index
                                    ) in inSAPErzeugteDokumente"
                                    :key="'table-1-' + index"
                                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                                >
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.sapSystem }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.contentRepository }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.objekttyp }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.beschreibung }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.dokumentenart }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.dokumenttyp }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.verknüpfungstabelle }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        <button
                                            class="mr-2"
                                            @click.prevent="
                                                editInSAPErzeugteDokumente(
                                                    index
                                                )
                                            "
                                        >
                                            <font-awesome-icon
                                                icon="fa-regular fa-pen-to-square"
                                            ></font-awesome-icon>
                                        </button>
                                        <button
                                            @click.prevent="
                                                deleteInSAPErzeugteDokumente(
                                                    index
                                                )
                                            "
                                        >
                                            <font-awesome-icon
                                                icon="fa-regular fa-trash-can"
                                            ></font-awesome-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr
                                    class="flex p-5"
                                    v-if="!inSAPErzeugteDokumente.length"
                                >
                                    {{
                                        $t("No Items Exist")
                                    }}
                                </tr>
                            </table>
                            &nbsp;
                            <div class="flex justify-between">
                                <p class="color-blue font-bold self-center">
                                    Extern eingehende Dokumente (Barcode Upload
                                    Verknüpfung)
                                </p>
                                <button
                                    @click="
                                        externEingehendeDokumenteToggle =
                                            !externEingehendeDokumenteToggle
                                    "
                                    class="docsHeroColorBlue px-3 py-2 self-center rounded"
                                >
                                    + Add
                                </button>
                            </div>
                            &nbsp;
                            <table class="w-full whitespace-nowrap">
                                <tr class="text-left font-bold">
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("SAP System") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Content Repository") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Objekttyp") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Beschreibung") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Dokumentenart (OAC2)*") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Dokumenttyp (OAD2)") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Verknüpfungstabelle (OAC3)") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Action") }}
                                    </th>
                                </tr>
                                <tr
                                    v-for="(
                                        item, index
                                    ) in externEingehendeDokumente"
                                    :key="'table-2-' + index"
                                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                                >
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.sapSystem }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.contentRepository }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.objekttyp }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.beschreibung }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.dokumentenart }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.dokumenttyp }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        {{ item.verknüpfungstabelle }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        <button
                                            class="mr-2"
                                            @click.prevent="
                                                editExternEingehendeDokumente(
                                                    index
                                                )
                                            "
                                        >
                                            <font-awesome-icon
                                                icon="fa-regular fa-pen-to-square"
                                            ></font-awesome-icon>
                                        </button>
                                        <button
                                            @click.prevent="
                                                deleteExternEingehendeDokumente(
                                                    index
                                                )
                                            "
                                        >
                                            <font-awesome-icon
                                                icon="fa-regular fa-trash-can"
                                            ></font-awesome-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr
                                    class="flex p-5"
                                    v-if="!externEingehendeDokumente.length"
                                >
                                    {{
                                        $t("No Items Exist")
                                    }}
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="fifth-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="fifth-accordion"
                    >
                        {{ $t("Metadaten Masken") }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 p-8">
                            <p class="font-bold italic">Beschreibung:</p>
                            <p>
                                Die Metadaten Masken werden über den
                                Maskenwechsel zugewiesen. Je Dokumentart ist die
                                detaillierteste Art und entspricht dem meisten
                                Aufwand, da jede Dokumentart eine eigene Maske
                                erhält. Bei je Business Objekt werden können
                                mehrere Dokumentarten des jeweiligen Business
                                Objekts eine Metadaten Maske gemeinsam nutzen.
                            </p>
                            &nbsp;
                            <div class="ml-8">
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Metadaten Maske je Dokumentart
                                </label>
                            </div>
                            <div class="ml-8">
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Metadaten Maske je Business Objekt
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="sixth-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="sixth-accordion"
                    >
                        {{ $t("Metadaten Masken Felder") }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 p-8">
                            <p class="font-bold italic">Beschreibung:</p>
                            <p>
                                Die Transaktion /n/ELO/BO_FIELDS bietet die
                                Möglichkeit, sich alle Standard
                                SAP-Attributfelder zu SAP-Business-Objekten
                                anzeigen zu lassen. Diese Felder können rein
                                über Konfiguration in Metadaten Masken
                                übernommen werden. Zusätzliche Felder oder
                                Business Objekt müssen als Erweiterung in SAP
                                gepflegt werden, es dazu Customizing
                                (Programmierung) notwendig und bedeutet erhöhten
                                Aufwand für den Kunden.
                            </p>
                            &nbsp;
                            <div class="ml-8">
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Nur Standard SAP-Attributfelder
                                </label>
                            </div>
                            <div class="ml-8">
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Zusätzliche Felder (Erweiterungen)
                                </label>
                            </div>
                            <div class="ml-8">
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Eigene Entwickelte Business Objekte
                                    (Erweiterungen)
                                </label>
                            </div>
                            &nbsp;
                            <p>
                                Listen Sie folgend alle zusätzlichen Felder, das
                                dazugehöre Business Objekt sowie die
                                DatenbankTabelle auf:
                            </p>
                            &nbsp;
                            <p class="font-bold">Beispiel:</p>
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: 10% 10% 10% 10%"
                            >
                                <div></div>
                                <div>BUS2012</div>
                                <div></div>
                                <div></div>
                            </div>
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: 10% 10% 10% 10%"
                            >
                                <div></div>
                                <div></div>
                                <div>Feld XYZ</div>
                                <div>Tabelle ABC</div>
                            </div>
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: 10% 10% 10% 10%"
                            >
                                <div></div>
                                <div></div>
                                <div>Feld ABC</div>
                                <div>Tabelle XYZ</div>
                            </div>
                            &nbsp;
                            <TextInput
                                class="w-1/3 mb-2"
                                placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                            ></TextInput>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="seventh-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="seventh-accordion"
                    >
                        {{
                            $t(
                                "Automatische Metadaten-Aktualisierung (Ereignistypkopplung)"
                            )
                        }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 p-8">
                            <p class="font-bold">Beschreibung:</p>
                            <p>
                                Die Ereignistypkopplung kann ebenso zur
                                Aktualisierung von bereits in ELO gespeicherten
                                Metadaten eines Dokuments verwendet werden, wenn
                                Sie beispielsweise nach dem Ändern einer
                                Bestellung die Metadaten aktualisieren möchten.
                                Es wird dann für alle Dokumente des
                                entsprechenden SAPBusiness-Objekts ein erneuter
                                Indexdownload durchgeführt.
                            </p>
                            &nbsp;
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Automatische Aktualisierung verwenden
                                </label>
                            </div>
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Keine Automatische Aktualisierung verwenden
                                </label>
                            </div>
                            &nbsp;
                            <p class="font-bold">Beschreibung:</p>
                            <p>
                                Ebenso kann diese Ereignistypkopplung für den
                                Download von Metadaten nach einem erfolgreichen
                                Verknüpfen eines Barcodes über die
                                Barcode-Upload-Funktion verwendet werden
                            </p>
                            &nbsp;
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Automatische Aktualisierung verwenden
                                </label>
                            </div>
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Keine Automatische Aktualisierung verwenden
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="eighth-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="eighth-accordion"
                    >
                        {{ $t("Leere alle Felder vor erneutem Download") }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 p-8">
                            <p class="font-bold italic">Beschreibung:</p>
                            <p>
                                Dies bedeutet, dass bei einem erneuten
                                Indexdownload eines Dokuments die Felder (außer
                                die ArchiveLink-Systemfelder) geleert werden.
                                Ist diese Funktion deaktiviert, werden die
                                Metadaten fortlaufend in den Feldern ergänzt.
                            </p>
                            &nbsp;
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Leere alle Felder vor erneutem Index
                                    Download
                                </label>
                            </div>
                            <p>
                                Auflistung aller
                                Standard-SAP-ArchiveLink-Felder, die nicht
                                geleert werden, wenn der übergeordnete Punkt
                                aktiviert ist. Sind weitere Notwendig hier
                                eintragen:
                            </p>
                            &nbsp;
                            <TextInput
                                class="w-1/3 mb-2"
                                placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                            ></TextInput>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="tenth-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="tenth-accordion"
                    >
                        {{ $t("Strukturaufbau") }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 p-8">
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Chaosablage
                                </label>
                            </div>
                            &nbsp;
                            <p class="font-bold italic">Beschreibung:</p>
                            <p>
                                Die Dokumente werden in keiner Ablagestruktur
                                dargestellt, diese sind lediglich über die ELO
                                iSearch auffindbar. Die Dokumente werden mit der
                                normalen SAP DATA Maske verschlagwortet ohne
                                Objektspezifische Metadaten. Er werden nur
                                rudimentäre Verknüpfungsinformationen in die
                                Metadaten übernommen.
                            </p>
                            &nbsp;
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Mit übermitteltem Dokument
                                </label>
                            </div>
                            &nbsp;
                            <p class="font-bold italic">Beschreibung:</p>
                            <p>
                                Die Ordnerstruktur wird erst mit der
                                Übermittlung eines Dokumentes aus SAP aufgebaut.
                                Mit dem Maskenwechsel kann je SAP_OBJEKT die
                                Metadatenmaske frei gewählt werden. Standard
                                Auslieferung:
                            </p>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 20% 80%"
                            >
                                <li>Lieferant</li>
                                <p>(SAP Vendor / Business Objekt LFA1)</p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 20% 80%"
                            >
                                <li>Ausgangsrechnung</li>
                                <p>
                                    (SAP BillingDocument / Business Objekt VBRK)
                                </p>
                            </div>
                            <div
                                class="grid gap-2 ml-8"
                                style="grid-template-columns: 20% 80%"
                            >
                                <li>Bestellung</li>
                                <p>
                                    (SAP PurchaseOrder / Business Objekt
                                    BUS2012)
                                </p>
                            </div>
                            <p>
                                Für weitere Objekte muss eine entsprechende
                                Metadatenmaske gemäß Vorlage angelegt werden,
                                dies ist auch für eigen entwickelte Business
                                Objekte möglich. Diese Masken enthalten eine
                                Auswahl von Standard Attributen aus dem SAP
                                Business Objekt. Diese können beliebig in die
                                bestehenden Metadatenmasken als Felder
                                übernommen werden
                            </p>
                            <p>(Konfiguration). Transaktion: /ELO/BO_FIELDS</p>
                            <p>
                                Sollten weitere Felder benötigt außerhalb des
                                Standards benötigt werden oder eine bestehende
                                Entwicklung zur Datenbereitstellung
                                implementiert werden, kann eine Erweiterung für
                                den ELO Connectivity Pack – Indexdownload
                                erstellt werden. (Customizing)
                            </p>
                            &nbsp;
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Ereignisbasierter Strukturaufbau
                                </label>
                            </div>
                            &nbsp;
                            <p class="font-bold italic">Beschreibung:</p>
                            <p>
                                Die Ordnerstruktur wird mit eintreten des Events
                                in SAP in ELO erzeugt und ist somit nicht
                                abhängig von der Übermittlung eines Dokuments.
                                Klassisches Beispiel ist, das bei Anlage eines
                                Lieferanten in den SAP-Stammdaten die
                                entsprechende Ordnerstruktur im ELO erzeugt
                                wird.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="eleventh-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="eleventh-accordion"
                    >
                        {{ $t("Ablagepfade") }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 p-8">
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Standard Ablagepfade nach Business Objekt
                                </label>
                            </div>
                            <div>
                                <input type="checkbox" />
                                <label class="color-blue ml-2 font-bold" for=""
                                    >Individuelle Ablagepfade
                                </label>
                            </div>
                            &nbsp;
                            <p class="font-bold">Beispiel:</p>
                            &nbsp;
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: 10% 90%"
                            >
                                <p>
                                    <span class="font-bold">Objekt:</span>
                                    BUS2012
                                </p>
                                <p>
                                    <span class="font-bold">Dokumentart:</span>
                                    MEOORDER
                                </p>
                            </div>
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: 10% 90%"
                            >
                                <p>Pfad:</p>
                                <p>
                                    ¶SAP¶SAP_OBJ_TXT
                                    (SAP_OBJECT)¶VENDOR_VENDORNO
                                    VENDORNAME¶OBJECT_ID
                                </p>
                            </div>
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: 10% 90%"
                            >
                                <p>Pfad:</p>
                                <p>
                                    SAP/Bestellung (BUS2012)/4711
                                    DocsHero/45000100
                                </p>
                            </div>
                            <TextInput
                                class="w-1/3 mb-2"
                                placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                            ></TextInput>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="twelveth-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="twelveth-accordion"
                    >
                        {{
                            $t(
                                "Automatische Zuordnung von Benutzer- und Gruppenrechten"
                            )
                        }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 p-8">
                            <p class="font-bold italic">Beschreibung:</p>
                            <p>
                                Dieser Bereich erlaubt es, in Abhängigkeit von
                                bestimmten Werten Benutzer- und Gruppenrechte
                                auf ein Dokument oder dessen erstellte Struktur
                                zu setzen
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="thirteenth-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="thirteenth-accordion"
                    >
                        {{ $t("Vorlage-Strukturen") }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 p-8">
                            <div
                                class="grid gap-2"
                                style="grid-template-columns: 50% 50%"
                            >
                                <div>
                                    <p class="font-bold italic">
                                        Beschreibung:
                                    </p>
                                    <p>
                                        Diese Konfiguration dient neben dem
                                        einfachen Strukturaufbau aus Metadaten
                                        dazu, ein hinterlegtes Template
                                        (Vorlagenstruktur) bei der Ablage eines
                                        Dokuments automatisch zu erzeugen. Wird
                                        nun ein Dokument zu einem Objekt
                                        erstmalig abgelegt, wird aufgrund der
                                        hinterlegten Konfiguration eine
                                        Vorlagenstruktur aufgebaut.
                                    </p>
                                    &nbsp;
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="color-blue ml-2 font-bold"
                                            for=""
                                            >Keine Vorlagestrukturen
                                        </label>
                                    </div>
                                    <div>
                                        <input type="checkbox" />
                                        <label
                                            class="color-blue ml-2 font-bold"
                                            for=""
                                            >Zusätzliche Ablagestrukturen
                                        </label>
                                    </div>
                                    <TextInput
                                        class="mb-2"
                                        placeholder="Klicken oder tippen Sie hier, um Text einzugeben."
                                    ></TextInput>
                                </div>
                                <div>
                                    <img
                                        :src="workshopTemplateImage"
                                        alt="workshop-template"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="fourteenth-accordion"
                        :checked="true"
                    />
                    <label
                        style="font-size: 1.2rem; border: 1px solid #e4e4e4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 secondary-color font-bold"
                        for="fourteenth-accordion"
                    >
                        {{
                            $t(
                                "Ablageregeln für die Zuordnung von Dokumenten in Vorlage-Strukturen"
                            )
                        }}
                    </label>
                    <div class="tab-content border p-2">
                        <div class="-mb-8 -mr-6 p-8">
                            <p class="font-bold italic">Beschreibung:</p>
                            <p>
                                Als weitere Funktion besteht die Möglichkeit,
                                Dokumente (bspw. per Dokumentart) nach Regeln in
                                Unterverzeichnisse abzulegen. Diese
                                Verzeichnisse können durch die Vorlagenstruktur
                                erstellt werden.
                            </p>
                            &nbsp;
                            <div class="flex justify-end">
                                <button
                                    @click="
                                        ablageregelnToggle = !ablageregelnToggle
                                    "
                                    class="docsHeroColorBlue px-3 py-2 self-center rounded"
                                >
                                    + Add
                                </button>
                            </div>
                            &nbsp;
                            <table class="w-full whitespace-nowrap">
                                <tr class="text-left font-bold">
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Wenn Feld") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Wert") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("verwendetes Template ") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Feld") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Wert") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Subpfad") }}
                                    </th>
                                    <th
                                        class="pb-4 pt-6 cursor-pointer text-center bg-blue text-white"
                                    >
                                        {{ $t("Action") }}
                                    </th>
                                </tr>
                                <tr
                                    v-for="(item, index) in ablageregeln"
                                    :key="'table-3-' + index"
                                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                                >
                                    <td class="border-t p-2">
                                        {{ item.wennFeld }}
                                    </td>
                                    <td class="border-t p-2">
                                        {{ item.wert1 }}
                                    </td>
                                    <td class="border-t p-2">
                                        {{ item.verwendetesTemplate }}
                                    </td>
                                    <td class="border-t p-2">
                                        {{ item.feld }}
                                    </td>
                                    <td class="border-t p-2">
                                        {{ item.wert2 }}
                                    </td>
                                    <td class="border-t p-2">
                                        {{ item.subpfad }}
                                    </td>
                                    <td
                                        style="padding: 1.2rem"
                                        class="border-t p-2"
                                    >
                                        <button
                                            class="mr-2"
                                            @click.prevent="
                                                editAblageregeln(index)
                                            "
                                        >
                                            <font-awesome-icon
                                                icon="fa-regular fa-pen-to-square"
                                            ></font-awesome-icon>
                                        </button>
                                        <button
                                            @click.prevent="
                                                deleteAblageregeln(index)
                                            "
                                        >
                                            <font-awesome-icon
                                                icon="fa-regular fa-trash-can"
                                            ></font-awesome-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr
                                    class="flex p-5"
                                    v-if="!ablageregeln.length"
                                >
                                    {{
                                        $t("No Items Exist")
                                    }}
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <br />
            <br />
        </div>
        <div class="flex justify-center" v-else>
            <img
                style="height: 400px; width: 500px"
                class="w-full"
                :src="underConstructionImage"
                alt="under-construction"
            />
        </div>
    </div>
    <Modal :title="actionType" v-if="inSAPErzeugteDokumenteToggle">
        <template #body>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <text-input
                    v-model="inSAPErzeugteDokumenteForm.sapSystem"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('SAP System')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="inSAPErzeugteDokumenteForm.contentRepository"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Content Repository')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="inSAPErzeugteDokumenteForm.objekttyp"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Objekttyp')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="inSAPErzeugteDokumenteForm.beschreibung"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Beschreibung')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="inSAPErzeugteDokumenteForm.dokumentenart"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Dokumentenart')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="inSAPErzeugteDokumenteForm.dokumenttyp"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Dokumenttyp')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="inSAPErzeugteDokumenteForm.verknüpfungstabelle"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Verknüpfungstabelle')"
                    placeholder=" "
                    :floatingLabel="true"
                />
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="cursor-pointer secondary-btn"
                @click="
                    actionType === 'Add'
                        ? saveInSAPErzeugteDokumente()
                        : updateInSAPErzeugteDokumente()
                "
            >
                {{ $t(actionType) }}
            </button>
            <button
                @click="onCancelInSAPErzeugteDokumente"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
    <Modal :title="actionType" v-if="externEingehendeDokumenteToggle">
        <template #body>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <text-input
                    v-model="externEingehendeDokumenteForm.sapSystem"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('SAP System')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="externEingehendeDokumenteForm.contentRepository"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Content Repository')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="externEingehendeDokumenteForm.objekttyp"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Objekttyp')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="externEingehendeDokumenteForm.beschreibung"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Beschreibung')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="externEingehendeDokumenteForm.dokumentenart"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Dokumentenart')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="externEingehendeDokumenteForm.dokumenttyp"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Dokumenttyp')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="externEingehendeDokumenteForm.verknüpfungstabelle"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Verknüpfungstabelle')"
                    placeholder=" "
                    :floatingLabel="true"
                />
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="cursor-pointer secondary-btn"
                @click="
                    actionType === 'Add'
                        ? saveExternEingehendeDokumente()
                        : updateExternEingehendeDokumente()
                "
            >
                {{ $t(actionType) }}
            </button>
            <button
                @click="onCancelExternEingehendeDokumenteForm"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
    <Modal :title="actionType" v-if="ablageregelnToggle">
        <template #body>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <text-input
                    v-model="ablageregelnForm.wennFeld"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Wenn Feld')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="ablageregelnForm.wert1"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Wert')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="ablageregelnForm.verwendetesTemplate"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Verwendetes Template')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="ablageregelnForm.feld"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Feld')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="ablageregelnForm.wert2"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Wert')"
                    placeholder=" "
                    :floatingLabel="true"
                />
                <text-input
                    v-model="ablageregelnForm.subpfad"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Subpfad')"
                    placeholder=" "
                    :floatingLabel="true"
                />
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="cursor-pointer secondary-btn"
                @click="
                    actionType === 'Add'
                        ? saveAblageregeln()
                        : updateAblageregeln()
                "
            >
                {{ $t(actionType) }}
            </button>
            <button
                @click="onCancelAblageregeln"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
    <SmartlinkConfig
        :sapSystemID="sapSystemID"
        :client="client"
        :host="host"
        :systemNr="systemNr"
        :user="user"
        :password="password"
        @configJsonModified="configJsonModified = $event"
    />
</template>

<script>
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import TextInput from "../../Components/TextInput.vue";
import { mapGetters } from "vuex";
import UnderConstruction from "@/assets/images/under-construction.webp";
import WorkshopTemplate from "@/assets/images/workshop-template.png";
import Modal from "../../Components/EditModal.vue";
import IndexDownloadConfig from "./SAPTemplate/IndexDownloadConfig.vue";
import SmartlinkConfig from "./SAPTemplate/SmartlinkConfig.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    name: "WorkshopTemplatesEdit",
    components: {
        SmartlinkConfig,
        MultiSelectInput,
        Modal,
        TextInput,
        IndexDownloadConfig,
        PageHeader
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Workshop Templates",
                    to: "/workshop-templates?page="+this.$route.query.page,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            configJsonModified: null,
            inSAPErzeugteDokumenteToggle: false,
            externEingehendeDokumenteToggle: false,
            ablageregelnToggle: false,
            inSAPErzeugteDokumenteForm: {},
            externEingehendeDokumenteForm: {},
            ablageregelnForm: {},
            inSAPErzeugteDokumente: [],
            externEingehendeDokumente: [],
            ablageregeln: [],
            shouldShow: true,
            form: {
                customer: "",
                system: "",
                createDate: new Date(),
                consultant: "",
            },
            actionType: "Add",
            updateIndex: null,
            sapSystemID: "",
            client: "",
            host: "",
            systemNr: "",
            user: "",
            password: "",
        };
    },
    async mounted() {
        try {
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
            await this.$store.dispatch("systems/list", {
                perPage: 25,
                page: 1,
                type: "premise",
            });
            this.shouldShow = false;
            await this.$store.dispatch("userProfile/list");
            this.form.consultant = this.userProfiles.data.find(
                (user) => user.userId == this.authUser?.id
            );
            this.form.customer = this.companies?.data?.[0] ?? "";
            this.form.system = this.premiseSystems?.data?.[0] ?? "";
        } catch (e) {
            console.log(e);
        } finally {
            this.shouldShow = true;
        }
    },
    methods: {
        async generate() {
            await this.$nextTick();
            try {
                let fileContents = JSON.stringify(
                    IndexDownloadConfig.data().configJson
                );
                let fileName = "indexdownload.config.json";
                let blob = new Blob([fileContents], {
                    type: "text/plain",
                });
                let url = URL.createObjectURL(blob);
                let link = document.createElement("a");
                link.href = url;
                link.download = fileName;
                link.click();
                URL.revokeObjectURL(url);
                fileContents = JSON.stringify(this.configJsonModified);
                fileName = "smartlink.config.json";
                blob = new Blob([fileContents], {
                    type: "text/plain",
                });
                url = URL.createObjectURL(blob);
                link = document.createElement("a");
                link.href = url;
                link.download = fileName;
                link.click();
                URL.revokeObjectURL(url);
            } catch (e) {
                console.log(e);
            }
        },
        editInSAPErzeugteDokumente(index) {
            this.updateIndex = index;
            this.inSAPErzeugteDokumenteForm = {
                ...this.inSAPErzeugteDokumente[index],
            };
            this.actionType = "Edit";
            this.inSAPErzeugteDokumenteToggle = true;
        },
        updateInSAPErzeugteDokumente() {
            this.inSAPErzeugteDokumente[this.updateIndex] = {
                ...this.inSAPErzeugteDokumenteForm,
            };
            this.actionType = "Add";
            this.onCancelInSAPErzeugteDokumente();
        },
        deleteInSAPErzeugteDokumente(index) {
            this.inSAPErzeugteDokumente.splice(index, 1);
        },
        saveInSAPErzeugteDokumente() {
            this.inSAPErzeugteDokumente.push({
                ...this.inSAPErzeugteDokumenteForm,
            });
            this.onCancelInSAPErzeugteDokumente();
        },
        onCancelInSAPErzeugteDokumente() {
            this.inSAPErzeugteDokumenteForm = {};
            this.inSAPErzeugteDokumenteToggle = false;
            this.updateIndex = null;
        },
        editExternEingehendeDokumente(index) {
            this.updateIndex = index;
            this.externEingehendeDokumenteForm = {
                ...this.externEingehendeDokumente[index],
            };
            this.actionType = "Edit";
            this.externEingehendeDokumenteToggle = true;
        },
        updateExternEingehendeDokumente() {
            this.externEingehendeDokumente[this.updateIndex] = {
                ...this.externEingehendeDokumenteForm,
            };
            this.actionType = "Add";
            this.onCancelExternEingehendeDokumente();
        },
        deleteExternEingehendeDokumente(index) {
            this.externEingehendeDokumente.splice(index, 1);
        },
        saveExternEingehendeDokumente() {
            this.externEingehendeDokumente.push({
                ...this.externEingehendeDokumenteForm,
            });
            this.onCancelExternEingehendeDokumente();
        },
        onCancelExternEingehendeDokumente() {
            this.externEingehendeDokumenteForm = {};
            this.externEingehendeDokumenteToggle = false;
            this.updateIndex = null;
        },
        editAblageregeln(index) {
            this.updateIndex = index;
            this.ablageregelnForm = {
                ...this.ablageregeln[index],
            };
            this.actionType = "Edit";
            this.ablageregelnToggle = true;
        },
        updateAblageregeln() {
            this.ablageregeln[this.updateIndex] = {
                ...this.ablageregelnForm,
            };
            this.actionType = "Add";
            this.onCancelAblageregeln();
        },
        deleteAblageregeln(index) {
            this.ablageregeln.splice(index, 1);
        },
        saveAblageregeln() {
            this.ablageregeln.push({
                ...this.ablageregelnForm,
            });
            this.onCancelAblageregeln();
        },
        onCancelAblageregeln() {
            this.ablageregelnForm = {};
            this.ablageregelnToggle = false;
            this.updateIndex = null;
        },
    },
    computed: {
        underConstructionImage() {
            return UnderConstruction;
        },
        workshopTemplateImage() {
            return WorkshopTemplate;
        },
        ...mapGetters(["errors"]),
        ...mapGetters("companies", ["companies"]),
        ...mapGetters("systems", ["premiseSystems"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("auth", {
            authUser: "user",
        }),
        dropdownStyles() {
            return {
                "--elem-hover-bg-color": "#312E81",
                "--elem-selected-bg-color": "#312E81",
            };
        },
    },
};
</script>

<style lang="scss" scoped>
.color-blue {
    color: #2957a4;
}
.bg-blue {
    background-color: #2957a4;
}

th,
td {
    border: 1px solid black;
}

.tabs {
    overflow: hidden;
}

.tab {
    width: 100%;
    color: black;
    overflow: hidden;
    &-label {
        display: flex;
        justify-content: space-between;
        cursor: pointer;
    }
    &-content {
        display: none;
        max-height: 0;
        color: black;
        transition: all 0.35s;
    }
    &-close {
        display: flex;
        justify-content: flex-end;
        font-size: 0.75em;
        cursor: pointer;
        &:hover {
        }
    }
}
.styles-configurator-tab-label::after {
    margin-top: 0px !important;
}

// :checked
input:checked {
    ~ .tab-content {
        display: block;
        max-height: initial;
    }
}

.inner-accordion-label::after {
    transform: rotate(90deg);
    transform-origin: center;
}

input[class="tab-checkbox"] {
    position: absolute;
    opacity: 0;
    z-index: -1;
}

.inner-accordion {
    display: none;
}
</style>
