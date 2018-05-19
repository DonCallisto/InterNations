@group_api_remove_user
Feature: Remove user from a group
  In order to remove a user from a group
  As a logged user
  I need to take advantage of API and have a role that is allowed to remove user from group

  @api
  Scenario: Remove user from a group as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    And There is a user with "username" username and "email" email in a group with "group" name
    When I log in as user with "admin" username
    And I try to remove user with "username" username from group with "group" name
    Then User with "username" username should be removed from group with "group" name

  @api
  Scenario: Remove user from a group as a non-administrator
    Given There is user with "notadmin" username and "notadmin" password
    And There is a user with "username" username and "email" email in a group with "group" name
    When I log in as user with "notadmin" username
    And I try to remove user with "username" username from group with "group" name
    Then I should not be allowed to remove user from the group

  @api
  Scenario: Try to remove user from group that not contains the user
    Given There is an administrator user with "admin" username and "admin" password
    And There is a user with "username" username and "email" email
    And There is a group with "group" name and no users
    When I log in as user with "admin" username
    And I try to remove user with "username" username from group with "group" name
    Then I should receive and error