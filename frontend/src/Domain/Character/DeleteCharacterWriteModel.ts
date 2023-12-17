import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export interface DeleteCharacterWriteModel {
  remove(characterUlid: Ulid): Promise<void>
}
