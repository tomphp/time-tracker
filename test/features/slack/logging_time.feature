Feature: Logging time against a project using Slack
  In order to easily log my development on a project
  As a Developer
  I want a simple slack interface

  @integration
  @critical
  Scenario: The one where Fran logs time against the project using Slack
    Given Fran has a developer account with email "fran@rgsoftware.com"
    And Fran has a Slack account
    And Fran has linked her slack user to "fran@rgsoftware.com"
    And there is a project named "Ingredient Inventory"
    When Fran issues the command "log 3hrs against Ingredient Inventory for Feature: Use an ingredient"
    Then "3:00" hours should have been logged today by Fran against "Ingredient Inventory" for "Feature: Use an ingredient"
    And Fran should receive a response message saying "Fran logged 3h against Ingredient Inventory"

  @integration
  Scenario: The one where the user enters an invalid project name
    Given Fran has a developer account with email "fran@rgsoftware.com"
    And Fran has a Slack account
    And Fran has linked her slack user to "fran@rgsoftware.com"
    When Fran issues the command "log 3hrs against Unknown Project for Trying to break things"
    Then Fran should receive a response message saying "Project Unknown Project was not found."

  Scenario: The one where the user enters an invalid log command
    Given Fran has a developer account with email "fran@rgsoftware.com"
    And Fran has a Slack account
    And Fran has linked her slack user to "fran@rgsoftware.com"
    When Fran issues the command "log with arguments which make now sense"
    Then Fran should receive a response message saying "Invalid log command"
    And Fran should receive an extended reponse message saying "Format: log [time] against [project] for [description]"
