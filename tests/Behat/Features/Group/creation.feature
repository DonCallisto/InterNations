@group_creation
Feature: Group creation
  In order to create a group
  As a logged user
  I need to visit group creation page and have a role that is allowed to create a group

  @ui
  Scenario: Group creation as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    When I log in as "admin" with password "admin"
    And I try to create a group
    Then Group should be created

  @ui
  Scenario: User creation as non-administrator
    Given There is user with "notadmin" username and "notadmin" password
    When I log in as "notadmin" with password "notadmin"
    And I try to create a group
    Then I should not be allowed to create a group