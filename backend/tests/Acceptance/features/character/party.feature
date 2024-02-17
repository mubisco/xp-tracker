Feature:
  In order to create encounters
  As a user
  I want to manage Parties

  Scenario: It creates a party properly
    When a post request is sent to "/api/party" with data
      | ulid             | 01HPWFRKG8X86VCRYVKV2BA0S7 |
      | name             |                  Comando G |
    Then a "200" status code should be received

  Scenario: It should return error when party already exists
    When a post request is sent to "/api/party" with data
      | ulid             | 01HPWFRKG8X86VCRYVKV2BA0S7 |
      | name             |                  Comando G |
    And a post request is sent to "/api/party" with data
      | ulid             | 01HPWFRKG8X86VCRYVKV2BA0S7 |
      | name             |                  Comando G |
    Then a "422" status code should be received
