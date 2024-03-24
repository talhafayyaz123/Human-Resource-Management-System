Feature: ProductGroup
  In order to keep Product Groups api stable
  As a tester
  I want to make sure that everything works as expected

  Scenario: List Product Groups
    Given I have a valid token
    Given I make a GET request to /api/groups
    When I receive a response
    And I store response at groups.data[0].id as GroupId
#    And I expect response to match a json snapshot product-group
    Then I expect response should have a status 200

  Scenario: Create A Product Group
    Given I have a valid token
    Given I make a POST request to /api/groups
    And I set body to
      """
      {
        "name": "test-group"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    #And I store response at groupId as GroupId
#    And I expect response to match a json snapshot product-group-create
    Then I expect response should have a status 200

  Scenario: Get A Product Group With Id
    Given I have a valid token
    Given I make a GET request to /api/groups/{id}
    And I set path param id to $S{GroupId}
    When I receive a response
#    And I expect response to match a json snapshot get-group-by-$S{GroupId}
    Then I expect response should have a status 200

  Scenario: Update A Product Group By Id
    Given I have a valid token
    Given I make a PUT request to /api/groups/{id}
    And I set path param id to $S{GroupId}
    And I set body to
      """
      {
       "name": "test-group"
      }
      """
    When I receive a response
#    And I expect response to match a json snapshot update-group-by-$S{GroupId}
    Then I expect response should have a status 200

  Scenario: Delete A Product Group By Id
    Given I have a valid token
    Given I make a DELETE request to /api/groups/{id}
    And I set path param id to $S{GroupId}
    When I receive a response
#    And I expect response to match a json snapshot delete-group-by-$S{GroupId}
    Then I expect response should have a status 200
