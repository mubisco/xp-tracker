package character

// func NewCharacter (ulid string, name string, actualXp int, maxHp int) (Character, error) {
func NewCharacter(ulid string, name string) (Character, error) {
	characterId, error := NewCharacterId(ulid)
	if error != nil {
		return Character{FromEmpty(), ""}, error
	}
	return Character{characterId, name}, nil
}

type Character struct {
	characterId CharacterId
	name string
}

func (c *Character) Id() string {
	return c.characterId.value()
}

func (c *Character) Name() string {
	return c.name
}
