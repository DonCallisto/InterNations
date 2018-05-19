@user_delete
Feature: User delete
  In order to delete a user
  As a logged user
  I need to visit user edit page and have a role that is allowed to delete user

  @ui
  Scenario: User delete as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    And There is a user with "user" username and "user" email
    When I log in as "admin" with password "admin"
    And I try to delete user with "user" username
    Then User with "user" username should be deleted

  @ui
  Scenario: User that belongs to a group delete as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    And There is a user with "user" username and "user" email that is in a group
    When I log in as "admin" with password "admin"
    And I try to delete user with "user" username
    Then User with "user" username should be deleted

  @ui
  Scenario: User delete as non-administrator
    Given There is user with "notadmin" username and "notadmin" password
    And There is a user with "user" username and "user" email
    When I log in as "notadmin" with password "notadmin"
    And I try to delete user with "user" username
    Then I should not be allowed to delete user

  @ui
  Scenario: User try to delete himself
    Given There is an administrator user with "admin" username and "admin" password
    When I log in as "admin" with password "admin"
    And I try to delete user with "admin" username
    Then I should not be allowed to delete user with "admin" username