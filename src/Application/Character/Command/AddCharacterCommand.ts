export class AddCharacterCommand {
  public readonly data: { [key: string]: any }

  constructor (data: { [key: string]: any }) {
    this.data = data
  }
}
