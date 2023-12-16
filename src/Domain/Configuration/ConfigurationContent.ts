export class ConfigurationContent {
  static fromBase64Content (content: string): ConfigurationContent {
    return new this(content)
  }

  private decodedContent: string

  constructor (base64Content: string) {
    if (!base64Content) {
      throw new RangeError('Content should not be empty')
    }
    this.decodedContent = atob(base64Content)
    this.validateDecodedContent()
  }

  value (): string {
    return this.decodedContent
  }

  private validateDecodedContent (): void {
    if (this.decodedContent.indexOf('encounters') === -1) {
      throw new RangeError('No encounter section found on config file')
    }
    if (this.decodedContent.indexOf('characters') === -1) {
      throw new RangeError('No character section found on config file')
    }
  }
}
