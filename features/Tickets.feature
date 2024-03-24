Feature: Tickets
    In order to keep Vacation Requests api stable
    As a tester
    I want to make sure that everything works as expected

    Scenario: List Companies
        Given I have a valid token
        Given I make a GET request to /api/companies
        And I set body to
            """
            {
                "customerType": "customer"
            }
            """
        When I receive a response
        And I store response at data[0].id as CompanyId
        Then I expect response should have a status 200

    Scenario: Create A Ticket
        Given I have a valid token
        Given I make a POST request to /api/tickets
        And I set body to
            """
            {
                "title": "Example title",
                "state": "pending-reminder",
                "priority": "normal",
                "contactType": "mail",
                "assignee": "186fc25b-40bd-64a4-0253-481f4f20e974",
                "userType": "employee",
                "companyId": "$S{CompanyId}",
                "visibility": "internal",
                "text": "{\"ops\":[{\"insert\":\"cool\\n\"}]}"
            }
            """
        When I receive a response
        And I store response at ticketId as TicketId
        And I expect response to match a json snapshot ticket-create
        Then I expect response should have a status 200

    Scenario: List Tickets
        Given I have a valid token
        Given I make a GET request to /api/tickets
        When I receive a response
        Then I expect response should have a status 200
        And I expect response to match a json snapshot tickets-list

    Scenario: Get A Ticket With Id
        Given I have a valid token
        Given I make a GET request to /api/tickets/{id}
        And I set path param id to  $S{TicketId}
        When I receive a response
        And I expect response to match a json snapshot get-ticket-$S{TicketId}
        Then I expect response should have a status 200

    Scenario: Update A Ticket With Id
        Given I have a valid token
        Given I make a PUT request to /api/tickets/{id}
        And I set path param id to $S{TicketId}
        And I set body to
            """
            {
                "title": "Example title updated",
                "state": "pending-reminder",
                "priority": "normal",
                "contactType": "mail",
                "assignee": "186fc25b-40bd-64a4-0253-481f4f20e974",
                "userType": "employee",
                "companyId": "$S{CompanyId}",
                "visibility": "internal",
                "text": "{\"ops\":[{\"insert\":\"cool\\n\"}]}"
            }
            """
        When I receive a response
        And I expect response to match a json snapshot update-ticket-$S{TicketId}
        Then I expect response should have a status 200

    Scenario: Delete A Ticket With Id
        Given I have a valid token
        Given I make a DELETE request to /api/tickets/{id}
        And I set path param id to  $S{TicketId}
        When I receive a response
        And I expect response to match a json snapshot delete-ticket-$S{TicketId}
        Then I expect response should have a status 200