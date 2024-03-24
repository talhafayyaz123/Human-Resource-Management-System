  Feature: Surveys
    In order to keep Surveys api stable
    As a tester
    I want to make sure that everything works as expected

  Scenario: Create A Survey
    Given I have a valid token
    Given I make a POST request to /api/surveys
    And I set body to
      """
      {
        "addProductsManually": 0,
        "minimizeCart": 0,
        "minimizeSteps": 0
      }
      """
    When I receive a response
    # And I expect response to match a json snapshot survey-create
    Then I expect response should have a status 200

  Scenario: List Surveys
    Given I have a valid token
    Given I make a GET request to /api/surveys
    When I receive a response
    # And I expect response to match a json snapshot survey-list
    And I store response at data[0].id as surveyId
    Then I expect response should have a status 200

  Scenario: Get A Survey With Id
    Given I have a valid token
    Given I make a GET request to /api/surveys/{id}
    And I set path param id to $S{surveyId}
    When I receive a response
    # And I expect response to match a json snapshot get-survey-$S{surveyId}
    Then I expect response should have a status 200


  Scenario: Update A Survey With Id
    Given I have a valid token
    Given I make a PUT request to /api/surveys/{id}
    And I set path param id to $S{surveyId}
    And I set body to
      """
      {
        "addProductsManually": 0,
        "minimizeCart": 0,
        "minimizeSteps": 0
      }
      """
    When I receive a response
# And I expect response to match a json snapshot update-survey-$S{surveyId}
    Then I expect response should have a status 200

  Scenario: Delete A Survey With Id
    Given I have a valid token
    Given I make a DELETE request to /api/surveys/{id}
    And I set path param id to $S{surveyId}
    When I receive a response
    # And I expect response to match a json snapshot delete-survey-$S{surveyId}
    Then I expect response should have a status 200