(window.webpackJsonp=window.webpackJsonp||[]).push([[21],{335:function(e,n,i){},383:function(e,n,i){"use strict";var t=i(335);i.n(t).a},464:function(e,n,i){"use strict";i.r(n);var t=i(10),o={components:{LoadingFiles:function(){return i.e(73).then(i.bind(null,516))},OneFile:function(){return i.e(56).then(i.bind(null,517))}},props:{files:{type:Array,required:!0},loading:{type:Boolean,required:!0},urlDownload:{type:Function,default:null},permissionCreate:{type:[String,Boolean,Function],default:null},permissionDownload:{type:[String,Boolean,Function],default:null},permissionEdit:{type:[String,Boolean,Function],default:null},permissionDelete:{type:[String,Boolean,Function],default:null}},methods:{hasPerm:t.d,onAdd:function(){this.$emit("add")},onEdit:function(){for(var e=arguments.length,n=new Array(e),i=0;i<e;i++)n[i]=arguments[i];this.$emit.apply(this,["edit"].concat(n))},onDelete:function(){for(var e=arguments.length,n=new Array(e),i=0;i<e;i++)n[i]=arguments[i];this.$emit.apply(this,["delete"].concat(n))},onRefresh:function(){this.$emit("refresh")}}},s=(i(383),i(98)),l=Object(s.a)(o,function(){var e=this,n=e.$createElement,i=e._self._c||n;return i("div",{staticClass:"files-list"},[i("div",{staticClass:"actions"},[i("div",{staticClass:"file-refresh",on:{click:e.onRefresh}},[i("i",{staticClass:"material-icons"},[e._v("refresh")]),e._v("\n      Оновити\n    ")]),e._v(" "),e.hasPerm(e.permissionCreate)?i("div",{staticClass:"file-add",on:{click:e.onAdd}},[i("i",{staticClass:"material-icons"},[e._v("cloud_upload")]),e._v("\n      Завантажити файл\n    ")]):e._e()]),e._v(" "),e.loading?i("loading-files"):i("div",{staticClass:"content"},[i("div",{staticClass:"items"},e._l(e.files,function(n,t){return i("one-file",{key:t,attrs:{file:n,"url-download":e.urlDownload,"permission-download":e.permissionDownload,"permission-edit":e.permissionEdit,"permission-delete":e.permissionDelete},on:{edit:function(n){return e.onEdit(n,t)},delete:function(n){return e.onDelete(n,t)}}})}),1)])],1)},[],!1,null,"558d0a0b",null);n.default=l.exports}}]);