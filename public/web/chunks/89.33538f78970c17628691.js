(window.webpackJsonp=window.webpackJsonp||[]).push([[89],{301:function(t,e,n){"use strict";n.d(e,"b",function(){return i});var i=["Home","Requests","Users","Settings","Roles","SettingsCore"];e.a=[].concat(i,["EquipmentManufacturers","EquipmentModels","EquipmentTypes","RequestStatuses","RequestPriorities","RequestTypes","Manifest","Global"])},302:function(t,e,n){"use strict";var i=n(28),s=n.n(i),a=n(301),o=n(18),l=n(0),r=n(99);function u(t){var e=r.a[l.a.home].title;return t?{title:e,routeName:o.a}:{title:e}}e.a={activated:function(){this.$nextTick(this.updateBreadcrumbs)},mounted:function(){this.$options.name&&a.a.includes(this.$options.name)||this.$nextTick(this.updateBreadcrumbs)},methods:{updateBreadcrumbs:function(){this.$options.breadcrumbs?this.$store.commit("template/SET_BREADCRUMBS",[u(!0)].concat(s()(this.$options.breadcrumbs))):this.$store.commit("template/SET_BREADCRUMBS",[u(!1)])}}}},431:function(t,e,n){"use strict";n.r(e);var i=n(49),s=n(302),a=n(0),o=n(99),l={name:"Global",breadcrumbs:[{title:o.a[a.a.settings].title,routeName:a.a.settings},{title:o.a[a.a.settings].children[a.a.settingsGlobal].title}],components:{ElTimelineItem:function(){return n.e(0).then(n.t.bind(null,283,7))},ElTimeline:function(){return n.e(0).then(n.t.bind(null,284,7))},Item:function(){return n.e(41).then(n.bind(null,493))},ElButton:function(){return n.e(0).then(n.t.bind(null,247,7))},ElCard:function(){return n.e(0).then(n.t.bind(null,285,7))}},mixins:[s.a],data:function(){return{rows:i.a.rows}},computed:{settings:function(){return this.$store.state.settings.global.data},loading:function(){return this.$store.state.settings.global.loading},title:function(){return o.a[a.a.settings].children[a.a.settingsGlobal].title}},methods:{onClickEdit:function(){this.$store.commit("template/OPEN_DIALOG",{component:function(){return n.e(42).then(n.bind(null,269))}})}}},r=n(98),u=Object(r.a)(l,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"global-settings"},[n("div",{staticClass:"title"},[t._v("\n    "+t._s(t.title)+"\n  ")]),t._v(" "),n("el-timeline",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}]},t._l(t.rows,function(e,i){return n("el-timeline-item",{key:i,attrs:{timestamp:e.title,placement:"top"}},[n("el-card",{attrs:{shadow:"none"}},[n("item",{attrs:{value:t.settings[e.attr],type:e.type,attr:e.attr}})],1)],1)}),1),t._v(" "),n("div",{staticClass:"btn-block"},[n("el-button",{attrs:{type:"primary",disabled:t.loading},on:{click:t.onClickEdit}},[t._v("\n      Редагувати\n    ")])],1)],1)},[],!1,null,null,null);e.default=u.exports}}]);