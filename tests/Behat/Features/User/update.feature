@user_update
Feature: User update
  In order to update a user
  As a logged user
  I need to visit user update page and have a role that is allowed to update user

  @ui
  Scenario: User update as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    And There is a user with "user" username and "user@user.com" email
    When I log in as "admin" with password "admin"
    And I try to update user with "user" username changing email to "user@modified.com"
    Then User with "user" username email should be updated with "user@modified.com"

  @ui
  Scenario: User update as non-administrator
    Given There is user with "notadmin" username and "notadmin" password
    And There is a user with "user" username and "user@user.com" email
    When I log in as "notadmin" with password "notadmin"
    And I try to update user with "user" username changing email to "user@modified.com"
    Then I should not be allowed to update user