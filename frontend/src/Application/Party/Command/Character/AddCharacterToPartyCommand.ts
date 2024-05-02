export class AddCharacterToPartyCommand {
  // eslint-disable-next-line
  constructor(
    public readonly partyUlid: string,
    public readonly characterName: string,
    public readonly experiencePoints: number
  ) {}
}
