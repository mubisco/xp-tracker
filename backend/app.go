package main

import (
	"log"
	BasicController "mubisco/xptracker/src/readyness/infrastructure/entrypoint/api"
  CharacterController "mubisco/xptracker/src/infrastructure/entrypoint/api/character"
	"net/http"
	"time"

	"github.com/go-chi/chi/v5"
	"github.com/go-chi/chi/v5/middleware"
)

func main() {
	r := setupRouter()
	err := http.ListenAndServe(":5000", r)
	if err != nil {
		log.Fatalf("Fatal error building base server. Err: %s", err)
	}
}

func setupRouter() chi.Router {
	r := chi.NewRouter()
	r.Use(middleware.RequestID)
	r.Use(middleware.RealIP)
	r.Use(middleware.Logger)
	r.Use(middleware.Recoverer)
	r.Use(middleware.Timeout(60 * time.Second))

  r.Route("/character", func(r chi.Router) {
    r.Post("/", CharacterController.AddCharacter)
    r.Get("/all", CharacterController.GetCharacters)
  })

	r.Get("/health", BasicController.Ready)
	r.Get("/", BasicController.NotFound)
	return r
}
