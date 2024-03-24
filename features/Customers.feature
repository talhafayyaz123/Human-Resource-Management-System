Feature: Customers
  In order to keep Customers api stable
  As a tester
  I want to make sure that everything works as expected

  Scenario: List Customers
    Given I have a valid token
    Given I make a GET request to /api/companies
    And I set body to
      """
      {
        "customerType": "customer"
      }
      """
    When I receive a response
    And I expect response to match a json snapshot customers-list
    Then I expect response should have a status 200

  Scenario: Create A Customer
    Given I have a valid token
    Given I make a POST request to /api/companies
    And I set body to
      """
      {
      "companyName": "Acme Inc.",
      "vatId": "1234567890",
      "url": "https://www.acmeinc.com",
      "phone": "555-555-5555",
      "fax": null,
      "type": "premise",
      "customerType": "customer",
      "addressFirst": "123 Main St",
      "city": "Anytown",
      "country": "USA",
      "state": "CA",
      "zip": "12345"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot customer-create
    And I store response at company_id as CustomerId
    Then I expect response should have a status 200

  Scenario: Create A Location
    Given I have a valid token
    Given I make a POST request to /api/company-locations
    And I set body to
      """
      {
      "id": "abc123",
      "addressFirst": "123 Main St",
      "addressSecond": "Apt 456",
      "city": "Anytown",
      "country": "USA",
      "state": "CA",
      "zip": "12345",
      "isHeadQuarters": true,
      "companyId":"$S{CustomerId}"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot create-customer-location-by-$S{CustomerId}
    Then I expect response should have a status 200

  Scenario: Get Customer's Location By Comapny Id
    Given I have a valid token
    Given I make a GET request to /api/company-locations/company/{id}
    And I set path param id to $S{CustomerId}
    When I receive a response
    And I expect response to match a json snapshot get-locations-by-customer-$S{CustomerId}
    And I store response at locations[0].id as LocationId
    Then I expect response should have a status 200

  Scenario: Get A Customer With Id
    Given I have a valid token
    Given I make a GET request to /api/companies/{id}
    And I set path param id to $S{CustomerId}
    When I receive a response
    And I expect response to match a json snapshot get-customer-by-$S{CustomerId}
    Then I expect response should have a status 200

  Scenario: Update A Customer By Id
    Given I have a valid token
    Given I make a PUT request to /api/companies/{id}
    And I set path param id to $S{CustomerId}
    And I set body to
      """
      {
      "companyName": "Acme Inc Updated.",
      "vatId": "1234567890",
      "url": "https://www.acmeinc.com",
      "phone": "555-555-5555",
      "fax": null,
      "type": "premise",
      "customerType": "customer",
      "addressFirst": "123 Main St update",
      "city": "Anytown",
      "country": "USA",
      "state": "CA",
      "zip": "12345",
      "location_id": "$S{LocationId}"
      }
      """
    When I receive a response
    And I expect response to match a json snapshot update-customer-by-$S{LocationId}
    Then I expect response should have a status 200

  Scenario: Delete A Customer By Id
    Given I have a valid token
    Given I make a DELETE request to /api/companies/{id}
    And I set path param id to $S{CustomerId}
    When I receive a response
    And I expect response to match a json snapshot delete-customer-by-$S{CustomerId}
    Then I expect response should have a status 200

  Scenario: Delete A Location By Id
    Given I have a valid token
    Given I make a DELETE request to /api/company-locations/{id}
    And I set path param id to $S{LocationId}
    When I receive a response
    And I expect response to match a json snapshot delete-customer-location-by-$S{CustomerId}
    Then I expect response should have a status 200
