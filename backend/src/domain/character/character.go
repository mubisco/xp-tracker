package character

// func NewCharacter (ulid string, name string, actualXp int, maxHp int) (Character, error) {
func NewCharacter(ulid string, name string, actualXp int) (Character, error) {
	characterId, error := NewCharacterId(ulid)
	if error != nil {
		return NullCharacter(), error
	}
	experience, expError := NewExperience(actualXp)
	if expError != nil {
		return NullCharacter(), expError
	}
	return Character{characterId, name, experience}, nil
}

func NullCharacter() Character {
	emptyXp, _ := NewExperience(0)
	return Character{FromEmpty(), "", emptyXp}
}

type Character struct {
	characterId CharacterId
	name        string
	experience  Experience
}

func (c *Character) Id() string {
	return c.characterId.value()
}

func (c *Character) Name() string {
	return c.name
}

func (c *Character) Level() int {
	return c.experience.Level()
}
