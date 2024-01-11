package character

import (
	"testing"

	"github.com/oklog/ulid"
)

func TestFromEmpty(t *testing.T) {
	sut := FromEmpty()
	testUlid := sut.value()
	_, err := ulid.Parse(testUlid)
	if err != nil {
		t.Errorf("Ulid from empty cannot be built")
	}
}

func TestShouldReturnValidUlid(t *testing.T) {
	expectedValue := "01AN4Z07BY79KA1307SR9X4MV3"
	sut, err := NewCharacterId(expectedValue)
	if err != nil {
		t.Errorf("Should not fail when valid id")
	}
	receivedValue := sut.value()
	if receivedValue != expectedValue {
		t.Errorf("Values dont match, expected %s, received %s", expectedValue, receivedValue)
	}
}

func TestShouldFailWhenWrongFormat(t *testing.T) {
	_, err := NewCharacterId("01AN4Z07BY79KA1307SR9X4MV")
	if err == nil {
		t.Errorf("Must fail when wrong values")
	}
}
