Feature: List all projects

  Scenario: The one where Betty gets a list of all projects
    Given there is a project named "Ingredient Inventory"
    And there is a project named "Route Planner"
    When Betty retrieves a list of all active projects
    Then she should get the following projects:
      | Ingredient Inventory |
      | Route Planner        |
