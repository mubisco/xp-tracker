import { EncounterMonster } from '@/Domain/Encounter/Monster/EncounterMonster'

export class EncounterMonsterOM {
  static random (): EncounterMonster {
    return EncounterMonster.fromValues('Some name', 2500, '1/2')
  }
}
