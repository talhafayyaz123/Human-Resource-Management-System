export default {
    methods: {
        /**
         * gets users by ids from arrayOfObjectsWithUserIds and sets the 'userProfilePictures' state
         * @param {arrayOfObjectsWithUserIds} array of objects that contain ids for which the profile pictures must be fetched
         */
        getUserProfilePictures(arrayOfObjectsWithUserIds, key) {
            const ticketUsers = [
                ...new Set(
                    (arrayOfObjectsWithUserIds ?? [])
                        .map((item) => item[key])
                        .filter(
                            (userId) =>
                                !Object.keys(this.userProfilePictures).includes(
                                    userId
                                )
                        )
                ),
            ];
            if (!!ticketUsers && (ticketUsers?.length ?? 0) > 0) {
                this.$store
                    .dispatch("auth/show", {
                        id: ticketUsers,
                    })
                    .then((res) => {
                        const userProfilePictures = {
                            ...this.userProfilePictures,
                        };
                        for (const user of res?.data || []) {
                            userProfilePictures[user.id] = user;
                        }
                        this.$store.commit(
                            "auth/userProfilePictures",
                            userProfilePictures
                        );
                    });
            }
        },
    },
};
