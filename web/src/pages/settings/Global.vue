<template>
  <div class="global-settings">
    <div class="title">
      {{ title }}
    </div>
    <el-timeline v-loading="loading">
      <el-timeline-item
        v-for="(row, index) in rows"
        :key="index"
        :timestamp="row.title"
        placement="top"
      >
        <el-card shadow="none">
          <item
            :value="settings[row.attr]"
            :type="row.type"
            :attr="row.attr"
          />
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
import SettingsGlobal from '@/classes/SettingsGlobal'
import breadcrumbs from '@/mixins/breadcrumbs'
import sections from '@/enum/sections'
import menu from '@/data/menu'

export default {
  name: 'Global',
  breadcrumbs: [
    { title: menu[sections.settings].title, routeName: sections.settings },
    { title: menu[sections.settings].children[sections.settingsGlobal].title }
  ],
  components: {
    ElTimelineItem: () => import('element-ui/lib/timeline-item'),
    ElTimeline: () => import('element-ui/lib/timeline'),
    Item: () => import('@/components/settings/Item'),
    ElButton: () => import('element-ui/lib/button'),
    ElCard: () => import('element-ui/lib/card')
  },
  mixins: [
    breadcrumbs
  ],
  data() {
    return {
      rows: SettingsGlobal.rows
    }
  },
  computed: {
    settings() {
      return this.$store.state.settings.global.data
    },
    loading() {
      return this.$store.state.settings.global.loading
    },
    title() {
      return menu[sections.settings].children[sections.settingsGlobal].title
    }
  },
  methods: {
    onClickEdit() {
      this.$store.commit('template/OPEN_DIALOG', {
        component: () => import('@/components/settings/dialogs/Global')
      })
    }
  }
}
</script>
