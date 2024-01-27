package character

import (
	"errors"
)

var tresholds = []int{0, 300, 900, 2700, 6500, 14000, 23000, 34000, 48000, 64000, 85000, 100000, 120000, 140000, 165000, 195000, 225000, 265000, 305000, 355000}

func NewExperience(currentXp int) (Experience, error) {
  if currentXp < 0 {
    error := errors.New("Experience should be zero or greater")
    return Experience{1}, error
  }
	return Experience{currentXp}, nil
}

type Experience struct {
	currentXp int
}

func (x *Experience) Level() int {
	for index, maxTreshold := range tresholds {
		if x.currentXp < maxTreshold {
			return index
		}
	}
	return 20
}

func (x *Experience) NextLevelXpRequired() int {
	for _, maxTreshold := range tresholds {
		if x.currentXp < maxTreshold {
			return maxTreshold
		}
	}
	return 20
}

func (x *Experience) Add(another Experience) Experience {
	return Experience{x.currentXp + another.currentXp}
}

func (x *Experience) Split(between int) Experience {
	return Experience{x.currentXp / between}
}
