import type Person from './Person'

export default interface Development {
  id: number,
  description: string,
  created_at: string,
  people: Array<Person>
}
