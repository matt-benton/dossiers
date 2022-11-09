import Development from "./Development"
import Person from "./Person"
import Interest from "./Interest"
import Group from "./Group"

export default interface Thread {
  id: number,
  developments: Array<Development>
  people: Person[]
  interests: Interest[]
  groups: Group[]
}
