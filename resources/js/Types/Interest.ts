import Person from "./Person"
import Thread from "./Thread"

export default interface Interest {
  id: number,
  name: string,
  relationship: string,
  user_id: number,
  threads?: Thread[],
  people?: Person[],
}
