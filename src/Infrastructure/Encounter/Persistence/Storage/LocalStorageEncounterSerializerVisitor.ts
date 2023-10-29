import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterVisitor } from '@/Domain/Encounter/EncounterVisitor'
import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'
import { MonsterDto } from '@/Domain/Encounter/MonsterDto'

export class LocalStorageEncounterSerializerVisitor implements EncounterVisitor<string> {
  visitDomainEncounter (encounter: DomainEncounter): string {
    const monsters = this.visitMonsters(encounter.monsters())
    const result = {
      ulid: encounter.id().value(),
      name: encounter.name().value(),
      status: encounter.status(),
      level: encounter.level(),
      // @ts-ignore
      characterLevels: encounter._characterLevels,
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
