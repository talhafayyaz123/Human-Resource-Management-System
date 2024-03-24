Feature: Projects
  In order to keep Projects api stable
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

  Scenario: Create A Project
    Given I have a valid token
    Given I make a POST request to /api/project-management/projects
    And I set body to
      """
      {
        "projectId": "10001",
        "name": "test project",
        "description": "{\"ops\":[{\"attributes\":{\"underline\":true,\"italic\":true,\"bold\":true},\"insert\":\"hello\"},{\"insert\":\"\\n\"}]}",
        "status": "in-work",
        "plannedStartDate": "2023-04-14",
        "actualStartDate": "2023-04-15",
        "earliestStartDate": "2023-04-12",
        "actualFinishedDate": "2023-04-25",
        "expectedFinishedDate": "2023-04-10",
        "plannedFinishedDate": "2023-04-12",
        "companyId": "$S{CompanyId}",
        "employeeId": "186fc25b-40bd-64a4-0253-481f4f20e974",
        "estimatedTime": "12",
        "neededTime": "22",
        "comments": ""
      }
      """
    When I receive a response
    And I expect response to match a json snapshot project-create
    Then I expect response should have a status 200

  Scenario: List Projects
    Given I have a valid token
    Given I make a GET request to /api/project-management/projects
    When I receive a response
    And I expect response to match a json snapshot projects-list
    And I store response at data[0].id as ProjectId
    Then I expect response should have a status 200

  Scenario: Get A Project With Id
    Given I have a valid token
    Given I make a GET request to /api/project-management/projects/{id}
    And I set path param id to $S{ProjectId}
    When I receive a response
    And I expect response to match a json snapshot get-project-$S{ProjectId}
    Then I expect response should have a status 200

  Scenario: Update A Project With Id
    Given I have a valid token
    Given I make a PUT request to /api/project-management/projects/{id}
    And I set path param id to  $S{ProjectId}
    And I set body to
      """
      {
        "projectId": "10004",
        "name": "test project 2",
        "description": "{\"ops\":[{\"attributes\":{\"underline\":true,\"italic\":true,\"bold\":true},\"insert\":\"hello world\"},{\"insert\":\"\\n\"}]}",
        "status": "in-work",
        "plannedStartDate": "2023-04-14",
        "actualStartDate": "2023-04-15",
        "earliestStartDate": "2023-04-12",
        "actualFinishedDate": "2023-04-25",
        "expectedFinishedDate": "2023-04-10",
        "plannedFinishedDate": "2023-04-12",
        "companyId": "$S{CompanyId}",
        "employeeId": "186fc25b-40bd-64a4-0253-481f4f20e974",
        "estimatedTime": "12",
        "neededTime": "22",
        "comments": ""
      }
      """
    When I receive a response
    And I expect response to match a json snapshot update-project-$S{ProjectId}
    Then I expect response should have a status 200

  Scenario: Create A Milestone
    Given I have a valid token
    Given I make a POST request to /api/project-management/milestones
    And I set body to
      """
      {
        "milestoneId": "milestone",
        "status": "new",
        "description": "{\"ops\":[{\"insert\":\"asad\\n\"}]}",
        "name": "test new",
        "plannedStartDate": "2023-04-13",
        "actualStartDate": "2023-04-26",
        "earliestStartDate": "2023-04-18",
        "actualFinishedDate": "2023-04-10",
        "expectedFinishedDate": "2023-04-10",
        "plannedFinishedDate": "2023-04-26",
        "projectId": "$S{ProjectId}",
        "comments": ""
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot project-milestone-create
    Then I expect response should have a status 200

  Scenario: List Milestones
    Given I have a valid token
    Given I make a GET request to /api/project-management/milestones
    When I receive a response
    And I expect response to match a json snapshot milestones-list
    And I store response at data[0].id as MilestoneId
    Then I expect response should have a status 200

  Scenario: Get Milestones By Project
    Given I have a valid token
    Given I make a GET request to /api/project-management/milestones/by-project/{project_id}
    And I set path param project_id to $S{ProjectId}
    When I receive a response
    And I expect response to match a json snapshot get-milestone-by-project-$S{ProjectId}
    Then I expect response should have a status 200

  Scenario: Update A Milestone With Id
    Given I have a valid token
    Given I make a PUT request to /api/project-management/milestones/{id}
    And I set path param id to $S{MilestoneId}
    And I set body to
      """
      {
        "milestoneId": "updated milestone",
        "status": "new",
        "description": "{\"ops\":[{\"insert\":\"updated\\n\"}]}",
        "name": "test new",
        "plannedStartDate": "2023-04-13",
        "actualStartDate": "2023-04-26",
        "earliestStartDate": "2023-04-18",
        "actualFinishedDate": "2023-04-10",
        "expectedFinishedDate": "2023-04-10",
        "plannedFinishedDate": "2023-04-26",
        "projectId": "$S{ProjectId}",
        "comments": "Comment"
      }
      """
    When I receive a response
    And I expect response to match a json snapshot update-milestone-$S{MilestoneId}
    Then I expect response should have a status 200

  Scenario: Create A Project Task
    Given I have a valid token
    Given I make a POST request to /api/project-management/tasks
    And I set body to
      """
      {
        "taskId": "Task",
        "name": "new task",
        "description": "{\"ops\":[{\"insert\":\"updated\\n\"}]}",
        "status": "blocked",
        "milestoneId": "$S{MilestoneId}",
        "priority": "medium",
        "employeeId": "185659cd-338b-fd65-0210-daa0e8e10bf6"
      }
      """
    When I receive a response
    Then I expect response time should be less than 3000 ms
    And I expect response to match a json snapshot project-task-create
    Then I expect response should have a status 200

  Scenario: List Tasks
    Given I have a valid token
    Given I make a GET request to /api/project-management/tasks
    When I receive a response
    And I expect response to match a json snapshot tasks-list
    Then I expect response should have a status 200
    And I store response at tasks.data[0].id as TaskId

  Scenario: Update A Project Task With Id
    Given I have a valid token
    Given I make a PUT request to /api/project-management/tasks/{id}
    And I set path param id to $S{TaskId}
    And I set body to
      """
      {
        "taskId": "Updated Task",
        "name": "updated task",
        "description": "{\"ops\":[{\"insert\":\"updated task\\n\"}]}",
        "status": "blocked",
        "milestoneId": "$S{MilestoneId}",
        "priority": "medium"
      }
      """
    When I receive a response
    And I expect response to match a json snapshot update-task-$S{MilestoneId}
    Then I expect response should have a status 200

  Scenario: Get Task By Id
    Given I have a valid token
    Given I make a GET request to /api/project-management/tasks
    And I set path param project_id to $S{TaskId}
    When I receive a response
    And I expect response to match a json snapshot get-task-$S{TaskId}
    Then I expect response should have a status 200

  Scenario: Get Backlog Tasks
    Given I have a valid token
    Given I make a GET request to /api/project-management/tasks/backlog
    When I receive a response
    And I expect response to match a json snapshot backlog-tasks-list
    Then I expect response should have a status 200

  Scenario: Delete A Task With Id
    Given I have a valid token
    Given I make a DELETE request to /api/project-management/tasks/{id}
    And I set path param id to $S{TaskId}
    When I receive a response
    And I expect response to match a json snapshot delete-task-$S{TaskId}
    Then I expect response should have a status 200

  Scenario: Delete A Milestone With Id
    Given I have a valid token
    Given I make a DELETE request to /api/project-management/milestones/{id}
    And I set path param id to $S{MilestoneId}
    When I receive a response
    And I expect response to match a json snapshot delete-milestone-$S{MilestoneId}
    Then I expect response should have a status 200

  Scenario: Delete A Project With Id
    Given I have a valid token
    Given I make a DELETE request to /api/project-management/projects/{id}
    And I set path param id to  $S{ProjectId}
    When I receive a response
    And I expect response to match a json snapshot delete-project-$S{ProjectId}
    Then I expect response should have a status 200