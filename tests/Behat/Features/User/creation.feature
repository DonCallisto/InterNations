@user_creation
Feature: User creation
  In order to create a user
  As a logged user
  I need to visit user creation page and have a role that is allowed to add user

  @ui
  Scenario: User creation as an administrator
    Given There is an administrator user with "admin" username and "admin" password
    When I log in as "admin" with password "admin"
    And I try to create a user
    Then User should be created

  @ui
  Scenario: User creation as non-administrator
    Given There is user with "notadmin" username and "notadmin" password
    When I log in as "notadmin" with password "notadmin"
    And I try to create a user
    Then I should not be allowed to create user