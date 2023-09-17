import { CharacterDto } from './CharacterDto'

export interface CharacterListReadModel {
  invoke(): Promise<CharacterDto[]>
}
