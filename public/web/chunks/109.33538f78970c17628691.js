(window.webpackJsonp=window.webpackJsonp||[]).push([[109],{437:function(t,e,n){"use strict";n.r(e);var i=n(3),r=n.n(i),s=n(104);function o(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var i=Object.getOwnPropertySymbols(t);e&&(i=i.filter(function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable})),n.push.apply(n,i)}return n}var c={components:{BasicDelete:function(){return n.e(3).then(n.bind(null,519))}},inheritAttrs:!1,props:{item:{type:Object,required:!0}},data:function(){return{loading:!1}},computed:{listeners:function(){return function(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?o(n,!0).forEach(function(e){r()(t,e,n[e])}):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):o(n).forEach(function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))})}return t}({},this.$listeners,{submit:this.fetchRequest})}},methods:{fetchRequest:function(){var t=this;this.loading=!0,s.a.fetchDelete(this.item.id).then(function(){t.$emit("delete"),t.$emit("close")}).finally(function(){t.loading=!1})}}},l=n(98),u=Object(l.a)(c,function(){var t=this.$createElement;return(this._self._c||t)("basic-delete",this._g(this._b({attrs:{title:this.item.name,confirm:this.item.id,loading:this.loading}},"basic-delete",this.$attrs,!1),this.listeners))},[],!1,null,null,null);e.default=u.exports}}]);