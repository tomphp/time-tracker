Feature: Slack security

  @e2e-only
  Scenario: The one where an invalid slack token
    When Slack sends a command with an invalid token
    Then A forbidden response should be returned
