@user_creation
Feature: User creation (API)
  In order to create a user
  As a logged user
  I need to take advantage of API and have a role that is allowed to add user

  @api
  Scenario: User creation as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    When I log in as user with "admin" username
    And I try to create a user
    Then User should be created

  @api
  Scenario: User creation as non-administrator
    Given There is user with "notadmin" username and "notadmin" password
    When I log in as user with "notadmin" username
    And I try to create a user
    Then I should not be allowed to create user