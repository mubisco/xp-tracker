import { MonsterDto } from './MonsterDto'

export interface EncounterDto {
  readonly ulid: string
  readonly name: string
  readonly status: string
  readonly monsters: MonsterDto[]
}
