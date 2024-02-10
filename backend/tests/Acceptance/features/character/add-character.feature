Feature:
    In order to create encounters
    As a user
    I want to add a Character

    Scenario: It creates a character properly
        When a post request is sent to "/api/character" with data
        | ulid             | 01HJCGHACDM5XTZVCECF88N2KZ |
        | characterName    |               Chindasvinto |
        | playerName       |                      Pousa |
        | experiencePoints |                          0 |
        | maxHitpoints     |                         25 |
      Then a "200" status code should be received

    Scenario: It should return error when characterName is missing
        When a post request is sent to "/api/character" with data
        | playerName       |        Pousa |
        | experiencePoints |            0 |
        | maxHitpoints     |           25 |
      Then a "400" status code should be received
