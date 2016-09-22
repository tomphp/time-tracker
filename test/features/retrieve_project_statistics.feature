Feature: Retrieving project statistics
  In order to learn more about the the progress of a project
  As a User
  I want too see a break down of information

  Scenario: Retrieve total hours spent on a project
    Given there is a project named "Time Tracker"
    And Rob has logged a time entry for "5:30" hours on "2016-09-16" against "Time Tracker"
    And Tom has logged a time entry for "2:00" hours on "2016-09-20" against "Time Tracker"
    And Felix has logged a time entry for "1:00" hours on "2016-09-19" against "Time Tracker"
    When I retrieve the details for "Time Tracker"
    Then I should see that the total hours spent on the project is "8:30"

  Scenario: List all time entries for a project
    Given there is a project named "Time Tracker"
    And the following time entries have been logged against "Time Tracker":
      | user  | date       | time | description              |
      | Tom   | 2016-09-19 | 3:00 | Initial project setup    |
      | Tom   | 2016-09-20 | 2:00 | More work on the project |
      | Felix | 2016-09-21 | 1:00 | Helped Tom a bit         |
    When I retrieve the time entries for "Time Tracker"
    Then I should see these time entries:
      | user  | date       | time | description              |
      | Tom   | 2016-09-19 | 3:00 | Initial project setup    |
      | Tom   | 2016-09-20 | 2:00 | More work on the project |
      | Felix | 2016-09-21 | 1:00 | Helped Tom a bit         |
