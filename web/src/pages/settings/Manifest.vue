<template>
  <div class="global-settings">
    <div class="title">
      {{ title }}
    </div>
    <el-alert
      title="Інформація про маніфест"
      type="info"
      class="mb-30"
      :closable="false"
      show-icon
    >
      <a
        href="https://developer.mozilla.org/en-US/docs/Web/Manifest"
        rel="nofollow"
        target="_blank"
      >
        Mozilla Manifest
      </a>
    </el-alert>
    <el-timeline v-loading="loading">
      <el-timeline-item
        v-for="(row, index) in rows"
        :key="index"
        :timestamp="row.title"
        placement="top"
      >
        <el-card shadow="none">
          <item
            :value="manifest[row.attr]"
            :type="row.type"
            :attr="row.attr"
          />
        </el-card>
      </el-timeline-item>
      <el-timeline-item
        timestamp="Іконки"
        placement="top"
      >
        <el-card shadow="none">
          <div class="icons">
            <div
              v-for="(obj, index) in manifest.icons"
              :key="index"
              class="icon"
            >
              <div class="size">
                {{ obj.sizes }} - {{ obj.type }}
              </div>
              <img
                :src="obj.src"
                :alt="`${obj.sizes} - ${obj.type}`"
              >
            </div>
          </div>
        </el-card>
      </el-timeline-item>
    </el-timeline>
    <div class="btn-block">
      <el-button
        type="primary"
        :disabled="loading"
        @click="onClickEdit"
      >
        Редагувати
      </el-button>
    </div>
  </div>
</template>

<script>
import SettingsManifest from '@/classes/SettingsManifest'
import breadcrumbs from '@/mixins/breadcrumbs'
import sections from '@/enum/sections'
import { mapState } from 'vuex'
import menu from '@/data/menu'

export default {
  name: 'Manifest',
  breadcrumbs: [
    { title: menu[sections.settings].title, routeName: sections.settings },
    { title: menu[sections.settings].children[sections.settingsManifest].title }
  ],
  components: {
    ElTimelineItem: () => import('element-ui/lib/timeline-item'),
    ElTimeline: () => import('element-ui/lib/timeline'),
    Item: () => import('@/components/settings/Item'),
    ElButton: () => import('element-ui/lib/button'),
    ElAlert: () => import('element-ui/lib/alert'),
    ElCard: () => import('element-ui/lib/card')
  },
  mixins: [
    breadcrumbs
  ],
  data() {
    return {
      rows: SettingsManifest.rows
    }
  },
  computed: {
    ...mapState({
      manifest: state => state.settings.manifest.data,
      loading: state => state.settings.manifest.loading,
      init: state => state.settings.manifest.init
    }),
    title() {
      return menu[sections.settings].children[sections.settingsManifest].title
    }
  },
  mounted() {
    if (this.init) {
      this.$store.dispatch('settings/fetchManifest')
    }
  },
  methods: {
    onClickEdit() {
      this.$store.commit('template/OPEN_DIALOG', {
        component: () => import('@/components/settings/dialogs/Manifest')
      })
    }
  }
}
</script>

<style lang="scss" scoped>
@import "~scss/_colors";

.icon {
	padding: 10px;
	img {
		max-width: 100%;
		max-height: none;
	}
	.size {
		background: #fff;
		border: 1px solid $defaultBorder;
		padding: 5px;
		margin-bottom: 10px;
	}
}
</style>
