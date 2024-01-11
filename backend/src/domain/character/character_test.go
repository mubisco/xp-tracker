package character

import (
	"reflect"
	"testing"
)

func TestInstanceOf(t *testing.T) {
	characterId := FromEmpty()
	sut := Character{characterId}
	instance := reflect.TypeOf(sut).String()
	if instance != "character.Character" {
		t.Errorf("Not proper type, %v received", instance)
	}
}

func TestValues(t *testing.T) {
	characterId := FromEmpty()
	sut := Character{characterId}
	if sut.Id() != characterId.value() {
		t.Errorf("Not matching ids")
	}
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
