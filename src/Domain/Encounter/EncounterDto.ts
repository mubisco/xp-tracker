import { MonsterDto } from './MonsterDto'

export interface EncounterDto {
  readonly ulid: string
  readonly name: string
  readonly monsters: MonsterDto[]
}
