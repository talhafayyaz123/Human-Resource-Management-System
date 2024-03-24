  Feature: Versions
    In order to keep Versions api stable
    As a tester
    I want to make sure that everything works as expected

  Scenario: Create A Version
    Given I have a valid token
    Given I make a POST request to /api/versions
    And I set body to
      """
      {
        "name": "21.4",
        "type": "hf"
      }
      """
    When I receive a response
    # And I expect response to match a json snapshot version-create
    Then I expect response should have a status 200

  Scenario: List Versions
    Given I have a valid token
    Given I make a GET request to /api/versions
    When I receive a response
    # And I expect response to match a json snapshot version-list
    And I store response at versions.data[0].id as VersionId
    Then I expect response should have a status 200

  Scenario: Get A Version With Id
    Given I have a valid token
    Given I make a GET request to /api/versions/{id}
    And I set path param id to $S{VersionId}
    When I receive a response
    # And I expect response to match a json snapshot get-version-$S{VersionId}
    Then I expect response should have a status 200

  Scenario: Update A Version With Id
    Given I have a valid token
    Given I make a PUT request to /api/versions/{id}
    And I set path param id to $S{VersionId}
    And I set body to
      """
      {
        "name": "21.1",
        "type": "lts"
      }
      """
    When I receive a response
# And I expect response to match a json snapshot update-version-$S{VersionId}
    Then I expect response should have a status 200

  Scenario: Delete A Version With Id
    Given I have a valid token
    Given I make a DELETE request to /api/versions/{id}
    And I set path param id to $S{VersionId}
    When I receive a response
    # And I expect response to match a json snapshot delete-version-$S{VersionId}
    Then I expect response should have a status 200