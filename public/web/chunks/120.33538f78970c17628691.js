(window.webpackJsonp=window.webpackJsonp||[]).push([[120],{485:function(e,t,r){"use strict";r.r(t);var n=r(3),i=r.n(n),s=r(101);function o(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),r.push.apply(r,n)}return r}var c={components:{ListCheckboxes:function(){return r.e(23).then(r.bind(null,483))},BasicEdit:function(){return r.e(2).then(r.bind(null,504))}},inheritAttrs:!1,props:{role:{type:Object,required:!0}},data:function(){return{loading:!1,form:{permissions:this.role.permissions_active}}},computed:{listeners:function(){return function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?o(r,!0).forEach(function(t){i()(e,t,r[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):o(r).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))})}return e}({},this.$listeners,{submit:this.fetchRequest})}},methods:{fetchRequest:function(){var e=this;this.loading=!0,s.a.fetchEditPermissions(this.role.id,this.form).then(function(t){var r=t.data;e.$store.commit("roles/UPDATE_ITEM",r.role),e.$emit("edit"),e.$emit("close")}).finally(function(){e.loading=!1})}}},l=r(98),a=Object(l.a)(c,function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("basic-edit",e._g(e._b({attrs:{title:"Доступи: "+e.role.name,loading:e.loading}},"basic-edit",e.$attrs,!1),e.listeners),[r("list-checkboxes",{attrs:{"permissions-list":e.role.permissions_list},model:{value:e.form.permissions,callback:function(t){e.$set(e.form,"permissions",t)},expression:"form.permissions"}})],1)},[],!1,null,null,null);t.default=a.exports}}]);