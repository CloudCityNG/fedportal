@admin_interface
@admin_interface_login
Feature: The admin interface login
  As a member of staff and a member of the administrator
  I should be able to view the admin login interface page
  And enter my credentials
  So I can perform administrator tasks

  Scenario: Login as super user
    Given There is user in the system
    And User is super user
    And I am at the admin login page
    When I enter my username
    And I enter my password
    And I click the login button
    Then Session is created with user data
    And User is redirected to admin home page

  Scenario: Login as user who has capability to view admin interface
    Given There is user in the system
    And User has capability to view admin interface page
    And I am at the admin login page
#    multiple steps
    When I enter my credentials and submit login form
    Then Session is created with user data
    Then Session is created with user capability data
    And User is redirected to admin home page

  Scenario: Login as user who is neither a super user nor has capability to view admin interface
    Given There is user in the system
    And User is neither a super user not has capability to view admin interface page
    And I am at the admin login page
#    multiple steps
    When I enter my credentials and submit login form
    Then Session is created with user data
    And I am redirected to login page
    And And shown message that I do not have sufficient privilege


  Scenario: Login - username not in system
    Given I am at the admin login page
#    multiple steps
    When I enter my credentials and submit login form
    Then I am redirected to login page
    And And shown message that username/password does not exist

  Scenario: Login - username in system, but password not in system
    Given There is user in the system
    And I am at the admin login page
#    multiple steps
    When I enter my credentials and submit login form
    Then I am redirected to login page
    And And shown message that username/password does not exist
