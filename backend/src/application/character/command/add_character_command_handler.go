package command

// TODO: Cargarme estos mapeos de json en el command
type AddCharacterCommand struct {
  Id string `json:"id"`
  Name string `json:"name"`
  ActualXp int `json:"actualXp"`
  MaxHp int `json:"maxHp"`
}

func Invoke(command AddCharacterCommand) {
}
