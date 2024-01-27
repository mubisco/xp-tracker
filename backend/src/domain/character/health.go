package character

import "errors"

func NewHealthWithCurrent(maxHp int, currentHp int) (Health, error) {
	if maxHp < 1 {
		error := errors.New("Experience should be zero or greater")
		return Health{0, 0}, error
	}
	return Health{maxHp, currentHp}, nil
}

func NewHealth(maxHp int) (Health, error) {
  return NewHealthWithCurrent(maxHp, maxHp)
}

type Health struct {
  _maxHp int
  _currentHp int
}

func (h *Health) CurrentHp() int {
	return h._currentHp
}

func (h *Health) IsBleeding() bool {
	return h._currentHp <= (h._maxHp / 2)
}
