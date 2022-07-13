import { format } from 'date-fns'

export function useFormatDate(date: Date): string {
  return format(date, 'MMMM dd, yyyy')
}
