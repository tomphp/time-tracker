Feature: Helpful feedback in Slack
  In order to be able to log time efficiently
  As a Developer
  I want to have helpful feedback so that I can learn the correct commands.

  Scenario: The one where the user issues an unknown command
    Given Mike has a developer account with email "mike@rgsoftware.com"
    And Mike has a Slack account
    When Mike issues the command "unknown command"
    Then Mike should receive a response message saying "unknown is not a valid command"
    And Mike should receive a list of all valid commands
