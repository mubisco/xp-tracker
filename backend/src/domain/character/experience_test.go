package character

import (
	// "reflect"
	"testing"
)

//
// func TestInstanceOf(t *testing.T) {
// 	sut, _ := NewExperience(1)
// 	instance := reflect.TypeOf(sut).String()
// 	if instance != "character.Experience" {
// 		t.Errorf("Not proper type, %v received", instance)
// 	}
// }

func TestShouldReturnErrorWhenZero(t *testing.T) {
	_, err := NewExperience(-1)
	if err != nil {
		t.Errorf("Values below Zero experience should throw error")
	}
}

func TestShouldReturnProperLevel(t *testing.T) {
	sut, _ := NewExperience(0)
	if sut.Level() != 1 {
		t.Errorf("Level must be 1 returned %d", sut.Level())
	}
	if sut.NextLevelXpRequired() != 300 {
		t.Errorf("Should return 300 for next level, %d returned", sut.NextLevelXpRequired())
	}
}

func TestShouldReturnProperLevelWithAnotherValue(t *testing.T) {
	sut, _ := NewExperience(300)
	if sut.Level() != 2 {
		t.Errorf("Level must be 2 returned %d", sut.Level())
	}
	if sut.NextLevelXpRequired() != 900 {
		t.Errorf("Should return 900 for next level, %d returned", sut.NextLevelXpRequired())
	}
}

func TestShouldReturnProperLevelWithAThirdValue(t *testing.T) {
	sut, _ := NewExperience(355100)
	if sut.Level() != 20 {
		t.Errorf("Level must be 20 returned %d", sut.Level())
	}
}

func TestShouldAddAnotherExperienceProperly(t *testing.T) {
	sut, _ := NewExperience(175)
	anotherSut, _ := NewExperience(125)
	result := sut.Add(anotherSut)
	if result.Level() != 2 {
		t.Errorf("Level must be 2 returned %d", sut.Level())
	}
}

func TestShouldSplitProperly(t *testing.T) {
	sut, _ := NewExperience(200)
	result := sut.Split(4)
	if result.currentXp != 50 {
		t.Errorf("Experience not splitted properly")
	}
}
