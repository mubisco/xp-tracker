export class DeleteMonsterFromEncounterCommand {
  // eslint-disable-next-line
  constructor (
    public readonly encounterUlid: string,
    public readonly monsterName: string,
    public readonly monsterXp: number,
    public readonly monsterCr: string
  ) {}
}
