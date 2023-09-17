export class DomainError extends Error {
  constructor (message?: string) {
    super(message)
    const actualProto = new.target.prototype
    Object.setPrototypeOf(this, actualProto)
  }
}
