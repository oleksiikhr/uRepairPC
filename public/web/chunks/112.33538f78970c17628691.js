(window.webpackJsonp=window.webpackJsonp||[]).push([[112],{472:function(e,t,n){"use strict";n.r(t);var r=n(3),i=n.n(r),s=n(306);function o(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter(function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable})),n.push.apply(n,r)}return n}var c={components:{BasicDelete:function(){return n.e(3).then(n.bind(null,519))}},inheritAttrs:!1,props:{request:{type:Object,required:!0},comment:{type:Object,required:!0},index:{type:Number,default:0}},data:function(){return{loading:!1}},computed:{listeners:function(){return function(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?o(n,!0).forEach(function(t){i()(e,t,n[t])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):o(n).forEach(function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))})}return e}({},this.$listeners,{submit:this.fetchRequest})}},methods:{fetchRequest:function(){var e=this;this.loading=!0,s.a.fetchDelete(this.request.id,this.comment.id).then(function(){e.$emit("comment-delete",e.index),e.$emit("close")}).finally(function(){e.loading=!1})}}},u=n(98),l=Object(u.a)(c,function(){var e=this.$createElement;return(this._self._c||e)("basic-delete",this._g(this._b({attrs:{title:this.comment.message,loading:this.loading}},"basic-delete",this.$attrs,!1),this.listeners))},[],!1,null,null,null);t.default=l.exports}}]);