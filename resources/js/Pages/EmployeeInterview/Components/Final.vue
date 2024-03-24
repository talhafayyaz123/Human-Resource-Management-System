<template>
    <div class="flex flex-wrap">
        <div class="pb-8 pr-6 w-full lg:w-1/2">
            <h3>{{ $t("Personal Skills") }}</h3>
        </div>
        <div class="pb-8 pr-6 w-full lg:w-1/2"></div>

        <div class="pb-8 pr-6 w-full lg:w-1/2 flex gap-[100px]">
            <div class="w-full">
                <div class="form-group">
                    <label class="form-label flex gap-2"
                        ><span
                            >{{
                                $t("Motivation & Willingsness to learn")
                            }}:</span
                        >
                        <span
                            ><ToolTip :text="'ToolTip GuideLines'">
                                <Icon :name="'iIcon'"
                            /></ToolTip>
                        </span>
                    </label>
                    <div class="rate">
                        <input
                            type="radio"
                            id="motivationStar5"
                            value="5"
                            v-model="form.finalPersonalSkills.motivationRating"
                        />
                        <label for="motivationStar5" title="text"
                            >5 stars</label
                        >
                        <input
                            type="radio"
                            id="motivationStar4"
                            v-model="form.finalPersonalSkills.motivationRating"
                            value="4"
                        />
                        <label for="motivationStar4" title="text"
                            >4 stars</label
                        >
                        <input
                            type="radio"
                            id="motivationStar3"
                            v-model="form.finalPersonalSkills.motivationRating"
                            value="3"
                        />
                        <label for="motivationStar3" title="text"
                            >3 stars</label
                        >
                        <input
                            type="radio"
                            id="motivationStar2"
                            v-model="form.finalPersonalSkills.motivationRating"
                            value="2"
                        />
                        <label for="motivationStar2" title="text"
                            >2 stars</label
                        >
                        <input
                            type="radio"
                            id="motivationStar1"
                            v-model="form.finalPersonalSkills.motivationRating"
                            value="1"
                        />
                        <label for="motivationStar1" title="text">1 star</label>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <label class="form-label flex gap-2"
                        ><span>{{ $t("Responsibility & Initiative") }}: </span>
                        <span
                            ><ToolTip :text="'ToolTip GuideLines'">
                                <Icon :name="'iIcon'"
                            /></ToolTip>
                        </span>
                    </label>
                    <div class="rate">
                        <input
                            type="radio"
                            id="responsibilityStar5"
                            value="5"
                            v-model="
                                form.finalPersonalSkills.responsibilityRating
                            "
                        />
                        <label for="responsibilityStar5" title="text"
                            >5 stars</label
                        >
                        <input
                            type="radio"
                            id="responsibilityStar4"
                            v-model="
                                form.finalPersonalSkills.responsibilityRating
                            "
                            value="4"
                        />
                        <label for="responsibilityStar4" title="text"
                            >4 stars</label
                        >
                        <input
                            type="radio"
                            id="responsibilityStar3"
                            v-model="
                                form.finalPersonalSkills.responsibilityRating
                            "
                            value="3"
                        />
                        <label for="responsibilityStar3" title="text"
                            >3 stars</label
                        >
                        <input
                            type="radio"
                            id="responsibilityStar2"
                            v-model="
                                form.finalPersonalSkills.responsibilityRating
                            "
                            value="2"
                        />
                        <label for="responsibilityStar2" title="text"
                            >2 stars</label
                        >
                        <input
                            type="radio"
                            id="responsibilityStar1"
                            v-model="
                                form.finalPersonalSkills.responsibilityRating
                            "
                            value="1"
                        />
                        <label for="responsibilityStar1" title="text"
                            >1 star</label
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group pb-8 pr-6 w-full lg:w-1/2">
            <text-area-input
                v-model="form.finalPersonalSkills.summary"
                class="pb-8"
                :label="$t('Personal Skills Summary')"
            />
        </div>

        <div class="pb-8 pr-6 w-full lg:w-1/2">
            <h3>{{ $t("Company Values") }}</h3>
        </div>
        <div class="pb-8 pr-6 w-full lg:w-1/2"></div>

        <div class="pb-8 pr-6 w-full lg:w-1/2 flex gap-[50px]">
            <div class="w-full">
                <div class="form-group">
                    <label class="form-label flex gap-2"
                        ><span>{{ $t("Innovation & Agility") }}: </span>
                        <span
                            ><ToolTip :text="'ToolTip GuideLines'">
                                <Icon :name="'iIcon'"
                            /></ToolTip>
                        </span>
                    </label>
                    <div class="rate">
                        <input
                            type="radio"
                            id="innovationStar5"
                            value="5"
                            v-model="form.finalCompanyValues.innovationRating"
                        />
                        <label for="innovationStar5" title="text"
                            >5 stars</label
                        >
                        <input
                            type="radio"
                            id="innovationStar4"
                            v-model="form.finalCompanyValues.innovationRating"
                            value="4"
                        />
                        <label for="innovationStar4" title="text"
                            >4 stars</label
                        >
                        <input
                            type="radio"
                            id="innovationStar3"
                            v-model="form.finalCompanyValues.innovationRating"
                            value="3"
                        />
                        <label for="innovationStar3" title="text"
                            >3 stars</label
                        >
                        <input
                            type="radio"
                            id="innovationStar2"
                            v-model="form.finalCompanyValues.innovationRating"
                            value="2"
                        />
                        <label for="innovationStar2" title="text"
                            >2 stars</label
                        >
                        <input
                            type="radio"
                            id="innovationStar1"
                            v-model="form.finalCompanyValues.innovationRating"
                            value="1"
                        />
                        <label for="innovationStar1" title="text">1 star</label>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <label class="form-label flex gap-2"
                        ><span>{{ $t("Customer Focus") }}: </span>
                        <span
                            ><ToolTip :text="'ToolTip GuideLines'">
                                <Icon :name="'iIcon'"
                            /></ToolTip>
                        </span>
                    </label>
                    <div class="rate">
                        <input
                            type="radio"
                            id="customerFocusStar5"
                            value="5"
                            v-model="
                                form.finalCompanyValues.customerFocusRating
                            "
                        />
                        <label for="customerFocusStar5" title="text"
                            >5 stars</label
                        >
                        <input
                            type="radio"
                            id="customerFocusStar4"
                            v-model="
                                form.finalCompanyValues.customerFocusRating
                            "
                            value="4"
                        />
                        <label for="customerFocusStar4" title="text"
                            >4 stars</label
                        >
                        <input
                            type="radio"
                            id="customerFocusStar3"
                            v-model="
                                form.finalCompanyValues.customerFocusRating
                            "
                            value="3"
                        />
                        <label for="customerFocusStar3" title="text"
                            >3 stars</label
                        >
                        <input
                            type="radio"
                            id="customerFocusStar2"
                            v-model="
                                form.finalCompanyValues.customerFocusRating
                            "
                            value="2"
                        />
                        <label for="customerFocusStar2" title="text"
                            >2 stars</label
                        >
                        <input
                            type="radio"
                            id="customerFocusStar1"
                            v-model="
                                form.finalCompanyValues.customerFocusRating
                            "
                            value="1"
                        />
                        <label for="customerFocusStar1" title="text"
                            >1 star</label
                        >
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <label class="form-label flex gap-2"
                        ><span>{{ $t("Open for new solutions") }}:</span>
                        <span
                            ><ToolTip :text="'ToolTip GuideLines'">
                                <Icon :name="'iIcon'"
                            /></ToolTip>
                        </span>
                    </label>
                    <div class="rate">
                        <input
                            type="radio"
                            id="openSolutionsStar5"
                            value="5"
                            v-model="
                                form.finalCompanyValues.openSolutionsRating
                            "
                        />
                        <label for="openSolutionsStar5" title="text"
                            >5 stars</label
                        >
                        <input
                            type="radio"
                            id="openSolutionsStar4"
                            v-model="
                                form.finalCompanyValues.openSolutionsRating
                            "
                            value="4"
                        />
                        <label for="openSolutionsStar4" title="text"
                            >4 stars</label
                        >
                        <input
                            type="radio"
                            id="openSolutionsStar3"
                            v-model="
                                form.finalCompanyValues.openSolutionsRating
                            "
                            value="3"
                        />
                        <label for="openSolutionsStar3" title="text"
                            >3 stars</label
                        >
                        <input
                            type="radio"
                            id="openSolutionsStar2"
                            v-model="
                                form.finalCompanyValues.openSolutionsRating
                            "
                            value="2"
                        />
                        <label for="openSolutionsStar2" title="text"
                            >2 stars</label
                        >
                        <input
                            type="radio"
                            id="openSolutionsStar1"
                            v-model="
                                form.finalCompanyValues.openSolutionsRating
                            "
                            value="1"
                        />
                        <label for="openSolutionsStar1" title="text"
                            >1 star</label
                        >
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group pb-8 pr-6 w-full lg:w-1/2">
            <text-area-input
                v-model="form.finalCompanyValues.summary"
                class="pb-8"
                :label="$t('Company Values Summary')"
            />
        </div>

        <div class="pb-8 pr-6 w-full lg:w-1/2">
            <h3>{{ $t("Social Competencies") }}</h3>
        </div>
        <div class="pb-8 pr-6 w-full lg:w-1/2"></div>

        <div class="pb-8 pr-6 w-full lg:w-1/2 flex gap-[50px]">
            <div class="w-full">
                <div class="form-group">
                    <label class="form-label flex gap-2"
                        ><span>{{ $t("Communication Skills") }}: </span>
                        <span
                            ><ToolTip :text="'ToolTip GuideLines'">
                                <Icon :name="'iIcon'"
                            /></ToolTip>
                        </span>
                    </label>
                    <div class="rate">
                        <input
                            type="radio"
                            id="communicationStar5"
                            value="5"
                            v-model="
                                form.finalSocialCompetence.communicationRating
                            "
                        />
                        <label for="communicationStar5" title="text"
                            >5 stars</label
                        >
                        <input
                            type="radio"
                            id="communicationStar4"
                            v-model="
                                form.finalSocialCompetence.communicationRating
                            "
                            value="4"
                        />
                        <label for="communicationStar4" title="text"
                            >4 stars</label
                        >
                        <input
                            type="radio"
                            id="communicationStar3"
                            v-model="
                                form.finalSocialCompetence.communicationRating
                            "
                            value="3"
                        />
                        <label for="communicationStar3" title="text"
                            >3 stars</label
                        >
                        <input
                            type="radio"
                            id="communicationStar2"
                            v-model="
                                form.finalSocialCompetence.communicationRating
                            "
                            value="2"
                        />
                        <label for="communicationStar2" title="text"
                            >2 stars</label
                        >
                        <input
                            type="radio"
                            id="communicationStar1"
                            v-model="
                                form.finalSocialCompetence.communicationRating
                            "
                            value="1"
                        />
                        <label for="communicationStar1" title="text"
                            >1 star</label
                        >
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <label class="form-label flex gap-2"
                        ><span>{{ $t("Teamwork and Cooperation") }}: </span>
                        <span
                            ><ToolTip :text="'ToolTip GuideLines'">
                                <Icon :name="'iIcon'"
                            /></ToolTip>
                        </span>
                    </label>
                    <div class="rate">
                        <input
                            type="radio"
                            id="teamworkStar5"
                            value="5"
                            v-model="form.finalSocialCompetence.teamworkRating"
                        />
                        <label for="teamworkStar5" title="text">5 stars</label>
                        <input
                            type="radio"
                            id="teamworkStar4"
                            v-model="form.finalSocialCompetence.teamworkRating"
                            value="4"
                        />
                        <label for="teamworkStar4" title="text">4 stars</label>
                        <input
                            type="radio"
                            id="teamworkStar3"
                            v-model="form.finalSocialCompetence.teamworkRating"
                            value="3"
                        />
                        <label for="teamworkStar3" title="text">3 stars</label>
                        <input
                            type="radio"
                            id="teamworkStar2"
                            v-model="form.finalSocialCompetence.teamworkRating"
                            value="2"
                        />
                        <label for="teamworkStar2" title="text">2 stars</label>
                        <input
                            type="radio"
                            id="teamworkStar1"
                            v-model="form.finalSocialCompetence.teamworkRating"
                            value="1"
                        />
                        <label for="teamworkStar1" title="text">1 star</label>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <label class="form-label flex gap-2"
                        ><span>{{ $t("Error culture") }}: </span>
                        <span
                            ><ToolTip :text="'ToolTip GuideLines'">
                                <Icon :name="'iIcon'"
                            /></ToolTip>
                        </span>
                    </label>
                    <div class="rate">
                        <input
                            type="radio"
                            id="errorCultureStar5"
                            value="5"
                            v-model="
                                form.finalSocialCompetence.errorCultureRating
                            "
                        />
                        <label for="errorCultureStar5" title="text"
                            >5 stars</label
                        >
                        <input
                            type="radio"
                            id="errorCultureStar4"
                            v-model="
                                form.finalSocialCompetence.errorCultureRating
                            "
                            value="4"
                        />
                        <label for="errorCultureStar4" title="text"
                            >4 stars</label
                        >
                        <input
                            type="radio"
                            id="errorCultureStar3"
                            v-model="
                                form.finalSocialCompetence.errorCultureRating
                            "
                            value="3"
                        />
                        <label for="errorCultureStar3" title="text"
                            >3 stars</label
                        >
                        <input
                            type="radio"
                            id="errorCultureStar2"
                            v-model="
                                form.finalSocialCompetence.errorCultureRating
                            "
                            value="2"
                        />
                        <label for="errorCultureStar2" title="text"
                            >2 stars</label
                        >
                        <input
                            type="radio"
                            id="errorCultureStar1"
                            v-model="
                                form.finalSocialCompetence.errorCultureRating
                            "
                            value="1"
                        />
                        <label for="errorCultureStar1" title="text"
                            >1 star</label
                        >
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group pb-8 pr-6 w-full lg:w-1/2">
            <text-area-input
                v-model="form.finalSocialCompetence.summary"
                class="pb-8"
                :label="$t('Social Competence Summary')"
            />
        </div>

        <div class="pb-8 pr-6 w-full lg:w-1/2">
            <h3>
                {{ $t("Professional & Technical abilities") }}
            </h3>
        </div>
        <div class="pb-8 pr-6 w-full lg:w-1/2"></div>

        <div class="pb-8 pr-6 w-full lg:w-1/2 flex gap-[50px]">
            <div class="flex flex-wrap">
                <div class="pr-6 w-full flex gap-[50px]">
                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label flex gap-2"
                                ><span>{{ $t("Tools") }}:</span>
                                <span
                                    ><ToolTip :text="'ToolTip GuideLines'">
                                        <Icon :name="'iIcon'"
                                    /></ToolTip>
                                </span>
                            </label>
                            <div class="rate">
                                <input
                                    type="radio"
                                    id="professionalTechnicalStar5"
                                    value="5"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .toolsRating
                                    "
                                />
                                <label
                                    for="professionalTechnicalStar5"
                                    title="text"
                                    >5 stars</label
                                >
                                <input
                                    type="radio"
                                    id="professionalTechnicalStar4"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .toolsRating
                                    "
                                    value="4"
                                />
                                <label
                                    for="professionalTechnicalStar4"
                                    title="text"
                                    >4 stars</label
                                >
                                <input
                                    type="radio"
                                    id="professionalTechnicalStar3"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .toolsRating
                                    "
                                    value="3"
                                />
                                <label
                                    for="professionalTechnicalStar3"
                                    title="text"
                                    >3 stars</label
                                >
                                <input
                                    type="radio"
                                    id="professionalTechnicalStar2"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .toolsRating
                                    "
                                    value="2"
                                />
                                <label
                                    for="professionalTechnicalStar2"
                                    title="text"
                                    >2 stars</label
                                >
                                <input
                                    type="radio"
                                    id="professionalTechnicalStar1"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .toolsRating
                                    "
                                    value="1"
                                />
                                <label
                                    for="professionalTechnicalStar1"
                                    title="text"
                                    >1 star</label
                                >
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label flex gap-2"
                                ><span>{{ $t("Quality of Work") }}: </span>
                                <span
                                    ><ToolTip :text="'ToolTip GuideLines'">
                                        <Icon :name="'iIcon'"
                                    /></ToolTip>
                                </span>
                            </label>
                            <div class="rate">
                                <input
                                    type="radio"
                                    id="qualityStar5"
                                    value="5"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .qualityRating
                                    "
                                />
                                <label for="qualityStar5" title="text"
                                    >5 stars</label
                                >
                                <input
                                    type="radio"
                                    id="qualityStar4"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .qualityRating
                                    "
                                    value="4"
                                />
                                <label for="qualityStar4" title="text"
                                    >4 stars</label
                                >
                                <input
                                    type="radio"
                                    id="qualityStar3"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .qualityRating
                                    "
                                    value="3"
                                />
                                <label for="qualityStar3" title="text"
                                    >3 stars</label
                                >
                                <input
                                    type="radio"
                                    id="qualityStar2"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .qualityRating
                                    "
                                    value="2"
                                />
                                <label for="qualityStar2" title="text"
                                    >2 stars</label
                                >
                                <input
                                    type="radio"
                                    id="qualityStar1"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .qualityRating
                                    "
                                    value="1"
                                />
                                <label for="qualityStar1" title="text"
                                    >1 star</label
                                >
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label flex gap-2"
                                ><span>{{ $t("Work-Efficieny") }}: </span>
                                <span
                                    ><ToolTip :text="'ToolTip GuideLines'">
                                        <Icon :name="'iIcon'"
                                    /></ToolTip>
                                </span>
                            </label>
                            <div class="rate">
                                <input
                                    type="radio"
                                    id="efficiencyStar5"
                                    value="5"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .efficiencyRating
                                    "
                                />
                                <label for="efficiencyStar5" title="text"
                                    >5 stars</label
                                >
                                <input
                                    type="radio"
                                    id="efficiencyStar4"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .efficiencyRating
                                    "
                                    value="4"
                                />
                                <label for="efficiencyStar4" title="text"
                                    >4 stars</label
                                >
                                <input
                                    type="radio"
                                    id="efficiencyStar3"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .efficiencyRating
                                    "
                                    value="3"
                                />
                                <label for="efficiencyStar3" title="text"
                                    >3 stars</label
                                >
                                <input
                                    type="radio"
                                    id="efficiencyStar2"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .efficiencyRating
                                    "
                                    value="2"
                                />
                                <label for="efficiencyStar2" title="text"
                                    >2 stars</label
                                >
                                <input
                                    type="radio"
                                    id="efficiencyStar1"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .efficiencyRating
                                    "
                                    value="1"
                                />
                                <label for="efficiencyStar1" title="text"
                                    >1 star</label
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pr-6 w-full flex gap-[50px]">
                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label flex gap-2"
                                ><span
                                    >{{
                                        $t(
                                            " Completing (sub-)projects and tasks"
                                        )
                                    }}:</span
                                >

                                <span
                                    ><ToolTip :text="'ToolTip GuideLines'">
                                        <Icon :name="'iIcon'"
                                    /></ToolTip>
                                </span>
                            </label>
                            <div class="rate">
                                <input
                                    type="radio"
                                    id="completingProjectAndTaskStar5"
                                    value="5"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .completingProjectAndTask
                                    "
                                />
                                <label
                                    for="completingProjectAndTaskStar5"
                                    title="text"
                                    >5 stars</label
                                >
                                <input
                                    type="radio"
                                    id="completingProjectAndTaskStar4"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .completingProjectAndTask
                                    "
                                    value="4"
                                />
                                <label
                                    for="completingProjectAndTaskStar4"
                                    title="text"
                                    >4 stars</label
                                >
                                <input
                                    type="radio"
                                    id="completingProjectAndTaskStar3"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .completingProjectAndTask
                                    "
                                    value="3"
                                />
                                <label
                                    for="completingProjectAndTaskStar3"
                                    title="text"
                                    >3 stars</label
                                >
                                <input
                                    type="radio"
                                    id="completingProjectAndTaskStar2"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .completingProjectAndTask
                                    "
                                    value="2"
                                />
                                <label
                                    for="completingProjectAndTaskStar2"
                                    title="text"
                                    >2 stars</label
                                >
                                <input
                                    type="radio"
                                    id="completingProjectAndTaskStar1"
                                    v-model="
                                        form.finalProfessionalTechnicalAbilities
                                            .completingProjectAndTask
                                    "
                                    value="1"
                                />
                                <label
                                    for="completingProjectAndTaskStar1"
                                    title="text"
                                    >1 star</label
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group pb-8 pr-6 w-full lg:w-1/2">
            <text-area-input
                v-model="form.finalProfessionalTechnicalAbilities.summary"
                class="pb-8"
                :label="$t('Professional & Technical abilities')"
            />
        </div>

        <div class="form-group pb-8 pr-6 w-full lg:w-1/2">
            <text-area-input
                v-model="form.finalGoalAcheivement"
                class="pb-8"
                :label="$t('Goal Acheivement')"
            />
        </div>
        <div class="form-group pb-8 pr-6 w-full lg:w-1/2">
            <text-area-input
                v-model="form.finalOverallSummary"
                class="pb-8"
                :label="$t('Overall Summary')"
            />
        </div>
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import Icon from "@/Components/Icon.vue";
import ToolTip from "@/Components/ToolTip.vue";

