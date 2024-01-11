package character

import (
	"reflect"
	"testing"
)
const ULID = "01AN4Z07BY79KA1307SR9X4MV3";

func TestInstanceOf(t *testing.T) {
	sut := dummyCharacter()
	instance := reflect.TypeOf(sut).String()
	if instance != "character.Character" {
		t.Errorf("Not proper type, %v received", instance)
	}
}

func TestValues(t *testing.T) {
	sut := dummyCharacter()
	if sut.Id() != ULID {
		t.Errorf("Not matching ids")
	}
}

func dummyCharacter() Character {
	characterId, _ := NewCharacterId(ULID)
	return Character{characterId}
}

// func TestInvoke(t *testing.T) {
// if (sut.Id() != string(characterId.value())) {
// t.Errorf("Not matching ids")
// }
//   response := httptest.NewRecorder()
//   request := httptest.NewRequest("GET", "/", nil)
//   Ready(response, request)
//   if response.Code != http.StatusOK {
//     t.Errorf("Expected response code %d. Got %d\n", http.StatusNotFound, response.Code)
//   }
// }
