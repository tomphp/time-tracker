Feature: Log time against a project

  Scenario: The one where Fran logs some time on the project
    Given there is a project named "Ingredient Inventory"
    When Tom logs a time entry for "2:00" hours on "2016-09-19" against "Ingredient Inventory" with description "Feature: Add new ingredient"
    Then Tom should have confirmation that his time was logged
