import Thread from "./Thread"
import Interest from "./Interest"

export default interface Person {
  id: number,
  name: string,
  relationship: string,
  birthday: number | null,
  birthmonth: number | null,
  birthmonth_text: string | null,
  user_id: number,
  threads?: Thread[],
  interests?: Interest[],
}
