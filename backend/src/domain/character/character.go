package character

// func NewCharacter (ulid string, name string, actualXp int, maxHp int) (Character, error) {
func NewCharacter (ulid string) (Character, error) {
	characterId, error := NewCharacterId(ulid)
  if error != nil {
    return Character {FromEmpty()}, error
  }
  return Character{characterId}, nil
}

type Character struct {
  characterId CharacterId
}

func (c *Character) Id() string {
	return c.characterId.value()
}
