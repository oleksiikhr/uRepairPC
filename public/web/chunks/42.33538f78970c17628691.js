(window.webpackJsonp=window.webpackJsonp||[]).push([[42],{269:function(t,e,n){"use strict";n.r(e);var r=n(52),o=n.n(r),i=n(3),l=n.n(i),a=n(49),s=n(0),u=n(99);function c(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter(function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable})),n.push.apply(n,r)}return n}function f(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?c(n,!0).forEach(function(e){l()(t,e,n[e])}):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):c(n).forEach(function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))})}return t}var d={components:{BasicEdit:function(){return n.e(2).then(n.bind(null,504))},ElFormItem:function(){return n.e(0).then(n.t.bind(null,273,7))},ElCheckbox:function(){return n.e(0).then(n.t.bind(null,94,7))},ElButton:function(){return n.e(0).then(n.t.bind(null,247,7))},ElUpload:function(){return n.e(0).then(n.t.bind(null,274,7))},ElInput:function(){return n.e(0).then(n.t.bind(null,56,7))},ElForm:function(){return n.e(0).then(n.t.bind(null,275,7))}},inheritAttrs:!1,data:function(){return{rows:a.a.rows,loadingFetch:!1,form:{}}},computed:{listeners:function(){return f({},this.$listeners,{submit:this.fetchRequest})},title:function(){return u.a[s.a.settings].children[s.a.settingsGlobal].title},settings:function(){return this.$store.state.settings.global.data},loading:function(){return this.$store.state.settings.global.init||this.$store.state.settings.global.loading}},watch:{settings:{handler:function(t){this.form=f({},t)},immediate:!0}},methods:{fetchRequest:function(){var t=this,e=new FormData;this.loadingFetch=!0,Object.entries(this.form).forEach(function(t){var n=o()(t,2),r=n[0],i=n[1];"boolean"==typeof i?e.append(r,+i):e.append(r,i||"")}),this.$refs.upload.forEach(function(n){e.delete(n.name),!n.disabled&&n.uploadFiles&&n.uploadFiles.length?e.append(n.name,n.uploadFiles[0].raw):t.form[n.name]||e.append(n.name,"")}),a.a.fetchStore(e).then(function(){t.$emit("store"),t.$emit("close")}).finally(function(){t.loadingFetch=!1})},deleteFile:function(t){this.$set(this.form,t,""),this.$refs.upload.forEach(function(e){e.name===t&&e.clearFiles()})}}},p=n(98),m=Object(p.a)(d,function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("basic-edit",t._g(t._b({attrs:{title:t.title,loading:t.loadingFetch}},"basic-edit",t.$attrs,!1),t.listeners),[n("el-form",{directives:[{name:"loading",rawName:"v-loading",value:t.loading,expression:"loading"}],ref:"form",staticClass:"form--full",attrs:{model:t.form,"status-icon":""},nativeOn:{submit:function(e){return e.preventDefault(),t.fetchRequest(e)}}},[t._l(t.rows,function(e,r){return n("el-form-item",{key:r,attrs:{prop:e.attr,label:"bool"!==e.type?e.title:null}},["img"===e.type?n("el-upload",{ref:"upload",refInFor:!0,attrs:{limit:1,"auto-upload":!1,accept:e.mimes,name:e.attr,action:""}},[n("el-button",{attrs:{slot:"trigger",size:"small"},slot:"trigger"},[t._v("\n          Оберіть файл\n        ")]),t._v(" "),t.form[e.attr]?n("el-button",{attrs:{type:"danger",size:"small",disabled:!t.form[e.attr]},on:{click:function(n){return t.deleteFile(e.attr)}}},[t._v("\n          Видалити\n        ")]):t._e()],1):"bool"===e.type?n("el-checkbox",{model:{value:t.form[e.attr],callback:function(n){t.$set(t.form,e.attr,n)},expression:"form[row.attr]"}},[t._v("\n        "+t._s(e.title)+"\n      ")]):n("el-input",{attrs:{placeholder:e.title},model:{value:t.form[e.attr],callback:function(n){t.$set(t.form,e.attr,n)},expression:"form[row.attr]"}})],1)}),t._v(" "),n("button",{staticClass:"hide",attrs:{type:"submit"}})],2)],1)},[],!1,null,null,null);e.default=m.exports}}]);