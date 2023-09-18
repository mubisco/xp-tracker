import { CharacterDto } from './CharacterDto'

export interface CharacterListReadModel {
  read(): Promise<CharacterDto[]>
}
