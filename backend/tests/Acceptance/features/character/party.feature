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

  Scenario: It should add character to a party
    When a post request is sent to "/api/party" with data
      | ulid             | 01HSNQQWP8F0KJZAHEXHYHYBJZ |
      | name             |                  Comando G |
    And a post request is sent to "/api/character" with data
      | ulid             | 01HJCGHACDM5XTZVCECF88N2KZ |
      | characterName    |               Chindasvinto |
      | experiencePoints |                          0 |
    And a put request is sent to "/api/party/character/add" with data
      | partyUlid        | 01HSNQQWP8F0KJZAHEXHYHYBJZ |
      | characterUlid    | 01HJCGHACDM5XTZVCECF88N2KZ |
    Then a "200" status code should be received

  Scenario: It should fail when wrong partyUlid provided
    When a put request is sent to "/api/party/character/add" with data
      | partyUlid        | A1HSNRJACRTGY3DYGENP89ZDVR |
      | characterUlid    | 01HJCGHACDM5XTZVCECF88N2KZ |
    Then a "400" status code should be received

  Scenario: It should fail when wrong characterUlid provided
    When a put request is sent to "/api/party/character/add" with data
      | partyUlid        | 01HSNRJACRTGY3DYGENP89ZDVR |
      | characterUlid    | A1HJCGHACDM5XTZVCECF88N2KZ |
    Then a "400" status code should be received

  Scenario: It should fail when no party to add character
    And a post request is sent to "/api/character" with data
      | ulid             | 01HJCGHACDM5XTZVCECF88N2KZ |
      | characterName    |               Chindasvinto |
      | experiencePoints |                          0 |
    And a put request is sent to "/api/party/character/add" with data
      | partyUlid        | 01HSNRJACRTGY3DYGENP89ZDVR |
      | characterUlid    | 01HJCGHACDM5XTZVCECF88N2KZ |
    Then a "404" status code should be received

  # Scenario: It should recover party data properly
  #   When a post request is sent to "/api/party" with data
  #     | ulid             | 01HT4SR0YRJE5HFT5NE4V0GHB9 |
  #     | name             |                  Comando G |
  #   And a post request is sent to "/api/character" with data
  #     | ulid             | 01HT4SR4VR5Q6D4QCDE65AA3RE |
  #     | characterName    |               Chindasvinto |
  #     | experiencePoints |                          0 |
  #   And a post request is sent to "/api/character" with data
  #     | ulid             | 01HT4SRHJ0CDHX7CQVDZJ17NA9 |
  #     | characterName    |                    Darling |
  #     | experiencePoints |                        800 |
  #   And a put request is sent to "/api/party/character/add" with data
  #     | partyUlid        | 01HT4SR0YRJE5HFT5NE4V0GHB9 |
  #     | characterUlid    | 01HT4SR4VR5Q6D4QCDE65AA3RE |
  #   And a put request is sent to "/api/party/character/add" with data
  #     | partyUlid        | 01HT4SR0YRJE5HFT5NE4V0GHB9 |
  #     | characterUlid    | 01HT4SRHJ0CDHX7CQVDZJ17NA9 |
  #   Then I should be able to recover party data
  #     | partyUlid        | 01HT4SR0YRJE5HFT5NE4V0GHB9 |
  #     | name             |                  Comando G |

  # Disabled until we know how to raise event consumer in behat tests
  # Scenario: It should fail when character already in party
  #   When a post request is sent to "/api/party" with data
  #     | ulid             | 01HSNQQWP8F0KJZAHEXHYHYBJZ |
  #     | name             |                  Comando G |
  #   When a post request is sent to "/api/party" with data
  #     | ulid             | 01HSP09CMRK0RR0JSXCQK9RYMA |
  #     | name             |                   Equipo A |
  #   And a post request is sent to "/api/character" with data
  #     | ulid             | 01HJCGHACDM5XTZVCECF88N2KZ |
  #     | characterName    |               Chindasvinto |
  #     | experiencePoints |                          0 |
  #   And a put request is sent to "/api/party/character/add" with data
  #     | partyUlid        | 01HSNQQWP8F0KJZAHEXHYHYBJZ |
  #     | characterUlid    | 01HJCGHACDM5XTZVCECF88N2KZ |
  #   And a put request is sent to "/api/party/character/add" with data
  #     | partyUlid        | 01HSP09CMRK0RR0JSXCQK9RYMA |
  #     | characterUlid    | 01HJCGHACDM5XTZVCECF88N2KZ |
  #   Then a "409" status code should be received
