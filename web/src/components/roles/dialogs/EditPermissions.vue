<template>
  <basic-edit
    :title="`Доступи: ${role.name}`"
    :loading="loading"
    v-bind="$attrs"
    v-on="listeners"
  >
    <list-checkboxes
      v-model="form.permissions"
      :permissions-list="role.permissions_list"
    />
  </basic-edit>
</template>

<script>
import Role from '@/classes/Role'

export default {
  components: {
    ListCheckboxes: () => import('@/components/permissions/ListCheckboxes'),
    BasicEdit: () => import('@/common/components/dialogs/BasicEdit')
  },
  inheritAttrs: false,
  props: {
    role: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      form: {
        permissions: this.role.permissions_active
      }
    }
  },
  computed: {
    listeners() {
      return {
        ...this.$listeners,
        submit: this.fetchRequest
      }
    }
  },
  methods: {
    fetchRequest() {
      this.loading = true

      Role.fetchEditPermissions(this.role.id, this.form)
        .then(({ data }) => {
          this.$store.commit('roles/UPDATE_ITEM', data.role)
          this.$emit('edit')
          this.$emit('close')
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>
