export class AddCharacterCommand {
  /* eslint-disable no-useless-constructor */
  constructor (
    readonly name: string,
    readonly actualXp: number,
    readonly maxHp: number
  ) {
  }
}
