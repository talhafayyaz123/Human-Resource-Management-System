const axios = require("axios");

let token;

async function getToken() {
    if (!token) {
        const response = await axios.post(
            `https://h2972847.stratoserver.net/o/public/index.php/login`,
            {
                mail: "m.rupprecht@docshero.de",
                password: "12345",
                remember: false,
            }
        );
        token = response.data.token;
    }
    return token;
}

module.exports = { getToken };
