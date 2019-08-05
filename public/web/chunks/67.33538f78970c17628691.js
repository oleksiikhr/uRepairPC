(window.webpackJsonp=window.webpackJsonp||[]).push([[67],{301:function(t,e,n){"use strict";n.d(e,"b",function(){return o});var o=["Home","Requests","Users","Settings","Roles","SettingsCore"];e.a=[].concat(o,["EquipmentManufacturers","EquipmentModels","EquipmentTypes","RequestStatuses","RequestPriorities","RequestTypes","Manifest","Global"])},302:function(t,e,n){"use strict";var o=n(28),r=n.n(o),i=n(301),s=n(18),u=n(0),l=n(99);function c(t){var e=l.a[u.a.home].title;return t?{title:e,routeName:s.a}:{title:e}}e.a={activated:function(){this.$nextTick(this.updateBreadcrumbs)},mounted:function(){this.$options.name&&i.a.includes(this.$options.name)||this.$nextTick(this.updateBreadcrumbs)},methods:{updateBreadcrumbs:function(){this.$options.breadcrumbs?this.$store.commit("template/SET_BREADCRUMBS",[c(!0)].concat(r()(this.$options.breadcrumbs))):this.$store.commit("template/SET_BREADCRUMBS",[c(!1)])}}}},304:function(t,e,n){"use strict";e.a={activated:function(){this.updateScrollTablePosition()},beforeRouteLeave:function(t,e,n){this.saveScrollTablePosition(),n()},methods:{updateScrollTablePosition:function(){var t=this.$store.state.template.pagesScroll[this.$route.name];t&&setTimeout(function(){document.documentElement.scrollTop=t})},saveScrollTablePosition:function(){var t=0,e=document.documentElement.scrollTop||document.body.scrollTop;e&&(t=e),this.$store.commit("template/SET_PAGE_SCROLL",{pageName:this.$route.name,scroll:t})}}}},420:function(t,e,n){"use strict";n.r(e);var o=n(3),r=n.n(o),i=n(304),s=n(9),u=n(302),l=n(0),c=n(100),a=n(74);function f(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(t);e&&(o=o.filter(function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable})),n.push.apply(n,o)}return n}function m(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?f(n,!0).forEach(function(e){r()(t,e,n[e])}):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):f(n).forEach(function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))})}return t}var h={name:"Users",breadcrumbs:[{title:n(99).a[l.a.users].title}],components:{FilterTableButtons:function(){return n.e(17).then(n.bind(null,449))},FilterPagination:function(){return n.e(15).then(n.bind(null,451))},FilterColumns:function(){return n.e(12).then(n.bind(null,453))},FilterAction:function(){return n.e(11).then(n.bind(null,454))},FilterSearch:function(){return n.e(16).then(n.bind(null,455))},TemplateList:function(){return n.e(9).then(n.bind(null,521))},FilterFixed:function(){return n.e(14).then(n.bind(null,458))},FilterCore:function(){return n.e(13).then(n.bind(null,459))},TableComponent:function(){return n.e(8).then(n.bind(null,460))},RoleTag:function(){return n.e(25).then(n.bind(null,474))}},mixins:[i.a,u.a],data:function(){return{sections:l.a,sectionName:l.a.users,columns:[],fixed:null,search:"",sort:{}}},computed:m({},Object(a.b)({userColumns:"users/columns"}),{list:function(){return this.$store.state.users.list},users:function(){return this.list.data||[]},filterColumns:function(){var t=[],e=!0,n=!1,o=void 0;try{for(var r,i=this.columns[Symbol.iterator]();!(e=(r=i.next()).done);e=!0){var s=r.value;s.model&&t.push(m({},s,{fixed:this.fixed===s.prop}))}}catch(t){n=!0,o=t}finally{try{e||null==i.return||i.return()}finally{if(n)throw o}}return t},loading:function(){return this.$store.state.users.loading},activeColumnProps:function(){return this.filterColumns.filter(function(t){return!t.disableSearch}).map(function(t){return t.prop})}}),watch:{userColumns:{handler:function(t){this.columns=t.filter(function(t){return!t.hideList})},immediate:!0}},mounted:function(){this.fetchList()},methods:{fetchList:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1;this.$store.dispatch("users/fetchList",{page:t,sortColumn:this.sort.column,sortOrder:this.sort.order,columns:this.activeColumnProps,search:this.search||null})},onChangeColumn:function(){s.a.columnUsers=this.filterColumns.map(function(t){return t.prop})},onRowClick:function(t){c.a.sidebar().add(t),this.$router.push({name:"".concat(l.a.users,"-id"),params:{id:t.id}})},onSortChange:function(t){var e=t.prop,n=t.order;this.sort={column:e,order:n},this.fetchList()}}},d=n(98),p=Object(d.a)(h,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("template-list",[n("template",{slot:"left-column"},[n("table-component",{attrs:{slot:"left-column",columns:t.filterColumns,list:t.list,loading:t.loading},on:{fetch:t.fetchList,"row-click":t.onRowClick,"sort-change":t.onSortChange},slot:"left-column",scopedSlots:t._u([{key:"default",fn:function(e){var o=e.column,r=e.data;return["roles"===o.prop?t._l(r,function(t,e){return n("role-tag",{key:e,attrs:{role:t}})}):t._e()]}}])})],1),t._v(" "),n("filter-core",{attrs:{slot:"right-column"},slot:"right-column"},[n("filter-table-buttons",{ref:"buttons",attrs:{section:t.sections.users},on:{update:function(){return t.fetchList(+t.list.current_page||1)}}}),t._v(" "),n("filter-action",{attrs:{section:t.sectionName}}),t._v(" "),n("filter-search",{on:{submit:t.fetchList},model:{value:t.search,callback:function(e){t.search=e},expression:"search"}}),t._v(" "),n("filter-pagination",{attrs:{pagination:t.list}}),t._v(" "),n("filter-columns",{attrs:{columns:t.columns},on:{change:t.onChangeColumn}}),t._v(" "),n("filter-fixed",{attrs:{columns:t.columns},model:{value:t.fixed,callback:function(e){t.fixed=e},expression:"fixed"}})],1)],2)},[],!1,null,null,null);e.default=p.exports}}]);