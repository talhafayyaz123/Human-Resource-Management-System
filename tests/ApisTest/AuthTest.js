// const { spec } = require("pactum");


// let authToken = '';

// it("should get token response code 200", async () => {
//     const loginRes = await spec().
//         post(`${process.env.VITE_AUTH_SERVICE_URL}/login`)
//         .withBody({
//             mail: "admin@docshero.de",
//             password: "12345",
//             remember: false,
//         }).expectStatus(200);
    
//     authToken = loginRes.body.token
//     getToken()
// });
// function getToken() {
//     const accessToken = authToken;
//     return accessToken;
// }

// module.exports = getToken;