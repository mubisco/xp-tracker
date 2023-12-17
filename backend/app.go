package main

import (
	"log"
	ReadyController "mubisco/xptracker/src/readyness/infrastructure/entrypoint/api"
	"net/http"
	"time"

	"github.com/go-chi/chi/v5"
	"github.com/go-chi/chi/v5/middleware"
)

func main() {
	r := chi.NewRouter()
	r.Use(middleware.RequestID)
	r.Use(middleware.RealIP)
	r.Use(middleware.Logger)
	r.Use(middleware.Recoverer)
	r.Use(middleware.Timeout(60 * time.Second))

	r.Get("/", ReadyController.Invoke)
	err := http.ListenAndServe(":5000", r)
	if err != nil {
		log.Fatalf("Fatal error building base server. Err: %s", err)
	}
}
