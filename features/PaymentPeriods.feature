  Feature: Payment Periods
    In order to keep Payment Periods api stable
    As a tester
    I want to make sure that everything works as expected

  Scenario: Create A Payment Period
    Given I have a valid token
    Given I make a POST request to /api/periods
    And I set body to
      """
      {
        "name": "Test Payment",
        "billingCycle": 0
      }
      """
    When I receive a response
    # And I expect response to match a json snapshot period-create
    Then I expect response should have a status 200

  Scenario: List Payment Periods
    Given I have a valid token
    Given I make a GET request to /api/periods
    When I receive a response
    # And I expect response to match a json snapshot period-list
    And I store response at periods.data[0].id as PeriodId
    Then I expect response should have a status 200

  Scenario: Get A Payment Period With Id
    Given I have a valid token
    Given I make a GET request to /api/periods/{id}
    And I set path param id to $S{PeriodId}
    When I receive a response
    # And I expect response to match a json snapshot get-period-$S{PeriodId}
    Then I expect response should have a status 200

  Scenario: Update A Payment Period With Id
    Given I have a valid token
    Given I make a PUT request to /api/periods/{id}
    And I set path param id to $S{PeriodId}
    And I set body to
      """
      {
        "name": "Test Payment",
        "billingCycle": 0
      }
      """
    When I receive a response
# And I expect response to match a json snapshot update-period-$S{PeriodId}
    Then I expect response should have a status 200

  Scenario: Delete A Payment Period With Id
    Given I have a valid token
    Given I make a DELETE request to /api/periods/{id}
    And I set path param id to $S{PeriodId}
    When I receive a response
    # And I expect response to match a json snapshot delete-period-$S{PeriodId}
    Then I expect response should have a status 200