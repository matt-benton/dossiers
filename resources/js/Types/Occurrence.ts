import type Person from './Person'

export default interface Occurrence {
  id: number,
  description: string,
  created_at: string,
  people: Array<Person>
}
