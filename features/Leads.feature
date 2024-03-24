Feature: Leads
  In order to keep Leads api stable
  As a tester
  I want to make sure that everything works as expected

  Scenario: List Leads
    Given I have a valid token
    Given I make a GET request to /api/companies
    And I set body to
      """
      {
        "customerType": "lead"
      }
      """
    When I receive a response
    And I expect response to match a json snapshot leads-list
    Then I expect response should have a status 200

  Scenario: Create A Lead
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
      "customerType": "lead",
      "assignees":["1"],
      "addressFirst": "123 Main St",
      "city": "Anytown",
      "country": "USA",
      "state": "CA",
      "zip": "12345",
      "expiryDate":"10-02-2032"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot lead-create
    And I store response at company_id as LeadId
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
      "companyId":"$S{LeadId}"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot create-lead-location-by-$S{LeadId}
    Then I expect response should have a status 200

  Scenario: Get Lead's Location By Comapny Id
    Given I have a valid token
    Given I make a GET request to /api/company-locations/company/{id}
    And I set path param id to $S{LeadId}
    When I receive a response
    And I expect response to match a json snapshot get-locations-by-Lead-$S{LeadId}
    And I store response at locations[0].id as LocationId
    Then I expect response should have a status 200

  Scenario: Get A Lead With Id
    Given I have a valid token
    Given I make a GET request to /api/companies/{id}
    And I set path param id to $S{LeadId}
    When I receive a response
    And I expect response to match a json snapshot get-lead-by-$S{LeadId}
    Then I expect response should have a status 200

  Scenario: Update A Lead By Id
    Given I have a valid token
    Given I make a PUT request to /api/companies/{id}
    And I set path param id to $S{LeadId}
    And I set body to
      """
      {
      "companyName": "Acme Inc Updated.",
      "vatId": "1234567890",
      "url": "https://www.acmeinc.com",
      "phone": "555-555-5555",
      "fax": null,
      "type": "premise",
      "customerType": "lead",
      "addressFirst": "123 Main St update",
      "city": "Anytown",
      "country": "USA",
      "state": "CA",
      "zip": "12345",
      "location_id": "$S{LocationId}",
      "expiryDate":"10-02-2032"
      }
      """
    When I receive a response
    And I expect response to match a json snapshot update-lead-by-$S{LocationId}
    Then I expect response should have a status 200

  Scenario: Delete A Lead By Id
    Given I have a valid token
    Given I make a DELETE request to /api/companies/{id}
    And I set path param id to $S{LeadId}
    When I receive a response
    And I expect response to match a json snapshot delete-lead-by-$S{LeadId}
    Then I expect response should have a status 200

  Scenario: Delete A Location By Id
    Given I have a valid token
    Given I make a DELETE request to /api/company-locations/{id}
    And I set path param id to $S{LocationId}
    When I receive a response
    And I expect response to match a json snapshot delete-lead-location-by-$S{LeadId}
    Then I expect response should have a status 200
