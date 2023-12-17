import { CharacterNotFoundError } from '@/Domain/Character/CharacterNotFoundError'
import { CharacterWriteModelError } from '@/Domain/Character/CharacterWriteModelError'
import { DeleteCharacterWriteModel } from '@/Domain/Character/DeleteCharacterWriteModel'
import { Ulid } from '@/Domain/Shared/Identity/Ulid'

export class DeleteCharacterWriteModelStub implements DeleteCharacterWriteModel {
  public shouldFail = false
  public shouldNotFind = false
  public deleted = false

  // eslint-disable-next-line
  async remove (characterUlid: Ulid): Promise<void> {
    if (this.shouldFail) {
      return Promise.reject(new CharacterWriteModelError())
    }
    if (this.shouldNotFind) {
      return Promise.reject(new CharacterNotFoundError())
    }
    this.deleted = true
    return Promise.resolve()
  }
}
