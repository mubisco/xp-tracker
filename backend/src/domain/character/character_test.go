package character

import (
	"testing"
)

const ULID = "01AN4Z07BY79KA1307SR9X4MV3"

func TestErrorWhenWrongUlid(t *testing.T) {
	_, err := NewCharacter("asd", "Chindasvinto", 1)
	if err == nil {
		t.Errorf("Should return error when wrong ULID")
	}
}

func TestValues(t *testing.T) {
	sut, _ := dummyCharacter()
	if sut.Id() != ULID {
		t.Errorf("Not matching ids")
	}
	if sut.Name() != "Chindasvinto" {
		t.Errorf("Character name not valid!!!")
	}
  if sut.Level() != 2 {
		t.Errorf("Level not valid!!!")
  }
}

func dummyCharacter() (Character, error) {
	return NewCharacter(ULID, "Chindasvinto", 300)
}
