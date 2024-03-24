Feature: TimeTrackers
  In order to keep Vacation Requests api stable
  As a tester
  I want to make sure that everything works as expected

  Scenario: Create A Company
    Given I have a valid token
    Given I make a POST request to /api/companies
    And I set body to
      """
      {
        "companyName": "Acme Inc.",
        "vatId": "1234567890",
        "url": "https://www.acmeinc.com",
        "phone": "555-555-5555",
        "fax": null,
        "type": "premise",
        "customerType": "customer",
        "addressFirst": "123 Main St",
        "city": "Anytown",
        "country": "USA",
        "state": "CA",
        "zip": "12345"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot company-create-for-tracker
    And I store response at company_id as CompanyId
    Then I expect response should have a status 200

  Scenario: Create A Location
    Given I have a valid token
    Given I make a POST request to /api/user/locations
    And I set body to
      """
      {
        "street": "street 1",
        "address": "Address 1",
        "zipCode": "345345",
        "state": "State1",
        "city": "City1",
        "country": "Germany"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot create-user-locations
    Then I expect response should have a status 200

  Scenario: List User Locations
    Given I have a valid token
    Given I make a GET request to /api/user/locations
    When I receive a response
    And I expect response to match a json snapshot list-locations
    And I store response at data[0].id as LocationId
    Then I expect response should have a status 200

  Scenario: Create A User Profile Data
    Given I have a valid token
    Given I make a POST request to /api/user-profile/profile
    And I set body to
      """
      {
        "firstName": "Marcel",
        "lastName": "Rupprecht",
        "birthName": "Marce Rupprechtl",
        "dateOfBirth": "1976-07-22",
        "countryOfBirth": "Germany",
        "placeOfBirth": "fsd",
        "gender": "male",
        "nationality": "german",
        "highestSchoolDegree": "grad",
        "highestEducationLevel": "grad",
        "street": "fsd",
        "houseNumber": "279",
        "secondaryAddress": "",
        "zipCode": "86000",
        "city": "city",
        "state": "state",
        "country": "Germany",
        "phone": "",
        "mobile": "+923150001320",
        "email": "m.rupprecht@docshero.de",
        "childrenData": [],
        "financeDepartmentNumber": "",
        "financeCategory": "",
        "religion": "",
        "religionOfPartner": "",
        "freeAmountChildren": "",
        "freeAmountYearly": "",
        "freeAmountMonthly": "",
        "taxLiability": "",
        "accountOwner": "",
        "iban": "",
        "bic": "",
        "bankName": "",
        "socialSecurityNumber": "",
        "healthInsurance": "",
        "previousHealthInsurance": "",
        "groupPeople": "",
        "contributionGroup": "",
        "accidentInsurance": "",
        "tariffPoints": "",
        "percentageAssociation": "",
        "userId": "186fc40b-55dc-feae-025a-f000fca5d884",
        "password": "1234",
        "confirmPassword": "1234",
        "roles": [
          {
            "id": "TestRole",
            "title": "TestRole",
            "can_register": 1,
            "active": 1,
            "permissions": []
          }
        ]
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I store response at userId as UserId
    And I expect response to match a json snapshot create-user-profile-data
    Then I expect response should have a status 200

  Scenario: Create A User Job
    Given I have a valid token
    Given I make a POST request to /api/user-profile/job
    And I set body to
      """
      {
        "jobTitle": "Developer",
        "jobDescription": "Developer",
        "personalNumber": "1234",
        "jobNumber": "2222",
        "departments": "Administration",
        "contractType": "abc",
        "joinDate": "2023-02-08",
        "exitDate": "2023-02-24",
        "accountingDate": "2023-02-18",
        "isMainJob": true,
        "isOtherJob": false,
        "isEmployeeLeasing": false,
        "locationId": {
          "id": "$S{LocationId}",
          "name": "Lahore"
        },
        "workingDays": null,
        "weeklyHours": 40,
        "totalAnnualLeaves": 23,
        "availableLeaves": 23,
        "remainingLeaves": 23,
        "userId": "$S{UserId}",
        "workHours": [
          {
            "days": [
              "tue"
            ],
            "numOfHours": 8
          },
          {
            "days": [
              "wed"
            ],
            "numOfHours": 8
          },
          {
            "days": [
              "thu"
            ],
            "numOfHours": 8
          },
          {
            "days": [
              "fri"
            ],
            "numOfHours": 8
          }
        ],
        "workHoursArray": [
          {
            "day": "tue",
            "numOfHours": 8
          },
          {
            "day": "wed",
            "numOfHours": 8
          },
          {
            "day": "thu",
            "numOfHours": 8
          },
          {
            "day": "fri",
            "numOfHours": 8
          }
        ]
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot create-user-profile-job
    Then I expect response should have a status 200

  Scenario: Create A Time Tracker
    Given I have a valid token
    Given I make a POST request to /api/time-tracker
    And I set body to
      """
      {
        "date": "2023-04-18",
        "companyId": null,
        "timeTrackerGovernment": {
          "type": "newEntry",
          "description": "description",
          "startTime": "18:00:00",
          "endTime": null,
          "hours": 0
        },
        "timeTrackerCompany": {
          "type": "newEntry",
          "description": "description",
          "startTime": "18:00:00",
          "endTime": null,
          "hours": "10.00",
          "moduleId": null,
          "companyId": "$S{CompanyId}",
          "isGoodwill": false
        },
        "userId": "186fc40b-55dc-feae-025a-f000fca5d884"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot time-tracker-create
    Then I expect response should have a status 200

  Scenario: List Time Trackers
    Given I have a valid token
    Given I make a GET request to /api/time-trackers
    And I set body to
      """
        {
        "date": "2023-04-18",
        "companyId": null,
        "timeTrackerGovernment": {
              "type": "newEntry",
              "description": "description",
              "startTime": "18:00:00",
              "endTime": "24:00:00",
              "hours": 0
        },
        "timeTrackerCompany": {
          "type": "newEntry",
          "description": "description",
          "startTime": "18:00:00",
          "endTime": "24:00:00",
          "hours": "10.00",
          "moduleId": null,
          "companyId": "$S{CompanyId}",
          "isGoodwill": false
        },
        "userId": "186fc40b-55dc-feae-025a-f000fca5d884"
        }
      """
    When I receive a response
    And I expect response to match a json snapshot time-trackers-list
    And I store response at timeTrackerCompany[0].id as timeTrackerCompanyId
    Then I expect response should have a status 200

  Scenario: Create A Performance Record
    Given I have a valid token
    Given I make a POST request to /api/store-performance-records
    And I set body to
      """
      {
      "startDate": "2023-04-12",
      "endDate": "2023-04-20",
      "advisor":"186fc40b-55dc-feae-025a-f000fca5d884"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot performance-record-create
    Then I expect response should have a status 200

  Scenario: List Performance
    Given I have a valid token
    Given I make a GET request to /api/performance-records
    When I receive a response
    And I expect response to match a json snapshot performance-records-list
    And I store response at data[0].id as PerformanceId
    Then I expect response should have a status 200

  Scenario: Update Performance
    Given I have a valid token
    Given I make a PUT request to /api/performance-records/{id}
    And I set path param id to $S{PerformanceId}
    When I receive a response
    And I expect response to match a json snapshot performance-update
    Then I expect response should have a status 200

  Scenario: Delete A Performance With Id
    Given I have a valid token
    Given I make a DELETE request to /api/performance-records/{id}
    And I set path param id to  $S{PerformanceId}
    When I receive a response
    And I expect response to match a json snapshot delete-performance-$S{PerformanceId}
    Then I expect response should have a status 200

  Scenario: Update A Time Tracker Company With Id
    Given I have a valid token
    Given I make a PATCH request to /api/time-tracker-company/{id}
    And I set path param id to $S{timeTrackerCompanyId}
    And I set body to
      """
      {
        "type": "newEntry",
        "description": "description updated",
        "startTime": "18:00:00",
        "endTime": null,
        "hours": "12.00",
        "moduleId": null,
        "companyId": "$S{CompanyId}",
        "isGoodwill": 1
      }
      """
    When I receive a response
    And I expect response to match a json snapshot update-time-tracker-company-$S{timeTrackerCompanyId}
    Then I expect response should have a status 200

  Scenario: Delete A Time Tracker Company With Id
    Given I have a valid token
    Given I make a DELETE request to /api/tracker-company/{id}
    And I set path param id to  $S{timeTrackerCompanyId}
    When I receive a response
    And I expect response to match a json snapshot delete-time-tracker-company-$S{timeTrackerCompanyId}
    Then I expect response should have a status 200
