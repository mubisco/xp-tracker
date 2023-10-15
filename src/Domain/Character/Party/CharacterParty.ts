import { Character } from '@/Domain/Character/Character'
import { Experience } from '@/Domain/Character/Experience'
import { Party } from '@/Domain/Character/Party/Party'

export class CharacterParty implements Party {
  // eslint-disable-next-line
  constructor (private readonly members: Character[]) {}

  updateExperience (experiencePoints: Experience): void {
    const experienceToAdd = experiencePoints.split(this.count())
    this.members.forEach((character: Character): void => {
      character.addExperience(experienceToAdd)
    })
  }

  count (): number {
    return this.members.length
  }
}
