import { Ulid } from '../Shared/Identity/Ulid'
import { Character } from './Character'
import { CharacterName } from './CharacterName'
import { CharacterVisitor } from './CharacterVisitor'
import { Experience } from './Experience'
import { ExperienceDto } from './ExperienceDto'
import { Health } from './Health'
import { HitPointsDto } from './HitPointsDto'

export class BasicCharacter implements Character {
  private _characterId: Ulid
  private _characterName: CharacterName
  private _experience: Experience
  private _health: Health

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
    this._characterId = Ulid.fromEmpty()
    this._characterName = CharacterName.fromString(name)
    this._experience = Experience.fromXp(actualXp)
    this._health = Health.fromValues(maxHp, actualHp)
  }

  addExperience (experience: Experience): void {
    const updatedExperience = this._experience.add(experience)
    this._experience = updatedExperience
  }

  id (): Ulid {
    return this._characterId
  }

  name (): CharacterName {
    return this._characterName
  }

  experience (): ExperienceDto {
    return this._experience.values()
  }

  hitPoints (): HitPointsDto {
    return this._health.hitpoints()
  }

  visit (visitor: CharacterVisitor<any>): any {
    return visitor.visitBasicCharacter(this)
  }
}
