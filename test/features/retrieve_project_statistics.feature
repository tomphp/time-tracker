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
