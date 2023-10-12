import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterVisitor } from '@/Domain/Encounter/EncounterVisitor'
import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'

interface MonsterDto {
  readonly name: string
  readonly xp: number
  readonly cr: string
}

export class LocalStorageEncounterSerializerVisitor implements EncounterVisitor<string> {
  // TODO To simplify maintanibility, this visitor shouls generate a EncounterDto structure
  // so the read model was just to parse the stored item
  visitDomainEncounter (encounter: DomainEncounter): string {
    const monsters = this.visitMonsters(encounter.monsters())

    const result = {
      ulid: encounter.id().value(),
      name: encounter.name().value(),
      monsters
    }
    return JSON.stringify(result)
  }

  private visitMonsters (monsters: EncounterMonster[]): MonsterDto[] {
    return monsters.map((monster: EncounterMonster): MonsterDto => {
      return {
        name: monster.name(),
        xp: monster.xp(),
        cr: monster.challengeRating()
      }
    })
  }
}
