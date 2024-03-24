export default {
    methods: {
        /**
         * sets the profile_image on the user w.r.t to the file
         * @param {file} recieved file
         * @param {user} user for which profile picture is being updated
         * @returns
         */
        setProfilePicture(file, user) {
            return new Promise((resolve, reject) => {
                // convert to base64
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onerror = (err) => {
                    console.log(err);
                    reject(err);
                };
                reader.onloadend = async () => {
                    let base64 = reader.result;
                    // hit the auth update API to update the profile_image
                    this.$store
                        .dispatch("auth/update", {
                            ...user,
                            profile_image: base64,
                            types: Object.keys(user.types).filter(
                                (type) => user.types[type] == 1
                            ),
                            roles: user.roles,
                            set_company_id: user.company_id,
                            set_tenant_id: user.tenant_id,
                        })
                        .then(() => {
                            resolve(base64);
                        })
                        .catch((err) => reject(err));
                };
            });
        },
    },
};
