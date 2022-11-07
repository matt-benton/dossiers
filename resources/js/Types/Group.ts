import Person from './Person'

export default interface Group {
  id: number,
  name: string,
  people: Person[]
}
