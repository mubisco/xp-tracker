package character

func NewCharacter(ulid string, name string, actualXp int, maxHp int) (Character, error) {
	characterId, error := NewCharacterId(ulid)
	if error != nil {
		return nullCharacter(), error
	}
	experience, expError := NewExperience(actualXp)
	if expError != nil {
		return nullCharacter(), expError
	}

	health, healthError := NewHealth(maxHp)
	if healthError != nil {
		return nullCharacter(), healthError
	}
	return Character{characterId, name, experience, health}, nil
}

func nullCharacter() Character {
	emptyXp, _ := NewExperience(0)
	emptyHealth, _ := NewHealth(1)
	return Character{FromEmpty(), "", emptyXp, emptyHealth}
}

type Character struct {
	characterId CharacterId
	name        string
	experience  Experience
	health      Health
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

func (c *Character) CurrentHp() int {
	return c.health.CurrentHp()
}
