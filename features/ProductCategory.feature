  Feature: Product Categories
    In order to keep Product Categories api stable
    As a tester
    I want to make sure that everything works as expected

    Scenario: Create A Unit
        Given I have a valid token
        Given I make a POST request to /api/units
        And I set body to
            """
            {
                "name": "Test Unit",
                "shortName": "TU"
            }
            """
        When I receive a response
        # And I expect response to match a json snapshot version-create
        Then I expect response should have a status 200

    Scenario: List Units
        Given I have a valid token
        Given I make a GET request to /api/units
        When I receive a response
        # And I expect response to match a json snapshot version-list
        And I store response at units.data[0].id as UnitId
        Then I expect response should have a status 200


  Scenario: Create A Product Category
    Given I have a valid token
    Given I make a POST request to /api/product-category
    And I set body to
      """
      {
        "name": "Test Category",
        "defaultUnit": "$S{UnitId}",
        "isDefaultOnOffers": 0
      }
      """
    When I receive a response
    # And I expect response to match a json snapshot period-create
    Then I expect response should have a status 200

  Scenario: List Product Categories
    Given I have a valid token
    Given I make a GET request to /api/product-category
    When I receive a response
    # And I expect response to match a json snapshot period-list
    And I store response at data[0].id as ProductCategoryId
    Then I expect response should have a status 200

  Scenario: Get A Product Category With Id
    Given I have a valid token
    Given I make a GET request to /api/product-category/{id}
    And I set path param id to $S{ProductCategoryId}
    When I receive a response
    # And I expect response to match a json snapshot get-period-$S{ProductCategoryId}
    Then I expect response should have a status 200

  Scenario: Update A Product Category With Id
    Given I have a valid token
    Given I make a PUT request to /api/product-category/{id}
    And I set path param id to $S{ProductCategoryId}
    And I set body to
      """
        {
        "name": "Test Category",
        "defaultUnit": "$S{UnitId}",
        "isDefaultOnOffers": 1
        }
      """
    When I receive a response
# And I expect response to match a json snapshot update-period-$S{ProductCategoryId}
    Then I expect response should have a status 200

  Scenario: Delete A Product Category With Id
    Given I have a valid token
    Given I make a DELETE request to /api/product-category/{id}
    And I set path param id to $S{ProductCategoryId}
    When I receive a response
    # And I expect response to match a json snapshot delete-period-$S{ProductCategoryId}
    Then I expect response should have a status 200