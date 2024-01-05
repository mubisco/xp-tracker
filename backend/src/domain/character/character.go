package character

func NewCharacter (ulid string, name string, actualXp int, maxHp int) (Character, error) {
}

type Character struct {
  characterId CharacterId
}

func (c *Character) id() string {
	return c.characterId.value()
}
