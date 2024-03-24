Feature: Suppliers
  In order to keep Suppliers api stable
  As a tester
  I want to make sure that everything works as expected

  Scenario: Create A Supplier
    Given I have a valid token
    Given I make a POST request to /api/suppliers
    And I set body to
      """
      {
      "supplierName": "Test",
      "supplierNumber": "0",
      "vatId": "123",
      "url": "http",
      "fax": "123",
      "type": "premise",
      "phone": "+923150001320",
      "supplierType": "supplier",
      "termsOfPayment": 1,
      "addressFirst": "GM Abad Faisalabad",
      "addressSecond": "FSD",
      "city": "Faisalabad",
      "zip": "86000",
      "state": "Punjab",
      "country": "Pakistan"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot supplier-create
    And I store response at supplier_id as SupplierId
    Then I expect response should have a status 200

  Scenario: List Suppliers
    Given I have a valid token
    Given I make a GET request to /api/suppliers
    When I receive a response
    And I expect response to match a json snapshot suppliers-list
    Then I expect response should have a status 200

  Scenario: Get Supplier By Id
    Given I have a valid token
    Given I make a GET request to /api/suppliers/{id}
    And I set path param id to $S{SupplierId}
    When I receive a response
    And I expect response to match a json snapshot get-supplier-by-$S{SupplierId}
    Then I expect response should have a status 200

  Scenario: Delete A Customer By Id
    Given I have a valid token
    Given I make a DELETE request to /api/suppliers/{id}
    And I set path param id to $S{SupplierId}
    When I receive a response
    And I expect response to match a json snapshot delete-supplier-by-$S{SupplierId}
    Then I expect response should have a status 200
