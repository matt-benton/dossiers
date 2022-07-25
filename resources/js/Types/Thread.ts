import Development from "./Development";
import Person from "./Person";

export default interface Thread {
  id: number,
  developments: Array<Development>,
  people: Array<Person>,
}
