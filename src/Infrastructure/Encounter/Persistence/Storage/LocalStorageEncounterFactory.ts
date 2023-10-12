import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterDto } from '@/Domain/Encounter/EncounterDto'
import { EncounterName } from '@/Domain/Encounter/EncounterName'
import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'
import { MonsterDto } from '@/Domain/Encounter/MonsterDto'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class LocalStorageEncounterFactory {
  make (encounterData: EncounterDto): DomainEncounter {
    const encounter = DomainEncounter.withName(EncounterName.fromString(encounterData.name))
    // @ts-ignore
    encounter.ulid = Ulid.fromString(encounterData.ulid)
    // @ts-ignore
    encounter._encounterMonsters = this.hydrateMonsters(encounterData.monsters)
    return encounter
  }

  private hydrateMonsters (monsters: MonsterDto[]): EncounterMonster[] {
    return monsters.map((monster: MonsterDto): EncounterMonster => {
      return EncounterMonster.fromValues(monster.name, monster.xp, monster.cr)
    })
  }
}
