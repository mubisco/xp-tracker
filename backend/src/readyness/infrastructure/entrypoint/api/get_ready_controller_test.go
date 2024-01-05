package api

import (
	"net/http"
	"net/http/httptest"
	"testing"
)

func TestInvoke(t *testing.T) {
	response := httptest.NewRecorder()
	request := httptest.NewRequest("GET", "/", nil)
	Ready(response, request)
	if response.Code != http.StatusOK {
		t.Errorf("Expected response code %d. Got %d\n", http.StatusNotFound, response.Code)
	}
}
