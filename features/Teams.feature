  Feature: Teams
    In order to keep Versions api stable
    As a tester
    I want to make sure that everything works as expected

  Scenario: Create A Department
    Given I have a valid token
    Given I make a POST request to /api/user/departments
    And I set body to
      """
      {
        "name": "test department"
      }
      """
    When I receive a response
    Then I expect response should have a status 200

  Scenario: List Departments
    Given I have a valid token
    Given I make a GET request to /api/user/departments
    When I receive a response
    And I store response at data[0].id as departmentId
    Then I expect response should have a status 200

  Scenario: Create A Team
    Given I have a valid token
    Given I make a POST request to /api/user/teams
    And I set body to
      """
     {
      "name": "testing",
      "departmentId": "$S{departmentId}",
      "teamLeadId": "1867617a-9ffa-ff35-025a-42baa91ca4d3",
      "teamMembers": [
          "1867617a-9ffa-ff35-025a-42baa91ca4d3"
        
    ]
    }
      """
    When I receive a response
    Then I expect response should have a status 200

  Scenario: List Team
    Given I have a valid token
    Given I make a GET request to /api/user/teams
    When I receive a response
    # And I expect response to match a json snapshot team-list
    And I store response at data[0].id as TeamId
    Then I expect response should have a status 200

  Scenario: Get A Team With Id
    Given I have a valid token
    Given I make a GET request to /api/user/teams
    And I set path param id to $S{TeamId}
    When I receive a response
    # And I expect response to match a json snapshot get-Team-$S{TeamId}
    Then I expect response should have a status 200

  Scenario: Update A Team With Id
    Given I have a valid token
    Given I make a PUT request to /api/user/teams/{id}
    And I set path param id to $S{TeamId}
    And I set body to
      """
      {
      "name": "update Testing",
      "departmentId": "$S{departmentId}",
      "teamLeadId": "1867617a-9ffa-ff35-025a-42baa91ca4d3",
      "teamMembers": [
          "1867617a-9ffa-ff35-025a-42baa91ca4d3"
        
    ]
    }
      """
    When I receive a response
# And I expect response to match a json snapshot update-team-$S{TeamId}
    Then I expect response should have a status 200

  Scenario: Delete A Team With Id
    Given I have a valid token
    Given I make a DELETE request to /api/user/teams/{id}
    And I set path param id to $S{TeamId}
    When I receive a response
    # And I expect response to match a json snapshot delete-team-$S{TeamId}
    Then I expect response should have a status 200