const VALID_FILENAME_REGEX = /^[0-9a-zA-Z_]{3,}$/
export class ExportFileName {
  private _filename: string

  static fromString (filename: string): ExportFileName {
    return new this(filename)
  }

  constructor (filename: string) {
    if (!VALID_FILENAME_REGEX.test(filename)) {
      throw new RangeError('Not valid filename provided: ' + filename)
    }
    this._filename = filename + '.json'
  }

  value (): string {
    return this._filename
  }
}
