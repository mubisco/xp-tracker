import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export interface DeleteCharacterWriteModel {
  byUlid(characterUlid: Ulid): Promise<void>
}
