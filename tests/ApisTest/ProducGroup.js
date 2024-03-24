// const getToken = require('./AuthTest');
// const { spec } = require("pactum");

// it("should get product groups response code 200", async () => {
//     const accessToken = getToken();
//     await spec().
//         get('http://127.0.0.1:8000/api/groups')
//         .withBearerToken(accessToken)
//         .expectStatus(200)
//         .end(() => {
//             console.log('Request complete');
//         });
    
// });

// it("should create product group response code 200", async () => {
//     const accessToken = getToken();
//     await spec().
//       post('http://127.0.0.1:8000/api/groups')
//       .withBearerToken(accessToken)
//         .withJson({
//             name: 'Product Group'
//         })
//         .expectStatus(200)
//         .end(() => {
//             console.log('Request complete');
//         });
// });
