package api

import (
	"encoding/json"
	"log"
	"net/http"
)

func Invoke(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type", "application/json")
	resp := make(map[string]bool)
	resp["ready"] = true
	jsonResp, err := json.Marshal(resp)
	if err != nil {
		log.Fatalf("Error happened in JSON marshal. Err: %s", err)
	}
	_, writeErr := w.Write(jsonResp)
	if writeErr != nil {
		log.Fatalf("Fatal error writing response. Err: %s", err)
	}
}
