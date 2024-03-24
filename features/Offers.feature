  Feature: Offers
    In order to keep Offers api stable
    As a tester
    I want to make sure that everything works as expected

    Scenario: List Terms Of Payment
      Given I have a valid token
      Given I make a GET request to /api/terms-of-payment
      When I receive a response
      And I expect response to match a json snapshot terms-of-payment-list
      And I store response at data[0].id as TermOfPaymentId
      Then I expect response should have a status 200

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

  Scenario: Create A Offer
    Given I have a valid token
    Given I make a POST request to /api/sale-offers
    And I set body to
      """
      {
        "companyId": "$S{CompanyId}",
        "termId": "$S{TermOfPaymentId}",
        "receiverType": "customer",
        "type": "offer",
        "expiryDate": "2023-05-12 11:13:37",
        "createdBy": "18748fed-c86e-9d6c-027e-3b6ddd855225",
        "paymentTerms": "Payment term",
        "types": []
      }
      """
    When I receive a response
    And I expect response to match a json snapshot offer-create
    Then I expect response should have a status 200

  Scenario: List Offers
    Given I have a valid token
    Given I make a GET request to /api/sale-offers
    When I receive a response
    And I expect response to match a json snapshot offers-list
    And I store response at data[0].id as OfferId
    Then I expect response should have a status 200

  Scenario: Get A Offer With Id
    Given I have a valid token
    Given I make a GET request to /api/sale-offers/{id}
    And I set path param id to $S{OfferId}
    When I receive a response
    And I expect response to match a json snapshot get-offer-$S{OfferId}
    Then I expect response should have a status 200

  Scenario: Update A Offer With Id
    Given I have a valid token
    Given I make a PUT request to /api/sale-offers/{id}
    And I set path param id to  $S{OfferId}
    And I set body to
      """
      {
        "companyId": "$S{CompanyId}",
        "termId": "$S{TermOfPaymentId}",
        "receiverType": "customer",
        "type": "offer",
        "expiryDate": "2023-06-11 11:13:37",
        "paymentTerms": "Payment term",
        "createdBy": "18748fed-c86e-9d6c-027e-3b6ddd855225",
        "types": []
      }
      """
    When I receive a response
    And I expect response to match a json snapshot update-offer-$S{OfferId}
    Then I expect response should have a status 200

  Scenario: Delete A Offer With Id
    Given I have a valid token
    Given I make a DELETE request to /api/sale-offers/{id}
    And I set path param id to $S{OfferId}
    When I receive a response
    And I expect response to match a json snapshot delete-offer-$S{OfferId}
    Then I expect response should have a status 200