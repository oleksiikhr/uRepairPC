<template>
  <el-card
    shadow="hover"
    class="user-block"
    :body-style="{ padding: '0' }"
    @click.native="onClick"
  >
    <div class="image">
      <user-image :user="user" />
    </div>
    <slot />
  </el-card>
</template>

<script>
import sections from '@/enum/sections'
import User from '@/classes/User'

export default {
  components: {
    UserImage: () => import('@/components/users/Image'),
    ElCard: () => import('element-ui/lib/card')
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    onClick() {
      User.sidebar().add(this.user)
      this.$router.push({ name: `${sections.users}-id`, params: { id: this.user.id } })
    }
  }
}
</script>

<style lang="scss" scoped>
.user-block {
  max-width: 250px;
  width: 100%;
  margin: 0 10px 20px 10px;
  cursor: pointer;
  user-select: none;
}

.image {
  padding: 20px;
}

.user-image {
  /deep/ .image {
    width: 100px;
    height: 100px;
    font-size: 1.2rem;
  }
  /deep/ .name {
    font-size: 1.3rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
}
</style>
