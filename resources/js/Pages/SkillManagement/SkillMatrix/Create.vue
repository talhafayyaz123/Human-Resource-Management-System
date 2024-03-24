<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="grid grid-cols-1 gap-4">
            <form @submit.prevent="store">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Fill Skill matrix Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid items-center grid-cols-2 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        v-model="form.name"
                                        :required="true"
                                        :error="errors.name"
                                        class=""
                                        :label="$t('Name')"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        class=""
                                        v-model="form.skillGroup"
                                        :options="skillGroup.data"
                                        :multiple="true"
                                        :textLabel="$t('Skill Group')"
                                        label="name"
                                        trackBy="id"
                                        :required="true"
                                        moduleName="skillGroup"
                                        @select="removeSkills"
                                        @remove="addSkills"
                                        :error="errors.skillGroup"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        :key="form.skills"
                                        class=""
                                        v-model="form.skills"
                                        :options="skillOptions"
                                        :multiple="true"
                                        :textLabel="$t('Skills')"
                                        :required="true"
                                        label="name"
                                        trackBy="id"
                                        moduleName="skills"
                                        :error="errors.skills"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        class=""
                                        v-model="form.teams"
                                        :options="teams?.data"
                                        :multiple="true"
                                        :textLabel="$t('Teams')"
                                        label="name"
                                        trackBy="id"
                                        :required="true"
                                        moduleName="teams"
                                        @select="removeTeams"
                                        @remove="addTeams"
                                        :error="errors.teams"
                                    />
                                </div>
                                
                            </div>
                            <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            v-model="form.teamMembers"
                                            :options="teamMembersOptions"
                                            class=""
                                            :multiple="true"
                                            :textLabel="$t('Team Members')"
                                            :required="true"
                                            label="employee"
                                            trackBy="id"
                                            moduleName="userProfile"
                                            :error="errors.teams"
                                        />
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <router-link to="/skill-matrix" class="primary-btn mr-3">
                        <span class="mr-1">
                            <CustomIcon name="cancelIcon" />
                        </span>
                        <span>{{ $t("Cancel") }}</span>
                    </router-link>
                    <loading-button
                        v-if="$can(`${$route.meta.permission}.create`)"
                        :loading="isLoading"
                        class="secondary-btn"
                    >
                        <span class="mr-1">
                            <CustomIcon name="saveIcon" />
                        </span>
                        {{ $t("Save and Proceed") }}
                    </loading-button>
                </div>
            </form>
        </div>
        <div
            v-if="!!form.skillGroup?.length && !!form.skillGroup?.length"
            class="bg-white rounded-md shadow margin-bottom-3rem"
        >
            <div class="flex justify-start">
                <button @click="preview()" class="secondary-btn">
                    {{ $t("Preview Matrix") }}
                </button>
            </div>
            <div v-if="previewMatrix" class="matrix p-4">
                <table class="matrix-table">
                    <thead>
                        <tr>
                            <th class="w-16"></th>
                            <th class="w-64 border-right"></th>
                            <th
                                v-for="(group, key) in matrixGroups"
                                class="border-right border-bottom"
                                :colspan="group?.skills?.length"
                            >
                                {{ group?.name }}
                                <table v-if="group.isGroup">
                                    <tr>
                                        <th
                                            v-for="skill in group?.skills"
                                            class="rotate-cur"
                                        >
                                            <div>
                                                <span>{{ skill.name }}</span>
                                            </div>
                                        </th>
                                    </tr>
                                </table>
                                <table v-else>
                                    <tr>
                                        <th class="rotate-cur">
                                            <div>
                                                <span>{{
                                                    group?.skillName
                                                }}</span>
                                            </div>
                                        </th>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </thead>
                    <tbody v-for="(team, index) in matrixTeams" :key="index">
                        <tr
                            v-if="team.isTeam && team.teamMembers.length > 0"
                            class="border-bottom border-top"
                        >
                            <td
                                class="team-mem"
                                :class="{
                                    'team-width': team.teamMembers.length === 1,
                                }"
                                :title="team.name"
                            >
                                <strong>{{ team.name }}</strong>
                            </td>
                        </tr>
                        <tr
                            v-if="team.isTeam && team.teamMembers.length > 0"
                            v-for="user in team?.teamMembers"
                            class=""
                        >
                            <td :title="team.name"></td>
                            <td
                                class="border-left border-right border-bottom border-top"
                            >
                                <div class="member">
                                    <img
                                        src="@/assets/images/user.png"
                                        alt=""
                                    />
                                    {{ user.employee }}
                                </div>
                            </td>
                            <td
                                v-for="group in matrixGroups"
                                :colspan="group?.skills?.length"
                                class="border-bottom border-right border-top"
                            >
                                <table>
                                    <tr>
                                        <td
                                            v-if="group.isGroup"
                                            v-for="skill in group?.skills"
                                        >
                                            <div
                                                v-if="user?.skills?.length > 0"
                                                class="main_cir"
                                            >
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            user.skills,
                                                            skill.id
                                                        ) == 1
                                                    "
                                                    src="@/assets/images/matrix/matrix1.png"
                                                    alt=""
                                                />
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            user.skills,
                                                            skill.id
                                                        ) == 2
                                                    "
                                                    src="@/assets/images/matrix/matrix2.png"
                                                    alt=""
                                                />
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            user.skills,
                                                            skill.id
                                                        ) == 3
                                                    "
                                                    src="@/assets/images/matrix/matrix3.png"
                                                    alt=""
                                                />
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            user.skills,
                                                            skill.id
                                                        ) == 4
                                                    "
                                                    src="@/assets/images/matrix/matrix4.png"
                                                    alt=""
                                                />
                                                <!--                                        <img v-if="!findSkillLevel(user.skills, skill)" src="@/assets/images/matrix/matrix.png" alt="">-->
                                                <div
                                                    class="select_cir"
                                                    v-if="
                                                        !findSkillLevel(
                                                            user.skills,
                                                            skill.id
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="cir_1 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_2 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_3 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_4 cir"
                                                    ></div>
                                                </div>
                                            </div>
                                            <div v-else class="main_cir">
                                                <div class="select_cir">
                                                    <div
                                                        class="cir_1 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_2 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_3 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_4 cir"
                                                    ></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td v-else>
                                            <div class="main_cir">
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            user.skills,
                                                            group.id,
                                                            direct
                                                        ) == 1
                                                    "
                                                    src="@/assets/images/matrix/matrix1.png"
                                                    alt=""
                                                />
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            user.skills,
                                                            group.id
                                                        ) == 2
                                                    "
                                                    src="@/assets/images/matrix/matrix2.png"
                                                    alt=""
                                                />
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            user.skills,
                                                            group.id
                                                        ) == 3
                                                    "
                                                    src="@/assets/images/matrix/matrix3.png"
                                                    alt=""
                                                />
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            user.skills,
                                                            group.id
                                                        ) == 4
                                                    "
                                                    src="@/assets/images/matrix/matrix4.png"
                                                    alt=""
                                                />
                                                <div
                                                    class="select_cir"
                                                    v-if="
                                                        !findSkillLevel(
                                                            user.skills,
                                                            group.id
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="cir_1 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_2 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_3 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_4 cir"
                                                    ></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr v-if="!team.isTeam">
                            <td
                                class="border-left border-right border-bottom border-top"
                                colspan="2"
                            >
                                <div class="member ml-auto">
                                    <img
                                        src="@/assets/images/user.png"
                                        alt=""
                                    />
                                    {{ team.employee }}
                                </div>
                            </td>
                            <td
                                v-for="group in matrixGroups"
                                :colspan="group?.skills?.length"
                                class="border-bottom border-right border-top"
                            >
                                <table>
                                    <tr>
                                        <td
                                            v-if="group.isGroup"
                                            v-for="skill in group?.skills"
                                        >
                                            <div
                                                v-if="team?.skills?.length > 0"
                                                class="main_cir"
                                            >
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            team.skills,
                                                            skill.id
                                                        ) == 1
                                                    "
                                                    src="@/assets/images/matrix/matrix1.png"
                                                    alt=""
                                                />
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            team.skills,
                                                            skill.id
                                                        ) == 2
                                                    "
                                                    src="@/assets/images/matrix/matrix2.png"
                                                    alt=""
                                                />
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            team.skills,
                                                            skill.id
                                                        ) == 3
                                                    "
                                                    src="@/assets/images/matrix/matrix3.png"
                                                    alt=""
                                                />
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            team.skills,
                                                            skill.id
                                                        ) == 4
                                                    "
                                                    src="@/assets/images/matrix/matrix4.png"
                                                    alt=""
                                                />
                                                <!--                                        <img v-if="!findSkillLevel(user.skills, skill)" src="@/assets/images/matrix/matrix.png" alt="">-->
                                                <div
                                                    class="select_cir"
                                                    v-if="
                                                        !findSkillLevel(
                                                            team.skills,
                                                            skill.id
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="cir_1 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_2 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_3 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_4 cir"
                                                    ></div>
                                                </div>
                                            </div>
                                            <div v-else class="main_cir">
                                                <div class="select_cir">
                                                    <div
                                                        class="cir_1 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_2 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_3 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_4 cir"
                                                    ></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td v-else>
                                            <div class="main_cir">
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            team.skills,
                                                            group.id,
                                                            direct
                                                        ) == 1
                                                    "
                                                    src="@/assets/images/matrix/matrix1.png"
                                                    alt=""
                                                />
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            team.skills,
                                                            group.id
                                                        ) == 2
                                                    "
                                                    src="@/assets/images/matrix/matrix2.png"
                                                    alt=""
                                                />
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            team.skills,
                                                            group.id
                                                        ) == 3
                                                    "
                                                    src="@/assets/images/matrix/matrix3.png"
                                                    alt=""
                                                />
                                                <img
                                                    v-if="
                                                        findSkillLevel(
                                                            team.skills,
                                                            group.id
                                                        ) == 4
                                                    "
                                                    src="@/assets/images/matrix/matrix4.png"
                                                    alt=""
                                                />
                                                <div
                                                    class="select_cir"
                                                    v-if="
                                                        !findSkillLevel(
                                                            team.skills,
                                                            group.id
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="cir_1 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_2 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_3 cir"
                                                    ></div>
                                                    <div
                                                        class="cir_4 cir"
                                                    ></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        QuillTextEditor,
        MultiSelectInput,
        TextInput,
        SelectInput,
        TextAreaInput,
        LoadingButton,
        PageHeader,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("skills", ["skills"]),
        ...mapGetters("skillGroup", ["skillGroup"]),
        ...mapGetters("userTeams", ["teams"]),
        ...mapGetters("userProfile", ["userProfiles"]),
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Skills Matrix",
                    to: "/skill-matrix",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            form: {
                name: "",
                skillGroup: [],
                teamMembers: [],
                teams: [],
                skills: [],
            },
            previewMatrix: false,
            rows: [],
            options: [],
            optionsTeams: [],
            skillOptions: [],
            teamMembersOptions: [],
            matrixGroups: [],
            matrixTeams: [],
        };
    },
    async mounted() {
        await this.refresh();
    },
    methods: {
        async refresh() {
            await this.$store.dispatch("skills/list").finally(() => {
                this.skillOptions = this.skills?.data;
            });
            await this.$store.dispatch("userProfile/list").finally(() => {
                this.teamMembersOptions = this.userProfiles?.data;
            });
            await this.$store.dispatch("userTeams/list");
            await this.$store.dispatch("skillGroup/list");
        },
        findSkillLevel(skills, skillId) {
            if (skills) {
                for (const skillGroup of skills) {
                    for (const subSkill of skillGroup) {
                        if (subSkill.skillId === skillId) {
                            return subSkill.skillLevel;
                        }
                    }
                }
            }
            return null;
        },

        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("skillsMatrix/create", {
                    ...this.form,
                    teams:
                        this.form?.teams.map((team) => {
                            return team.id;
                        }) ?? [],
                    teamMembers:
                        this.form?.teamMembers.map((member) => {
                            return member.id;
                        }) ?? [],
                    skillGroup:
                        this.form.skillGroup.map((group) => {
                            return group.id;
                        }) ?? [],
                    skills:
                        this.form.skills.map((skill) => {
                            return skill.id;
                        }) ?? [],
                });
                //await this.$store.dispatch("skillGroup/list");
                this.$router.push("/skill-matrix");
            } catch (e) {}
        },
        async preview() {
            this.matrixGroups = [];
            this.matrixTeams = [];
            this.previewMatrix = !this.previewMatrix;

            if (this.previewMatrix) {
                //groups view of the matrix array create
                this.matrixGroups.push(
                    ...this.form.skillGroup.map((group) => {
                        return {
                            id: group.id,
                            name: group.name,
                            skills: group.setOfSkills ?? [],
                            isGroup: true,
                        };
                    })
                );

                //individual skills add
                this.matrixGroups.push(
                    ...this.form.skills.map((skill) => {
                        return {
                            id: skill.id,
                            name: "",
                            skillName: skill.name,
                            level: skill.level,
                            isGroup: false,
                        };
                    })
                );

                //Teams view of the matrix array create
                this.matrixTeams.push(
                    ...this.form.teams.map((team) => {
                        return {
                            id: team.id,
                            name: team.name,
                            teamMembers: team.teamMembers ?? [],
                            isTeam: true,
                        };
                    })
                );

                //individual teamMembers add
                this.matrixTeams.push(
                    ...this.form.teamMembers.map((member) => {
                        return {
                            id: member.id,
                            name: "",
                            employee: member?.employee,
                            skills: member.skills ?? [],
                            isTeam: false,
                        };
                    })
                );
            }
        },

        async removeSkills(values) {
            values?.setOfSkills.map((row) => {
                this.skillOptions = this.skillOptions.filter(
                    (option) => option.id !== row.id
                );
            });
        },

        addSkills(removed) {
            let obj = [];
            let alreadyExistOrNot = [];
            removed?.setOfSkills.map((row) => {
                obj = this.skills?.data.filter(
                    (option) => option.id === row.id
                );
                if (!!obj) {
                    alreadyExistOrNot = this.skillOptions.filter(
                        (option) => option.id === obj[0].id
                    );
                    if (!alreadyExistOrNot.length)
                        this.skillOptions.push(...obj);
                }
            });
        },

        async removeTeams(team) {
            team?.teamMembers.map((row) => {
                this.teamMembersOptions = this.teamMembersOptions.filter(
                    (option) => option.id !== row.memberId
                );
            });
        },

        addTeams(removedTeam) {
            let obj = [];
            let alreadyExistOrNot = [];
            removedTeam?.teamMembers.map((row) => {
                obj = this.userProfiles?.data.filter(
                    (option) => option.id === row.memberId
                );
                if (!!obj) {
                    alreadyExistOrNot = this.teamMembersOptions.filter(
                        (option) => option.id === obj[0].id
                    );
                    if (!alreadyExistOrNot.length)
                        this.teamMembersOptions.push(...obj);
                }
            });
        },
    },
};
</script>
<style lang="scss" scoped>
.matrix {
    width: 100%;
    overflow: hidden;
    display: flex;
    justify-content: center;
    //border: 1px solid #ccc;

    .border-right {
        border-right: 1px solid #ccc;
    }

    .border-left {
        border-left: 1px solid #ccc;
    }

    .border-bottom {
        border-bottom: 1px solid #ccc;
    }

    .border-top {
        border-top: 1px solid #ccc;
    }

    .teams {
        display: flex;
        align-items: flex-start;
    }

    .rotate-cur {
        white-space: nowrap;
        width: 28px;
        height: 250px;
        text-align: left;
        padding-top: 5px;
        font-size: 13px;

        div {
            transform: rotate(270deg);
            width: 28px;
            margin: 0 auto;

            span {
                position: relative;
                left: -100px;
                display: block;
            }
        }
    }

    .matrix-table {
        border: 1px solid #ccc;

        thead {
            tr {
                th {
                    // border: 1px solid #ccc;
                    padding: 4px;
                }
            }
        }

        tbody {
            position: relative;

            .team-mem {
                position: absolute;
                left: 5px;
                top: 37%;
                transform: translate(0, -50%);
                rotate: -90deg;
            }
            .team-mem.team-width {
                width: 45px;
                top: 18%;
                left: 20px;
            }
            .member {
                display: flex;
                align-items: center;
                img {
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    object-fit: cover;
                    margin-right: 8px;
                }
            }

            tr {
                td {
                    // border: 1px solid #ccc;
                    padding: 4px;
                }
            }
        }
    }
}

.main_cir {
    .select_cir {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
        border: 1px solid #ccc;

        img {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }

        .cir {
            width: 50%;
            height: 50%;
            cursor: pointer;
            position: relative;
            z-index: 1;
        }
    }
}
td > :only-child:hover {
    text-overflow: ellipsis;
    white-space: nowrap;
    word-break: break-word;
}
</style>
