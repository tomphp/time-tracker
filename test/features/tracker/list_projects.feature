Feature: List all projects

  Scenario: Retrieving a list of all active projects
    Given there is a project named "Time Tracker"
    And there is a project named "Lime Cracker"
    When I retrieve a list of all active projects
    Then I should get the following projects:
      | Time Tracker |
      | Lime Cracker |