export default {
    props: {
        recordId: {
            type: Number,
            required: false,
        },
        parentForm: { type: Object, default: {} },
        childForm: { type: Object, default: {} },
        activeTab : {
            type: String,
            default: ""
        }
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("suppliers", ["suppliers"]),
        ...mapGetters("auth", { user: "user", users: "users" }),
        ...mapGetters("employeeInterview", ["saveBotton"]),
    },
    components: {
        LoadingButton,
        TextInput,
        TextAreaInput,
        MultiSelectInput,
        NumberInput,
        PageHeader,
        Icon,
        MultiSelectInput,
        ToolTip,
    },
    mounted() {
        if (this.recordId && Object.keys(this.childForm).length > 0) {
            try {
                this.form.finalGoalAcheivement =
                    this.childForm.finalGoalAcheivement;
                this.form.finalOverallSummary =
                    this.childForm.finalOverallSummary;
                this.form.finalPersonalSkills =
                    this.childForm.finalPersonalSkills;
                this.form.finalCompanyValues =
                    this.childForm.finalCompanyValues;
                this.form.finalSocialCompetence =
                    this.childForm.finalSocialCompetence;
                this.form.finalProfessionalTechnicalAbilities =
                    this.childForm.finalProfessionalTechnicalAbilities;
            } catch (e) {
                console.log(e);
            }
        }
    },
    data() {
        return {
            form: {
                finalGoalAcheivement: "",
                finalOverallSummary: "",
                finalPersonalSkills: {
                    motivationRating: "",
                    responsibilityRating: "",
                    summary: "",
                },
                finalCompanyValues: {
                    innovationRating: "",
                    customerFocusRating: "",
                    openSolutionsRating: "",
                    summary: "",
                },
                finalSocialCompetence: {
                    communicationRating: "",
                    teamworkRating: "",
                    errorCultureRating: "",
                    summary: "",
                },
                finalProfessionalTechnicalAbilities: {
                    toolsRating: "",
                    qualityRating: "",
                    efficiencyRating: "",
                    completingProjectAndTask: "",
                    summary: "",
                },
            },
        };
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                if (this.recordId) {
                    await this.$store.dispatch("employeeInterview/update", {
                        id: this.recordId,
                        data: {
                            ...this.parentForm,
                            ...this.form,
                            interviewReason:
                                this.parentForm.interviewReason.value,
                            employeeId: this.parentForm?.employeeId?.id,
                        },
                    });
                } else {
                    await this.$store.dispatch("employeeInterview/create", {
                        ...this.form,
                        ...this.parentForm,
                        interviewReason: this.parentForm.interviewReason.value,
                        employeeId: this.parentForm?.employeeId?.id,
                    });
                }
                this.$router.push("/employee-interview");
            } catch (e) {
            } finally {
                this.$store.dispatch("employeeInterview/toggleSaveBotton", false);
                this.$store.commit("isLoading", false);
            }
        },
    },
     watch: {
        saveBotton(show) {
            if (show && this.activeTab === "final") {
                this.store()
            } 
        },
    },
};
</script>

<style scoped>
.rate {
    float: left;
    height: 46px;
    padding: 0 10px;
}
.rate:not(:checked) > input {
    position: absolute;
    opacity: 0;
}
.rate:not(:checked) > label {
    float: right;
    width: 1em;
    overflow: hidden;
    white-space: nowrap;
    cursor: pointer;
    font-size: 30px;
    color: #ccc;
}
.rate:not(:checked) > label:before {
    content: "★ ";
}
.rate > input:checked ~ label {
    color: #ffc700;
}
.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #deb217;
}
.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
}
</style>
