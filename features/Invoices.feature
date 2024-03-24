Feature: Invoices
    In order to keep Vacation Requests api stable
    As a tester
    I want to make sure that everything works as expected

    Scenario: List Companies
        Given I have a valid token
        Given I make a GET request to /api/companies
        And I set body to
            """
            {
                "customerType": "customer"
            }
            """
        When I receive a response
        Then I expect response should have a status 200
        And I store response at data[0].id as CompanyId

    Scenario: List Products
        Given I have a valid token
        Given I make a GET request to /api/products
        When I receive a response
        Then I expect response should have a status 200
        And I store response at products.data[0].id as ProductId
        And I store response at products.data[0].name as ProductName


    Scenario: List Terms Of Payment
        Given I have a valid token
        Given I make a GET request to /api/terms-of-payment
        When I receive a response
        And I expect response to match a json snapshot terms-of-payment-list
        And I store response at data[0].id as TermOfPaymentId
        Then I expect response should have a status 200

    Scenario: Create A Invoice
        Given I have a valid token
        Given I make a POST request to /api/invoices
        And I set body to
            """
            {
                "userId": "18748fed-c86e-9d6c-027e-3b6ddd855225",
                "category": "license",
                "customNotesFields": "Dolor corrupti haru",
                "customers": {
                    "id": "$S{CompanyId}"
                },
                "invoicePeriod": 100,
                "dueDate": "2023-04-17 08:57:01",
                "endDate": "2023-04-17 08:57:01",
                "groupedBy": "nothing",
                "invoiceType": "invoice-correction",
                "paymentPeriod": "",
                "paymentTerms": "Invoice text",
                "performanceRecord": "",
                "products": [
                    {
                        "articleNumber": "SW-1000",
                        "discount": 0,
                        "id": "$S{ProductId}",
                        "listingPrice": "895",
                        "maintenancePrice": "161.1",
                        "maintenanceRate": 18,
                        "name": "$S{ProductName}",
                        "priceWithoutTax": "895.00",
                        "profit": "268.5",
                        "quantity": 1,
                        "salePrice": "895",
                        "status": "active",
                        "tax": "19",
                        "totalPrice": "1065.05",
                        "totalSalePriceAdjustedForQuantity": 895
                    }
                ],
                "systems": [],
                "termsOfPayment": "$S{TermOfPaymentId}",
                "totalAmount": "1065.05",
                "totalAmountWithoutTax": "895.00",
                "totalTaxAmount": "€170.05"
            }
            """
        When I receive a response
        And I expect response to match a json snapshot invoices-create
        And I store response at InvoiceId as invoiceId
        Then I expect response should have a status 200

    Scenario: List Invoices
        Given I have a valid token
        Given I make a GET request to /api/invoices
        When I receive a response
        And I expect response to match a json snapshot invoices-list
        Then I expect response should have a status 200

Scenario: Get A Invoice With Id
    Given I have a valid token
    Given I make a GET request to /api/invoices/{id}
    And I set path param id to  $S{invoiceId}
    When I receive a response
    Then I expect response should have a status 200
    And I expect response to match a json snapshot get-invoice-$S{invoiceId}

Scenario: Update A Invoice With Id
    Given I have a valid token
    Given I make a PUT request to /api/invoices/{id}
    And I set path param id to $S{invoiceId}
    And I set body to
        """
            {
            "userId": "18748fed-c86e-9d6c-027e-3b6ddd855225",
            "category": "license",
            "customNotesFields": "Dolor corrupti haru",
            "customers": {
                "id": "$S{CompanyId}"
            },
            "invoiceStatus":"draft",
            "invoicePeriod": 100,
            "dueDate": "2023-04-17 08:57:01",
            "endDate": "2023-04-17 08:57:01",
            "groupedBy": "nothing",
            "invoiceType": "invoice-correction",
            "paymentPeriod": "",
            "paymentTerms": "Invoice text",
            "performanceRecord": "",
            "products": [
                {
                    "articleNumber": "SW-1000",
                    "discount": 0,
                    "id": "$S{ProductId}",
                    "listingPrice": "895",
                    "maintenancePrice": "161.1",
                    "maintenanceRate": 18,
                    "name": "$S{ProductName}",
                    "priceWithoutTax": "895.00",
                    "profit": "268.5",
                    "quantity": 1,
                    "salePrice": "895",
                    "status": "active",
                    "tax": "19",
                    "totalPrice": "1065.05",
                    "totalSalePriceAdjustedForQuantity": 895
                }
            ],
            "systems": [],
            "termsOfPayment": "$S{TermOfPaymentId}",
            "totalAmount": "1065.05",
            "totalAmountWithoutTax": "895.00",
            "totalTaxAmount": "€170.05"
            }
        """
    When I receive a response
    Then I expect response should have a status 200
    And I expect response to match a json snapshot update-invoice-$S{invoiceId}

Scenario: Delete A Invoice With Id
    Given I have a valid token
    Given I make a DELETE request to /api/invoices/{id}
    And I set path param id to  $S{invoiceId}
    When I receive a response
    Then I expect response should have a status 200
    And I expect response to match a json snapshot delete-invoice-$S{invoiceId}