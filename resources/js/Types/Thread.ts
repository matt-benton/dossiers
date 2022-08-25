import Development from "./Development"
import Person from "./Person"
import Interest from "./Interest"

export default interface Thread {
  id: number,
  developments: Array<Development>
  people: Array<Person>
  interests: Array<Interest>
}
