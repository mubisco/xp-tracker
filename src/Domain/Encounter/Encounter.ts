import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export interface Encounter {
  id(): Ulid
}
