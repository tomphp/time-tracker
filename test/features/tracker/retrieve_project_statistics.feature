Feature: Retrieving project statistics
  In order to learn more about the the progress of a project
  As a User
  I want too see a break down of information

  Scenario: The one where Betty checks the  total hours spent on the project
    Given there is a project named "Ingredient Inventory"
    And Rob has logged a time entry for "5:30" hours on "2016-09-16" against "Ingredient Inventory"
    And Tom has logged a time entry for "2:00" hours on "2016-09-20" against "Ingredient Inventory"
    And Felix has logged a time entry for "1:00" hours on "2016-09-19" against "Ingredient Inventory"
    When Betty retrieves the details for "Ingredient Inventory"
    Then she should see that the total hours spent on the project is "8:30"

  Scenario: The one where Betty lists all logged time entries for the project
    Given there is a project named "Ingredient Inventory"
    And the following time entries have been logged against "Ingredient Inventory":
      | developer | date       | time | description                                                 |
      | Fran      | 2016-09-19 | 3:00 | Feature: Add Ingredient                                     |
      | Fran      | 2016-09-20 | 2:00 | Feature: Set order threshold for an ingredient              |
      | Mike      | 2016-09-21 | 1:00 | Fix Bug: Add Ingredient fail with special chars in the name |
    When I retrieve the time entries for "Ingredient Inventory"
    Then I should see these time entries:
      | developer | date       | time | description                                                 |
      | Fran      | 2016-09-19 | 3:00 | Feature: Add Ingredient                                     |
      | Fran      | 2016-09-20 | 2:00 | Feature: Set order threshold for an ingredient              |
      | Mike      | 2016-09-21 | 1:00 | Fix Bug: Add Ingredient fail with special chars in the name |
