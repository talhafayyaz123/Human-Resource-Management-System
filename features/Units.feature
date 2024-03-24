  Feature: Units
    In order to keep Units api stable
    As a tester
    I want to make sure that everything works as expected

  Scenario: Create A Unit
    Given I have a valid token
    Given I make a POST request to /api/units
    And I set body to
      """
      {
        "name": "Test Unit",
        "shortName": "TU"
      }
      """
    When I receive a response
    # And I expect response to match a json snapshot version-create
    Then I expect response should have a status 200

  Scenario: List Units
    Given I have a valid token
    Given I make a GET request to /api/units
    When I receive a response
    # And I expect response to match a json snapshot version-list
    And I store response at units.data[0].id as UnitId
    Then I expect response should have a status 200

  Scenario: Get A Unit With Id
    Given I have a valid token
    Given I make a GET request to /api/units/{id}
    And I set path param id to $S{UnitId}
    When I receive a response
    # And I expect response to match a json snapshot get-unit-$S{UnitId}
    Then I expect response should have a status 200

  Scenario: Update A Unit With Id
    Given I have a valid token
    Given I make a PUT request to /api/units/{id}
    And I set path param id to $S{UnitId}
    And I set body to
      """
      {
        "name": "pauschal",
        "shortName": "pausch."
      }
      """
    When I receive a response
# And I expect response to match a json snapshot update-unit-$S{UnitId}
    Then I expect response should have a status 200

  Scenario: Delete A Unit With Id
    Given I have a valid token
    Given I make a DELETE request to /api/units/{id}
    And I set path param id to $S{UnitId}
    When I receive a response
    # And I expect response to match a json snapshot delete-version-$S{UnitId}
    Then I expect response should have a status 200