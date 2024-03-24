  Feature: Softwares
    In order to keep Softwares api stable
    As a tester
    I want to make sure that everything works as expected

  Scenario: Create A Software
    Given I have a valid token
    Given I make a POST request to /api/softwares
    And I set body to
      """
      {
        "name": "Test Software"
      }
      """
    When I receive a response
    # And I expect response to match a json snapshot version-create
    Then I expect response should have a status 200

  Scenario: List Softwares
    Given I have a valid token
    Given I make a GET request to /api/softwares
    When I receive a response
    # And I expect response to match a json snapshot version-list
    And I store response at softwares.data[0].id as SoftwareId
    Then I expect response should have a status 200

  Scenario: Get A Software With Id
    Given I have a valid token
    Given I make a GET request to /api/softwares/{id}
    And I set path param id to $S{SoftwareId}
    When I receive a response
    # And I expect response to match a json snapshot get-unit-$S{SoftwareId}
    Then I expect response should have a status 200

  Scenario: Update A Software With Id
    Given I have a valid token
    Given I make a PUT request to /api/softwares/{id}
    And I set path param id to $S{SoftwareId}
    And I set body to
      """
      {
        "name": "ELO"
      }
      """
    When I receive a response
# And I expect response to match a json snapshot update-unit-$S{SoftwareId}
    Then I expect response should have a status 200

  Scenario: Delete A Software With Id
    Given I have a valid token
    Given I make a DELETE request to /api/softwares/{id}
    And I set path param id to $S{SoftwareId}
    When I receive a response
    # And I expect response to match a json snapshot delete-version-$S{SoftwareId}
    Then I expect response should have a status 200