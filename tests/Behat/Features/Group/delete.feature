@group_delete
Feature: Group delete
  In order to delete a group
  As a logged user
  I need to visit group list page and have a role that is allowed to delete a group

  @ui
  Scenario: Group without users delete as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    And There is a group with "group" name and no users
    When I log in as "admin" with password "admin"
    And I try to delete group with "group" name
    Then Group with "group" name should be deleted

  @ui
  Scenario: Group with users delete as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    And There is a group with "group" name and at least one user
    When I log in as "admin" with password "admin"
    And I try to delete group with "group" name
    Then Group with "group" name should not be deleted

  @ui
  Scenario: Group without users delete as an non-administrator
    Given There is user with "notadmin" username and "notadmin" password
    And There is a group with "group" name and no users
    When I log in as "notadmin" with password "notadmin"
    And I try to delete group with "group" name
    Then I should not be allowed to delete group