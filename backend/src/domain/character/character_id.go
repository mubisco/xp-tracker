package character

import (
	"math/rand"
	"time"

	"github.com/oklog/ulid"
)

func FromEmpty() CharacterId {
	defaultEntropySource := ulid.Monotonic(rand.New(rand.NewSource(time.Now().UnixNano())), 0)
	validUlid := ulid.MustNew(ulid.Timestamp(time.Now()), defaultEntropySource)
	return CharacterId{characterUlid: validUlid}
}

func NewCharacterId(value string) (CharacterId, error) {
	validUlid, err := ulid.Parse(value)
	if err != nil {
		return FromEmpty(), err
	}
	return CharacterId{characterUlid: validUlid}, nil
}

type CharacterId struct {
	characterUlid ulid.ULID
}

func (cid *CharacterId) value() string {
	return cid.characterUlid.String()
}
