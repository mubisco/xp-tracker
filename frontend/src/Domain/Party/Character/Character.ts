import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class Character {
  static fromValues (ulid: string, name: string, xp: number): Character {
    return new Character(ulid, name, xp)
  }

  static withNameAndXp (name: string, xp: number): Character {
    const ulid = Ulid.fromEmpty()
    return new Character(ulid.value(), name, xp)
  }

  private characterUlid: Ulid

  // eslint-disable-next-line
  constructor (
    characterUlid: string,
    private readonly characterName: string,
    private readonly experiencePoints: number
  ) {
    this.characterUlid = Ulid.fromString(characterUlid)
    if (this.characterName === '') {
      throw new RangeError('Character name cannot be empty')
    }
    if (this.experiencePoints < 0) {
      throw new RangeError('Experience points cannot be less than zero')
    }
  }

  ulid (): string {
    return this.characterUlid.value()
  }

  name (): string {
    return this.characterName
  }

  xp (): number {
    return this.experiencePoints
  }
}
