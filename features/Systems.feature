Feature: Systems
  In order to keep Customers api stable
  As a tester
  I want to make sure that everything works as expected

  Scenario: Create A System
    Given I have a valid token
    Given I make a POST request to /api/systems
    And I set body to
      """
      {
      "systemUser": "1862d4e1-497b-2efb-013b-b3192e29fa8b",
      "type": "premise",
      "systemProducts": [],
      "serverIp": "http",
      "serverPort": "3321",
      "username": "marcel@saasly.com",
      "password": "12345",
      "namespace": "",
      "instanceType": "productive",
      "operatingSystem": "linux"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot system-create
    Then I expect response should have a status 200

  Scenario: List Systems
    Given I have a valid token
    Given I make a GET request to /api/systems
    When I receive a response
    And I store response at data.data[0].id as SystemId
    And I expect response to match a json snapshot systems-list
    Then I expect response should have a status 200

  Scenario: Get System By Id
    Given I have a valid token
    Given I make a GET request to /api/systems/{id}
    And I set path param id to $S{SystemId}
    When I receive a response
    And I expect response to match a json snapshot get-system-by-$S{SystemId}
    Then I expect response should have a status 200

  Scenario: Delete A System By Id
    Given I have a valid token
    Given I make a DELETE request to /api/systems/{id}
    And I set path param id to $S{SystemId}
    When I receive a response
    And I expect response to match a json snapshot delete-system-by-$S{SystemId}
    Then I expect response should have a status 200
