Feature: ContactReports
  In order to keep Contact Reports api stable
  As a tester
  I want to make sure that everything works as expected

  Scenario: Create A Term Of Payment
    Given I have a valid token
    Given I make a POST request to /api/terms-of-payment
    And I set body to
      """
      {
        "percentage1": "33",
        "noOfDays1": "17",
        "name": "Terms Of payment",
        "offerText": "Offer text",
        "orderText": "Order text",
        "invoiceText": "Invoice text"
      }
      """
    When I receive a response
    Then I expect response should have a status 200


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
    Then I expect response should have a status 200
    And I store response at data[0].id as CompanyId

  Scenario: Create A ContactReport
    Given I have a valid token
    Given I make a POST request to /api/contact-reports
    And I set body to
      """
      {
        "subject": "Subject cool",
        "text": "{\"ops\":[{\"insert\":\"cool\\n\"}]}",
        "type": "customer",
        "priority": "low",
        "categoryId": 1,
        "contactType": "mail",
        "initiatedBy": "customer",
        "assignee": "1",
        "companyId": "$S{CompanyId}",
        "createdByEmployee": "185659cd-338b-fd65-0210-daa0e8e10bf6",
        "companyEmployees": [
          "185e329a-1c6b-c40a-015f-3980333ed8b3"
        ],
        "employees": [
          "185659cd-338b-fd65-0210-daa0e8e10bf6"
        ]
      }
      """
    When I receive a response
    And I expect response to match a json snapshot contact-report-create
    Then I expect response should have a status 200


  Scenario: List ContactReports
    Given I have a valid token
    Given I make a GET request to /api/contact-reports
    When I receive a response
    And I expect response to match a json snapshot contact-reports-list
    And I store response at data[0].id as ContactReportId
    Then I expect response should have a status 200

  Scenario: Get A Contact Report With Id
    Given I have a valid token
    Given I make a GET request to /api/contact-reports/{id}
    And I set path param id to  $S{ContactReportId}
    When I receive a response
    And I expect response to match a json snapshot get-contact-report-$S{ContactReportId}
    Then I expect response should have a status 200

  Scenario: Update A Contact Report With Id
    Given I have a valid token
    Given I make a PUT request to /api/contact-reports/{id}
    And I set path param id to  $S{ContactReportId}
    And I set body to
      """
      {
        "subject": "Subject cool",
        "text": "{\"ops\":[{\"insert\":\"cool\\n\"}]}",
        "type": "customer",
        "priority": "low",
        "categoryId": 1,
        "contactType": "mail",
        "initiatedBy": "customer",
        "assignee": "1",
        "companyId": "$S{CompanyId}",
        "createdByEmployee": "185659cd-338b-fd65-0210-daa0e8e10bf6",
        "companyEmployees": [
          "185e329a-1c6b-c40a-015f-3980333ed8b3"
        ],
        "employees": [
          "185659cd-338b-fd65-0210-daa0e8e10bf6"
        ]
      }
      """
    When I receive a response
    Then I expect response should have a status 200
    And I expect response to match a json snapshot update-contact-report-$S{ContactReportId}

  Scenario: Update A Contact Report With Id
    Given I have a valid token
    Given I make a PUT request to /api/contact-reports/{id}
    And I set path param id to  $S{ContactReportId}
    And I set body to
      """
      {
        "subject": "Subject cool",
        "text": "{\"ops\":[{\"insert\":\"cool\\n\"}]}",
        "type": "customer",
        "priority": "low",
        "categoryId": 1,
        "contactType": "mail",
        "initiatedBy": "customer",
        "assignee": "1",
        "companyId": "$S{CompanyId}",
        "createdByEmployee": "185659cd-338b-fd65-0210-daa0e8e10bf6",
        "companyEmployees": [
          "185e329a-1c6b-c40a-015f-3980333ed8b3"
        ],
        "employees": [
          "185659cd-338b-fd65-0210-daa0e8e10bf6"
        ]
      }
      """
    When I receive a response
    And I expect response to match a json snapshot update-contact-report-$S{ContactReportId}
    Then I expect response should have a status 200

  Scenario: Delete A Contact Report With Id
    Given I have a valid token
    Given I make a DELETE request to /api/contact-reports/{id}
    And I set path param id to  $S{ContactReportId}
    When I receive a response
    And I expect response to match a json snapshot delete-contact-report-$S{ContactReportId}
    Then I expect response should have a status 200
