<script lang="ts">
import { computed, h, defineComponent, resolveComponent } from 'vue'
import Development from '../Types/Development'
import Person from '../Types/Person'
import Interest from '../Types/Interest'
import type { PropType } from 'vue'

export default defineComponent({
  props: {
    development: { type: Object as PropType<Development>, required: true },
    peopleInThread: { type: Array as PropType<Person[]>, required: true },
    interestsInThread: { type: Array as PropType<Interest[]>, required: true },
  },
  setup(props) {
    const link = resolveComponent('Link')

    const peopleAndInterests = computed(() => [
      ...props.peopleInThread,
      ...props.interestsInThread,
    ])

    const regex = computed(() => {
      const pattern = peopleAndInterests.value
        .map((personOrInterest) => '@' + personOrInterest.name)
        .join('|')

      return new RegExp(`(${pattern})`)
    })

    const parsed = props.development.description
      .split(regex.value)
      .map((str) => {
        const matchingPerson = props.peopleInThread.find(
          (person) => '@' + person.name === str
        )
        const matchingInterest = props.interestsInThread.find(
          (interest) => '@' + interest.name === str
        )

        if (matchingPerson) {
          return [
            h(
              link,
              { href: `/people/${matchingPerson.id}`, class: 'tag-link' },
              () => str
            ),
          ]
        } else if (matchingInterest) {
          return [
            h(
              link,
              { href: `/interests/${matchingInterest.id}`, class: 'tag-link' },
              () => str
            ),
          ]
        }

        return str
      })

    return () => h('p', parsed)
  },
})
</script>
