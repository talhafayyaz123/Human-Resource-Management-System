<template>
    <PageHeader :items="breadcrumbItems" />
    <div class="matrix">
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
                                        <span>{{ group?.skillName }}</span>
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
                        :class="{ 'team-width': team.teamMembers.length === 1 }"
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
                            <img src="@/assets/images/user.png" alt="" />
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
                                            <div class="cir_1 cir"></div>
                                            <div class="cir_2 cir"></div>
                                            <div class="cir_3 cir"></div>
                                            <div class="cir_4 cir"></div>
                                        </div>
                                        <div v-else class="main_cir">
                                            <div class="select_cir">
                                                <div class="cir_1 cir"></div>
                                                <div class="cir_2 cir"></div>
                                                <div class="cir_3 cir"></div>
                                                <div class="cir_4 cir"></div>
                                            </div>
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
                                            <div class="cir_1 cir"></div>
                                            <div class="cir_2 cir"></div>
                                            <div class="cir_3 cir"></div>
                                            <div class="cir_4 cir"></div>
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
                            <img src="@/assets/images/user.png" alt="" />
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
                                            <div class="cir_1 cir"></div>
                                            <div class="cir_2 cir"></div>
                                            <div class="cir_3 cir"></div>
                                            <div class="cir_4 cir"></div>
                                        </div>
                                        <div v-else class="main_cir">
                                            <div class="select_cir">
                                                <div class="cir_1 cir"></div>
                                                <div class="cir_2 cir"></div>
                                                <div class="cir_3 cir"></div>
                                                <div class="cir_4 cir"></div>
                                            </div>
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
                                            <div class="cir_1 cir"></div>
                                            <div class="cir_2 cir"></div>
                                            <div class="cir_3 cir"></div>
                                            <div class="cir_4 cir"></div>
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
</template>

<script>
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        PageHeader,
    },

    computed: {
        ...mapGetters("skillGroup", ["skillGroup"]),
        ...mapGetters("skillsMatrix", ["matrixData"]),
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
                    to: `/skill-matrix?page=${this.$route.query.page}`,
                },
                {
                    text: "Show",
                    active: true,
                },
            ],
            rows: [],
            matrixGroups: [],
            matrixTeams: [],
        };
    },
    async mounted() {
        await this.refresh();
    },
    methods: {
        async refresh() {
            this.$store.commit("showLoader", true);
            await this.$store.dispatch("skillGroup/list");
            await this.$store
                .dispatch("skillsMatrix/matrix", this.$route.params.id)
                .finally(() => {
                    this.preview();
                    this.$store.commit("showLoader", false);
                });
        },

        async preview() {
            this.matrixGroups = [];
            this.matrixTeams = [];

            //groups view of the matrix array create
            this.matrixGroups.push(
                ...this.matrixData.skillGroup.map((group) => {
                    return {
                        id: group.id,
                        name: group.name,
                        skills: group.group_skills ?? [],
                        isGroup: true,
                    };
                })
            );

            //individual skills add
            this.matrixGroups.push(
                ...this.matrixData.skills.map((skill) => {
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
                ...this.matrixData.teams.map((team) => {
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
                ...this.matrixData.teamMembers.map((member) => {
                    return {
                        id: member.id,
                        name: "",
                        employee: member?.employee,
                        skills: member.skills ?? [],
                        isTeam: false,
                    };
                })
            );
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
