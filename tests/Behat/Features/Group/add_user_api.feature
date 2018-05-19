@group_api_add_user
Feature: Add user to a group
  In order to add a user to a group
  As a logged user
  I need to take advantage of API and have a role that is allowed to add user a group

  @api
  Scenario: Add user to a group as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    And There is a user with "username" username and "email" email
    And There is a group with "group" name and no users
    When I log in as user with "admin" username
    And I try to add user with "username" username to group with "group" name
    Then User with "username" username should be added to group with "group" name

  @api
  Scenario: Add user to a group as a non-administrator
    Given There is user with "notadmin" username and "notadmin" password
    And There is a user with "username" username and "email" email
    And There is a group with "group" name and no users
    When I log in as user with "notadmin" username
    And I try to add user with "username" username to group with "group" name
    Then I should not be allowed to add user to the group

  @api
  Scenario: Try to add user to a group that already contains the user
    Given There is an administrator user with "admin" username and "admin" password
    And There is a user with "username" username and "email" email in a group with "group" name
    When I log in as user with "admin" username
    And I try to add user with "username" username to group with "group" name
    Then I should be aware that operation didn't taken place