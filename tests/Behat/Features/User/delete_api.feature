@user_delete
Feature: User delete (API)
  In order to delete a user
  As a logged user
  I need to take advantage of API and have a role that is allowed to delete user

  @api
  Scenario: User delete as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    And There is a user with "user" username and "user" email
    When I log in as user with "admin" username
    And I try to delete user with "user" username
    Then User with "user" username should be deleted

  @api
  Scenario: User that belongs to a group delete as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    And There is a user with "user" username and "user" email that is in a group
    When I log in as user with "admin" username
    And I try to delete user with "user" username
    Then User with "user" username should be deleted

  @api
  Scenario: User delete as non-administrator
    Given There is user with "notadmin" username and "notadmin" password
    And There is a user with "user" username and "user" email
    When I log in as user with "notadmin" username
    And I try to delete user with "user" username
    Then I should not be allowed to delete user