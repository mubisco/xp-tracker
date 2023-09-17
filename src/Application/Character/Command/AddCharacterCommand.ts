export class AddCharacterCommand {
  public readonly name: string
  public readonly actualXp: number
  public readonly maxHp: number

  constructor (name: string, actualXp: number, maxHp: number) {
    this.name = name
    this.actualXp = actualXp
    this.maxHp = maxHp
  }
}
