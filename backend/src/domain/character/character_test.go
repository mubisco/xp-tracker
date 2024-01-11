package character

import (
	"reflect"
	"testing"
)

const ULID = "01AN4Z07BY79KA1307SR9X4MV3"

func TestInstanceOf(t *testing.T) {
	sut, _ := dummyCharacter()
	instance := reflect.TypeOf(sut).String()
	if instance != "character.Character" {
		t.Errorf("Not proper type, %v received", instance)
	}
}

func TestErrorWhenWrongUlid(t *testing.T) {
	_, err := NewCharacter("asd", "Chindasvinto")
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
}

func dummyCharacter() (Character, error) {
	return NewCharacter(ULID, "Chindasvinto")
}
