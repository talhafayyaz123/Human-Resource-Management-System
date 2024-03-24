Feature: Vacations
    In order to keep Vacation Requests api stable
    As a tester
    I want to make sure that everything works as expected

    Scenario: Create A Vacations
        Given I have a valid token
        Given I make a POST request to /api/employee-vacation
        And I set body to
            """
            {
                "startDate": "2023-04-06",
                "endDate": "2023-04-07",
                "leaveType": "whole",
                "replacements": [
                   1
                ],
                "isSpecialLeave": 0,
                "userId": "186fc40b-55dc-feae-025a-f000fca5d884"
            }
            """
        When I receive a response
        And I expect response to match a json snapshot vacation-create
        Then I expect response should have a status 200

    Scenario: List Vacations
        Given I have a valid token
        Given I make a GET request to /api/employee-vacation
        When I receive a response
        And I store response at data[0].id as VacationId
        And I expect response to match a json snapshot vacations-list
        Then I expect response should have a status 200

    Scenario: Get A Vacation With Id
        Given I have a valid token
        Given I make a GET request to /api/employee-vacation/{id}
        And I set path param id to $S{VacationId}
        When I receive a response
        And I expect response to match a json snapshot get-vacation-$S{VacationId}
        Then I expect response should have a status 200

    Scenario: Update A Vacation With Id
        Given I have a valid token
        Given I make a PUT request to /api/employee-vacation/{id}
        And I set path param id to $S{VacationId}
        And I set body to
            """
                {
                "startDate": "2023-04-06",
                "endDate": "2023-04-07",
                "leaveType": "whole",
                "replacements": [
                    1
                ],
                "isSpecialLeave": 0,
                "userId": "186fc40b-55dc-feae-025a-f000fca5d884"
                }
            """
        When I receive a response
        And I expect response to match a json snapshot update-vacation-$S{VacationId}
        Then I expect response should have a status 200

    Scenario: Delete A Vacation With Id
        Given I have a valid token
        Given I make a DELETE request to /api/employee-vacation/{id}
        And I set path param id to $S{VacationId}
        When I receive a response
        And I expect response to match a json snapshot delete-vacation-$S{VacationId}
        Then I expect response should have a status 200