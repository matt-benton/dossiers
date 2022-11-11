import Person from './Person'
import Thread from './Thread'

export default interface Group {
  id: number,
  name: string,
  members: Person[]
  threads?: Thread[]
}
