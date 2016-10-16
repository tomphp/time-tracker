Feature: Linking a Slack Username to a Tracker Developer
  In order to be able to log time efficiently
  As a Developer
  I want to be able to link my Slack account to my Tracker account

  Scenario: The one where Mike links his Slack account to his Developer account
    Given Mike has a developer account with email "mike@rgsoftware.com"
    And Mike has a Slack account with slack handle @mike
    When Mike issues the command "link to account mike@rgsoftware.com"
    Then Mike should receive a response message saying "Hi Mike, your account has been successfully linked."
