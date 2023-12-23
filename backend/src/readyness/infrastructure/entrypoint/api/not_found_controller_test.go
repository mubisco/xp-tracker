package api

import (
  "net/http"
  "net/http/httptest"
  "testing"
)

func TestNotFound(t *testing.T) {
  response := httptest.NewRecorder()
  request := httptest.NewRequest("GET", "/", nil)
  NotFound(response, request)
  if response.Code != http.StatusNotFound {
    t.Errorf("Expected response code %d. Got %d\n", http.StatusNotFound, response.Code)
  }
}
