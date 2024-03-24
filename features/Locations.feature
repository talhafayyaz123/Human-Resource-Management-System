Feature: Locations
  In order to keep Locations api stable
  As a tester
  I want to make sure that everything works as expected

  Scenario: Create A location
    Given I have a valid token
    Given I make a POST request to /api/user/locations
    And I set body to
      """
      {
      "name": "Test",
      "street": "Street 1",
      "address": "Address 1",
      "zipCode": "45341",
      "state": "State 1",
      "city": "City 1",
      "country": "Country 1"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    # And I expect response to match a json snapshot Location-create
    Then I expect response should have a status 200

  Scenario: List locations
    Given I have a valid token
    Given I make a GET request to /api/user/locations
    When I receive a response
    # And I expect response to match a json snapshot locations-list
    And I store response at data[0].id as LocationId
    Then I expect response should have a status 200

  Scenario: Get Location By Id
    Given I have a valid token
    Given I make a GET request to /api/user/locations/{id}
    And I set path param id to $S{LocationId}
    When I receive a response
    # And I expect response to match a json snapshot get-location-by-$S{LocationId}
    Then I expect response should have a status 200

  Scenario: Update A Location With Id
    Given I have a valid token
    Given I make a PUT request to /api/user/locations/{id}
    And I set path param id to $S{LocationId}
    And I set body to
      """
      {
        "name": "Update Test",
        "street": "Update Street 1",
        "address": "Update Address 1",
        "zipCode": "45341",
        "state": "Update State 1",
        "city": "Update City 1",
        "country": "Update Country 1"
      }
      """
    When I receive a response
    # And I expect response to match a json snapshot update-location-$S{LocationId}
    Then I expect response should have a status 200

  Scenario: Delete A Location By Id
    Given I have a valid token
    Given I make a DELETE request to /api/user/locations/{id}
    And I set path param id to $S{LocationId}
    When I receive a response
    # And I expect response to match a json snapshot delete-location-by-$S{LocationId}
    Then I expect response should have a status 200
