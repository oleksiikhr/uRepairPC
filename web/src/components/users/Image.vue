<template>
  <div class="user-image">
    <div
      :class="['image', {
        'image_has': !!user.image_id
      }]"
      :style="userObj.backgroundImage"
    >
      <template v-if="!user.image_id">
        <i class="material-icons">face</i>
        {{ userObj.initials }}
      </template>
    </div>
    <div class="name">
      {{ userObj.fullName }}
    </div>
  </div>
</template>

<script>
import User from '@/classes/User'

export default {
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  computed: {
    userObj() {
      return new User(this.user)
    }
  }
}
</script>

<style lang="scss" scoped>
.image {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  font-weight: bold;
  font-size: 2rem;
  width: 150px;
  height: 150px;
  background-repeat: no-repeat;
  background-size: cover;
  background-position: 50% 50%;
  border-radius: 50%;
  margin: 0 auto;
  color: #000;
  border: 3px solid;
  > i {
    margin-bottom: 10px;
  }
  &.image_has {
    border: 0;
  }
}

.name {
  margin-top: 30px;
  font-weight: bold;
  font-size: 1.5rem;
}
</style>
