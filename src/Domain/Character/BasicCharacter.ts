import { Character } from './Character'
import { CharacterId } from './CharacterId'
import { CharacterName } from './CharacterName'
import { Experience } from './Experience'
import { ExperienceDto } from './ExperienceDto'
import { Health } from './Health'
import { HitPointsDto } from './HitPointsDto'

export class BasicCharacter implements Character {
  private _characterId: string
  private _characterName: string
  private _actualXp: number
  private _maxHp: number
  private _actualHp: number

  static fromValues (name: CharacterName, experience: Experience, health: Health) {
    const xpValues = experience.values()
    const hpValues = health.hitpoints()
    return new this(
      name.value(),
      xpValues.actual,
      hpValues.max,
      hpValues.current
    )
  }

  private constructor (name: string, actualXp: number, maxHp: number, actualHp: number) {
    const id = CharacterId.fromEmpty()
    this._characterId = id.value()
    this._characterName = name
    this._actualXp = actualXp
    this._maxHp = maxHp
    this._actualHp = actualHp
  }

  id (): CharacterId {
    return CharacterId.fromString(this._characterId)
  }

  name (): CharacterName {
    return CharacterName.fromString(this._characterName)
  }

  experience (): ExperienceDto {
    return Experience.fromXp(this._actualXp).values()
  }

  hitPoints (): HitPointsDto {
    return Health.fromValues(this._maxHp, this._actualHp).hitpoints()
  }
}
