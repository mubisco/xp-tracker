import { MonsterDto } from './MonsterDto'

export interface EncounterDto {
  readonly ulid: string
  readonly name: string
  readonly status: string
  readonly level: string
  readonly monsters: MonsterDto[]
}
