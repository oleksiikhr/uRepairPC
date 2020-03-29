<template>
  <basic-delete
    :title="userObj.fullName"
    :confirm="user.id"
    :loading="loading"
    v-bind="$attrs"
    v-on="listeners"
  />
</template>

<script>
import User from '@/classes/User'

export default {
  components: {
    BasicDelete: () => import('@/common/components/dialogs/BasicDelete')
  },
  inheritAttrs: false,
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loading: false
    }
  },
  computed: {
    listeners() {
      return {
        ...this.$listeners,
        submit: this.fetchRequest
      }
    },
    userObj() {
      return new User(this.user)
    }
  },
  methods: {
    fetchRequest() {
      this.loading = true

      User.fetchDelete(this.user.id)
        .then(() => {
          this.$store.commit('users/DELETE_ITEM', this.user.id)
          this.$emit('delete')
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
