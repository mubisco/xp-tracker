import { DomainEncounter } from '@/Domain/Encounter/DomainEncounter'
import { EncounterName } from '@/Domain/Encounter/EncounterName'
import { EncounterStatus } from '@/Domain/Encounter/EncounterStatus'
import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'
import { MonsterDto } from '@/Domain/Encounter/MonsterDto'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class LocalStorageEncounterFactory {
  make (encounterData: string): DomainEncounter {
    const parsedData = JSON.parse(encounterData)
    const encounter = DomainEncounter.withName(EncounterName.fromString(parsedData.name))
    // @ts-ignore
    encounter.ulid = Ulid.fromString(parsedData.ulid)
    // @ts-ignore
    encounter._encounterMonsters = this.hydrateMonsters(parsedData.monsters)
    // @ts-ignore
    encounter._status = EncounterStatus[parsedData.status]
    // @ts-ignore
    encounter._characterLevels = parsedData.characterLevels
    return encounter
  }

  private hydrateMonsters (monsters: MonsterDto[]): EncounterMonster[] {
    return monsters.map((monster: MonsterDto): EncounterMonster => {
      return EncounterMonster.fromValues(monster.name, monster.xp, monster.cr)
    })
  }
}
