@group_creation
Feature: Group creation (API)
  In order to create a group
  As a logged user
  I need take advantage of API and have a role that is allowed to create a group

  @api
  Scenario: Group creation as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    When I log in as user with "admin" username
    And I try to create a group
    Then Group should be created

  @api
  Scenario: User creation as non-administrator
    Given There is user with "notadmin" username and "notadmin" password
    When I log in as user with "notadmin" username
    And I try to create a group
    Then I should not be allowed to create a group