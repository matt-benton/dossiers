import { format } from 'date-fns'

export function useFormatDate(date) {
  return format(date, 'MMMM dd, yyyy')
}
