Feature: Log time against a project

  Scenario: Log a time entry against a project
    Given there is a project named "Time Tracker"
    When Tom logs a time entry for "2:00" hours on "2016-09-19" against "Time Tracker" with description "First feature"
    Then Tom should have confirmation that his time was logged
