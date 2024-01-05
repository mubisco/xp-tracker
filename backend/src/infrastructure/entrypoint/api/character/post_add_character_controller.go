package character

import (
	"bytes"
	"encoding/json"
	"io"
	"log"
	Usecase "mubisco/xptracker/src/application/character/command"
	"net/http"
)

func AddCharacter(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type", "application/json")
	command, err := parseRequest(r)
	if err != nil {
		log.Fatalf("Fatal error parsing response. Err: %s", err)
		w.WriteHeader(http.StatusBadRequest)
		return
	}
	Usecase.Invoke(command)
}

func parseRequest(r *http.Request) (Usecase.AddCharacterCommand, error) {
	buf := &bytes.Buffer{}
	var bodyContent Usecase.AddCharacterCommand
	_, err := io.Copy(buf, r.Body)
	if err != nil {
		return bodyContent, err
	}
	parseErr := json.Unmarshal(buf.Bytes(), &bodyContent)
	if parseErr != nil {
		return bodyContent, err
	}
	return bodyContent, nil
}
