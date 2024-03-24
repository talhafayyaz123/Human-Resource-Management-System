const surveyJson = {
    id: "187564a6-89ed-e715-0120-0aa743cca63a",
    name: "Survey 1",
    steps: [
        {
            id: "4c52c7d1-7dc6-4ce6-9548-be40294a6087",
            type: "question",
            value: {
                id: 27,
                title: "Q1",
                text: "",
                order: -1,
                description: "",
                productDetails: "",
                chapterId: null,
                chapterName: "",
                configuration: {
                    type: "single-select",
                    options: [],
                    conditions: [],
                },
            },
        },
    ],
    cart: {
        products: [],
        config: [
            {
                id: 19,
                title: "Open Modal",
                isExpertMode: true,
                route: null,
                functionName: "openModal",
                code: 'function openModal() {\r\n  let formText = `<style>\r\n  /* The Modal (background) */\r\n  .modal {\r\n    display: none; /* Hidden by default */\r\n    position: fixed; /* Stay in place */\r\n    z-index: 1; /* Sit on top */\r\n    left: 0;\r\n    top: 0;\r\n    width: 100%; /* Full width */\r\n    height: 100%; /* Full height */\r\n    overflow: auto; /* Enable scroll if needed */\r\n    background-color: rgb(0, 0, 0); /* Fallback color */\r\n    background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */\r\n    color: slategray;\r\n    font-family: sans-serif;\r\n  }\r\n\r\n  /* Modal Content/Box */\r\n  .modal-content {\r\n    border-radius: 15px;\r\n    background-color: #fefefe;\r\n    margin: 5% auto; /* 15% from the top and centered */\r\n    padding: 20px;\r\n    border: 1px solid #888;\r\n    width: 70%; /* Could be more or less, depending on screen size */\r\n  }\r\n\r\n  /* The Close Button */\r\n  .close {\r\n    color: #aaa;\r\n    float: right;\r\n    font-size: 28px;\r\n    font-weight: bold;\r\n    align-self: center;\r\n  }\r\n\r\n  .close:hover,\r\n  .close:focus {\r\n    color: black;\r\n    text-decoration: none;\r\n    cursor: pointer;\r\n  }\r\n\r\n  .modal-header {\r\n    display: flex;\r\n    justify-content: space-between;\r\n    border-bottom: 1px solid grey;\r\n    margin-bottom: 1rem;\r\n  }\r\n\r\n  .header-text {\r\n    font-size: 1.2rem;\r\n  }\r\n\r\n  .modal-form {\r\n    display: grid;\r\n    gap: 1rem;\r\n    grid-template-columns: repeat(auto-fit, 330px);\r\n  }\r\n\r\n  .form-field {\r\n    display: flex;\r\n    flex-direction: column;\r\n  }\r\n\r\n  .form-input {\r\n    margin-top: 0.5rem;\r\n    padding: 0.6rem;\r\n    appearance: none;\r\n    border: 1px solid lightgrey;\r\n    border-radius: 5px;\r\n  }\r\n\r\n  .form-label {\r\n    font-size: 0.9rem;\r\n  }\r\n\r\n  .required {\r\n    color: red;\r\n  }\r\n\r\n  .submit-btn {\r\n    cursor: pointer;\r\n    font-weight: bold;\r\n    padding: 0.7rem 2rem 0.7rem 2rem;\r\n    margin-top: 1rem;\r\n    background-color: rgb(41, 87, 164);\r\n    color: white;\r\n    border-radius: 5px;\r\n    border: none;\r\n  }\r\n\r\n  .submit-btn:hover {\r\n    background-color: rgb(237, 125, 49);\r\n  }\r\n\r\n  .error {\r\n    margin-top: 5px;\r\n    font-size: 0.9rem;\r\n    color: rgb(237, 80, 80);\r\n  }\r\n\r\n  .form-input-error {\r\n    border: 1px solid red !important;\r\n  }\r\n\r\n  .d-none {\r\n    display: none;\r\n  }\r\n</style>\r\n<!-- The Modal -->\r\n<div id="myModal" class="modal">\r\n  <!-- Modal content -->\r\n  <div class="modal-content">\r\n    <div class="modal-header">\r\n      <p class="header-text">Fill Form Details</p>\r\n      <span class="close">&times;</span>\r\n    </div>\r\n    <form id="main-form">\r\n      <div class="modal-form">\r\n        <div class="form-field">\r\n          <label class="form-label" for="name"\r\n            ><span class="required">*&nbsp;</span>Name:</label\r\n          >\r\n          <input\r\n            class="form-input"\r\n            name="name"\r\n            id="name"\r\n            type="text"\r\n          />\r\n          <p id="name-error" class="error d-none">Name is a required field!</p>\r\n        </div>\r\n        <div class="form-field">\r\n          <label class="form-label" for="type"\r\n            ><span class="required">*&nbsp;</span>Type:</label\r\n          >\r\n          <select\r\n            class="form-input"\r\n            name="type"\r\n            id="type"\r\n          >\r\n            <option value="on-premise">On Premise Company</option>\r\n            <option value="private-cloud">Private Could Company</option>\r\n            <option value="public-cloud">Public Cloud Company</option>\r\n          </select>\r\n          <p id="type-error" class="error d-none">Type is a required field!</p>\r\n        </div>\r\n        <div class="form-field">\r\n          <label class="form-label" for="customer-type"\r\n            ><span class="required">*&nbsp;</span>Type:</label\r\n          >\r\n          <select\r\n            class="form-input"\r\n            name="customer-type"\r\n            id="customer-type"\r\n          >\r\n            <option value="company">Company</option>\r\n            <option value="lead">Lead</option>\r\n          </select>\r\n          <p id="customer-type-error" class="error d-none">\r\n            Customer Type is a required field!\r\n          </p>\r\n        </div>\r\n        <div class="form-field">\r\n          <label class="form-label" for="url"\r\n            ><span class="required">*&nbsp;</span>URL:</label\r\n          >\r\n          <input\r\n            class="form-input"\r\n            name="url"\r\n            id="url"\r\n            type="text"\r\n          />\r\n          <p id="url-error" class="error d-none">URL is a required field!</p>\r\n        </div>\r\n        <div class="form-field">\r\n          <label class="form-label" for="address-line-1"\r\n            ><span class="required">*&nbsp;</span>Address Line 1:</label\r\n          >\r\n          <input\r\n            class="form-input"\r\n            name="address-line-1"\r\n            id="address-line-1"\r\n            type="text"\r\n          />\r\n          <p id="address-line-1-error" class="error d-none">\r\n            Address Line 1 is a required field!\r\n          </p>\r\n        </div>\r\n        <div class="form-field">\r\n          <label class="form-label" for="address-line-2">Address Line 2:</label>\r\n          <input\r\n            class="form-input"\r\n            name="address-line-2"\r\n            id="address-line-2"\r\n            type="text"\r\n          />\r\n        </div>\r\n        <div class="form-field">\r\n          <label class="form-label" for="city"\r\n            ><span class="required">*&nbsp;</span>City:</label\r\n          >\r\n          <input\r\n            class="form-input"\r\n            name="city"\r\n            id="city"\r\n            type="text"\r\n          />\r\n          <p id="city-error" class="error d-none">City is a required field!</p>\r\n        </div>\r\n        <div class="form-field">\r\n          <label class="form-label" for="zip"\r\n            ><span class="required">*&nbsp;</span>ZIP:</label\r\n          >\r\n          <input\r\n            class="form-input"\r\n            name="zip"\r\n            id="zip"\r\n            type="text"\r\n          />\r\n          <p id="zip-error" class="error d-none">ZIP is a required field!</p>\r\n        </div>\r\n        <div class="form-field">\r\n          <label class="form-label" for="country"\r\n            ><span class="required">*&nbsp;</span>Country:</label\r\n          >\r\n          <input\r\n            class="form-input"\r\n            name="country"\r\n            id="country"\r\n            type="text"\r\n          />\r\n          <p id="country-error" class="error d-none">\r\n            Country is a required field!\r\n          </p>\r\n        </div>\r\n        <div class="form-field">\r\n          <label class="form-label" for="state"\r\n            ><span class="required">*&nbsp;</span>State:</label\r\n          >\r\n          <input\r\n            class="form-input"\r\n            name="state"\r\n            id="state"\r\n            type="text"\r\n          />\r\n          <p id="state-error" class="error d-none">\r\n            State is a required field!\r\n          </p>\r\n        </div>\r\n        <div class="form-field">\r\n          <label class="form-label" for="vat-id"\r\n            ><span class="required">*&nbsp;</span>VAT ID:</label\r\n          >\r\n          <input\r\n            class="form-input"\r\n            name="vat-id"\r\n            id="vat-id"\r\n            type="text"\r\n          />\r\n          <p id="vat-id-error" class="error d-none">\r\n            VAT ID is a required field!\r\n          </p>\r\n        </div>\r\n        <div class="form-field">\r\n          <label class="form-label" for="phone"\r\n            ><span class="required">*&nbsp;</span>Phone:</label\r\n          >\r\n          <input\r\n            class="form-input"\r\n            name="phone"\r\n            id="phone"\r\n            type="text"\r\n          />\r\n          <p id="phone-error" class="error d-none">\r\n            Phone is a required field!\r\n          </p>\r\n        </div>\r\n        <div class="form-field">\r\n          <label class="form-label" for="fax">Fax:</label>\r\n          <input\r\n            class="form-input"\r\n            name="fax"\r\n            id="fax"\r\n            type="text"\r\n          />\r\n        </div>\r\n      </div>\r\n      <button class="submit-btn" type="submit">Submit</button>\r\n    </form>\r\n  </div>\r\n</div>`\r\n\r\n  let body = document.querySelector("body");\r\n  let div = document.createElement("div");\r\n  div.innerHTML = formText;\r\n  body.appendChild(div);\r\n\r\n  // Get the modal\r\n  var modal = document.getElementById("myModal");\r\n\r\n  // Get the <span> element that closes the modal\r\n  var span = document.getElementsByClassName("close")[0];\r\n  // When the user clicks on the button, open the modal\r\n  modal.style.display = "block";\r\n\r\n  // When the user clicks on <span> (x), close the modal\r\n  span.onclick = function () {\r\n    modal.style.display = "none";\r\n  };\r\n\r\n  // When the user clicks anywhere outside of the modal, close it\r\n  window.onclick = function (event) {\r\n    if (event.target == modal) {\r\n      modal.style.display = "none";\r\n    }\r\n  };\r\n\r\n  document.querySelector("#main-form").addEventListener("submit", async (e) => {\r\n    e.preventDefault();\r\n    const payload = {\r\n      name: document.getElementById("name").value,\r\n      type: document.getElementById("type").value,\r\n      customerType: document.getElementById("customer-type").value,\r\n      url: document.getElementById("url").value,\r\n      addressLine1: document.getElementById("address-line-1").value,\r\n      addressLine2: document.getElementById("address-line-2").value,\r\n      city: document.getElementById("city").value,\r\n      zip: document.getElementById("zip").value,\r\n      country: document.getElementById("country").value,\r\n      state: document.getElementById("state").value,\r\n      vatId: document.getElementById("vat-id").value,\r\n      phone: document.getElementById("phone").value,\r\n      fax: document.getElementById("fax").value,\r\n    };\r\n    if (!payload.name) {\r\n      document.getElementById("name-error").classList.remove("d-none");\r\n      document.getElementById("name").classList.add("form-input-error");\r\n    }\r\n    if (!payload.type) {\r\n      document.getElementById("customer-type-error").classList.remove("d-none");\r\n      document\r\n        .getElementById("customer-type")\r\n        .classList.add("form-input-error");\r\n    }\r\n    if (!payload.customerType) {\r\n      document.getElementById("type-error").classList.remove("d-none");\r\n      document.getElementById("type").classList.add("form-input-error");\r\n    }\r\n    if (!payload.name) {\r\n      document.getElementById("url-error").classList.remove("d-none");\r\n      document.getElementById("url").classList.add("form-input-error");\r\n    }\r\n    if (!payload.addressLine1) {\r\n      document\r\n        .getElementById("address-line-1-error")\r\n        .classList.remove("d-none");\r\n      document\r\n        .getElementById("address-line-1")\r\n        .classList.add("form-input-error");\r\n    }\r\n    if (!payload.city) {\r\n      document.getElementById("city-error").classList.remove("d-none");\r\n      document.getElementById("city").classList.add("form-input-error");\r\n    }\r\n    if (!payload.zip) {\r\n      document.getElementById("zip-error").classList.remove("d-none");\r\n      document.getElementById("zip").classList.add("form-input-error");\r\n    }\r\n    if (!payload.country) {\r\n      document.getElementById("country-error").classList.remove("d-none");\r\n      document.getElementById("country").classList.add("form-input-error");\r\n    }\r\n    if (!payload.state) {\r\n      document.getElementById("state-error").classList.remove("d-none");\r\n      document.getElementById("state").classList.add("form-input-error");\r\n    }\r\n    if (!payload.vatId) {\r\n      document.getElementById("vat-id-error").classList.remove("d-none");\r\n      document.getElementById("vat-id").classList.add("form-input-error");\r\n    }\r\n    if (!payload.phone) {\r\n      document.getElementById("phone-error").classList.remove("d-none");\r\n      document.getElementById("phone").classList.add("form-input-error");\r\n    }\r\n\r\n    if (\r\n      !payload.name ||\r\n      !payload.type ||\r\n      !payload.customerType ||\r\n      !payload.url ||\r\n      !payload.addressLine1 ||\r\n      !payload.zip ||\r\n      !payload.country ||\r\n      !payload.city ||\r\n      !payload.state ||\r\n      !payload.vatId ||\r\n      !payload.phone\r\n    ) {\r\n      return;\r\n    }\r\n\r\n    try {\r\n      const url = "http://127.0.0.1/test";\r\n      const response = await fetch(url, {\r\n        method: "POST",\r\n        headers: {\r\n          "Content-Type": "application/json",\r\n        },\r\n        body: {\r\n          ...payload,\r\n        },\r\n      });\r\n      console.log({response})\r\n    } catch (e) {\r\n      alert(e);\r\n    }\r\n  });\r\n}',
                successFeedback: null,
                arguments: [],
                surveyId: "187564a6-89ed-e715-0120-0aa743cca63a",
                configIndex: 0,
            },
        ],
        addProductsManually: false,
        minimizeCart: false,
        minimizeSteps: false,
    },
    stylesConfiguration: {
        steps: {
            cardBgColor: "#ffffff",
            cardTextColor: "#000000",
            cardHeaderBgColor: "#fafafa",
            cardHeaderTextColor: "#000000",
        },
        questionDetails: { cardBgColor: "#ffffff", cardTextColor: "#000000" },
        cart: {
            cardBgColor: "#ffffff",
            cardTextColor: "#000000",
            cardHeaderBgColor: "#fafafa",
            cardHeaderTextColor: "#000000",
        },
        productDetails: {
            cardBgColor: "#ffffff",
            cardTextColor: "#000000",
            cardHeaderBgColor: "#fafafa",
            cardHeaderTextColor: "#000000",
        },
        layout: {
            id: "485657ce-7f33-4c21-9caa-c6c581f2e89b",
            type: "row",
            customCss: "min-height: 100vh;",
            children: [
                {
                    id: "445dfbbe-e716-4bc3-8ba9-46cb24787d45",
                    type: "column",
                    children: [],
                    value: "25%",
                    contains: "steps",
                },
                {
                    id: "e5e99ccc-d424-4b57-b2f9-60bc2f03ece3",
                    type: "column",
                    children: [
                        {
                            id: "641736fe-91bf-4fa1-92ef-084038c3e3fa",
                            type: "row",
                            children: [],
                            value: "minmax(300px, max-content)",
                            contains: "question",
                        },
                        {
                            id: "2ac7a9a7-9cce-4f61-94b3-47c66a7967ee",
                            type: "row",
                            children: [
                                {
                                    id: "ad319156-69b7-4f6c-84ef-69714d0bea47",
                                    type: "column",
                                    children: [],
                                    value: "60%",
                                    contains: "details",
                                },
                                {
                                    id: "e3417f6d-3c3d-430a-9629-c977da669f69",
                                    type: "column",
                                    children: [],
                                    value: "40%",
                                    contains: "cart",
                                },
                            ],
                            value: "auto",
                            contains: null,
                        },
                    ],
                    value: "75%",
                    contains: null,
                },
            ],
            value: "100%",
            contains: null,
        },
    },
};
