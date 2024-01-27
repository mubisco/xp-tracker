package character

import (
	"testing"
)

func TestShouldThrowErrorWhenMaxHpZero(t *testing.T) {
	_, err := NewHealth(0)
	if err == nil {
		t.Errorf("Should throw error when max HP zero or less")
	}
}

func TestShouldReturnProperHitPointsValue(t *testing.T) {
	sut, _ := NewHealth(1)
	if sut.CurrentHp() != 1 {
		t.Errorf("Should return proper value of HitPoints")
	}
}

func TestShouldReturnProperCurrentHitpointsWhenDefined(t *testing.T) {
	sut, _ := NewHealthWithCurrent(10, 1)
	if sut.CurrentHp() != 1 {
		t.Errorf("Should return proper value of HitPoints")
	}
}

func TestShouldReturnTrueBleedingWhenBelowHalf(t *testing.T) {
	sut, _ := NewHealthWithCurrent(10, 5)
	if !sut.IsBleeding() {
		t.Errorf("Should return proper value of Bleeding")
	}
}

func TestShouldReturnFalseBleedingWhenAboveHalf(t *testing.T) {
	sut, _ := NewHealthWithCurrent(10, 6)
	if sut.IsBleeding() {
		t.Errorf("Should return proper value of Bleeding")
	}
}
