Feature:
  In order to create encounters
  As a user
  I want to add a Character

  Scenario: It creates a character properly
    When a post request is sent to "/api/character" with data
      | ulid             | 01HJCGHACDM5XTZVCECF88N2KZ |
      | characterName    |               Chindasvinto |
      | experiencePoints |                          0 |
    Then a "200" status code should be received

  Scenario: It should return error when characterName is missing
    When a post request is sent to "/api/character" with data
      | ulid             | 01HPMKKHFK3D071EQKQ6V4ME1Z |
      | experiencePoints |                          0 |
    Then a "400" status code should be received

  Scenario: It should return error when ulid already exists
    When a post request is sent to "/api/character" with data
      | ulid             | 01HJCGHACDM5XTZVCECF88N2KZ |
      | characterName    |               Chindasvinto |
      | experiencePoints |                          0 |
    And a post request is sent to "/api/character" with data
      | ulid             | 01HJCGHACDM5XTZVCECF88N2KZ |
      | characterName    |               Chindasvinto |
      | experiencePoints |                          0 |
    Then a "422" status code should be received
