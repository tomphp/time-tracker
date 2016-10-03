Feature: Slack Integration
  In order to easily log my development on a project
  As a Developer
  I want a simple slack interface

  @integration
  #@critical
  Scenario: Logging time against a project
    Given Tom is a developer with Slack handle @tom
    And there is a project named "Time Tracker"
    When Tom issues the command "log 3hrs against Time Tracker for Implementing Slack integration"
    Then "3:00" hours should have been logged today by Tom against "Time Tracker" for "Implementing Slack integration"
    And message saying "Tom logged 3:00 hours against Time Tracker" should have been sent to Slack
