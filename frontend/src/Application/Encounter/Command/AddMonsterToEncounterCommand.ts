export class AddMonsterToEncounterCommand {
  // eslint-disable-next-line
  constructor(
    public readonly encounterUlid: string,
    public readonly monsterName: string,
    public readonly experiencePoints: number,
    public readonly challengeRating: string
  ) {}
}
