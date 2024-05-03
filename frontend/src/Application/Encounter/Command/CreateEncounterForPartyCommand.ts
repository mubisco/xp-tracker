export class CreateEncounterForPartyCommand {
  // eslint-disable-next-line
  constructor(public readonly partyUlid: string, public readonly encounterName: string) {}
}
