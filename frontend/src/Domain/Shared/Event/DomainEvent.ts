export interface DomainEvent {
  occurredOn (): Date;
  name (): string;
  payload (): { [key: string]: any }
}
