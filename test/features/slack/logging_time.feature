Feature: Logging time against a project using Slack
  In order to easily log my development on a project
  As a Developer
  I want a simple slack interface

  @integration
  @critical
  Scenario: The one where Fran logs time against the project using Slack
    Given Fran is a developer with Slack handle @fran
    And there is a project named "Ingredient Invetory"
    When Fran issues the command "log 3hrs against Ingredient Invetory for Feature: Use an ingredient"
    Then "3:00" hours should have been logged today by Fran against "Ingredient Invetory" for "Feature: Use an ingredient"
    And message saying "Fran logged 3:00 hours against Ingredient Invetory" should have been sent to Slack
