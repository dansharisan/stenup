(window.webpackJsonp=window.webpackJsonp||[]).push([[17],{QlCj:function(t,n,e){"use strict";e.r(n);e("nlWZ");var r=e("eYlF"),s=e("lYuH"),a={name:"UserInfo",data:function(){return{PERMISSION_NAME:s.c}},computed:{user:function(){return this.$store.get("auth/user")}},mixins:[r.a]},o=e("KHd+"),i=Object(o.a)(a,(function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"app flex-row align-items-center"},[e("div",{staticClass:"container"},[e("b-row",{staticClass:"justify-content-center"},[e("b-col",{attrs:{md:"4"}},[e("b-card-group",[e("b-card",{staticClass:"mb-0",attrs:{"no-body":""}},[e("b-card-header",[e("h2",{staticClass:"m-0"},[t._v("User information")])]),t._v(" "),e("b-card-body",[e("p",{staticClass:"text-muted"},[t._v("\n                                Your profile is as below:\n                            ")]),t._v(" "),e("b-input-group",{staticClass:"mb-3"},[e("b-input-group-prepend",{staticClass:"item-header-text",attrs:{"is-text":""}},[e("i",{staticClass:"fas fa-at"})]),t._v(" "),e("input",{staticClass:"form-control",attrs:{type:"text",placeholder:"Email",disabled:""},domProps:{value:t.user.email}})],1),t._v(" "),e("b-input-group",{staticClass:"mb-3"},[e("b-input-group-prepend",{staticClass:"item-header-text",attrs:{"is-text":""}},[e("i",{staticClass:"fas fa-user-circle"})]),t._v(" "),e("input",{staticClass:"form-control",attrs:{type:"text",placeholder:"Role",disabled:""},domProps:{value:this.getRoles(this.user)}})],1),t._v(" "),t.hasPermission(t.user,t.PERMISSION_NAME.VIEW_DASHBOARD)?e("b-button",{staticClass:"px-0",attrs:{variant:"link"},on:{click:function(n){return t.$router.push({name:"Dashboard"})}}},[t._v("\n                                Admin panel\n                            ")]):t._e(),t._v(" "),t.hasPermission(t.user,t.PERMISSION_NAME.VIEW_DASHBOARD)?e("br"):t._e(),t._v(" "),e("b-button",{staticClass:"px-0",attrs:{variant:"link"},on:{click:function(n){return t.$router.push({name:"PasswordChange"})}}},[t._v("\n                                Change password\n                            ")]),t._v(" "),e("br"),t._v(" "),e("b-button",{staticClass:"px-0",attrs:{variant:"link"},on:{click:function(n){return t.$router.push({name:"Home"})}}},[t._v("\n                                Back to Home\n                            ")]),t._v(" "),e("br"),t._v(" "),e("button",{staticClass:"btn px-0 btn-link",attrs:{type:"button"},on:{click:function(n){return t.logout()}}},[t._v("\n                                Log out\n                            ")])],1)],1)],1)],1)],1)],1)])}),[],!1,null,null,null);n.default=i.exports},eYlF:function(t,n,e){"use strict";function r(t){if("undefined"==typeof Symbol||null==t[Symbol.iterator]){if(Array.isArray(t)||(t=function(t,n){if(!t)return;if("string"==typeof t)return s(t,n);var e=Object.prototype.toString.call(t).slice(8,-1);"Object"===e&&t.constructor&&(e=t.constructor.name);if("Map"===e||"Set"===e)return Array.from(e);if("Arguments"===e||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(e))return s(t,n)}(t))){var n=0,e=function(){};return{s:e,n:function(){return n>=t.length?{done:!0}:{done:!1,value:t[n++]}},e:function(t){throw t},f:e}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var r,a,o=!0,i=!1;return{s:function(){r=t[Symbol.iterator]()},n:function(){var t=r.next();return o=t.done,t},e:function(t){i=!0,a=t},f:function(){try{o||null==r.return||r.return()}finally{if(i)throw a}}}}function s(t,n){(null==n||n>t.length)&&(n=t.length);for(var e=0,r=new Array(n);e<n;e++)r[e]=t[e];return r}e.d(n,"a",(function(){return a}));var a={methods:{hasRole:function(t,n){return this.getRoles(t).includes(n)},getRoles:function(t){if(!t.roles)return[];var n,e=[],s=r(t.roles);try{for(s.s();!(n=s.n()).done;){var a=n.value;e.push(a.name)}}catch(t){s.e(t)}finally{s.f()}return e},logout:function(){var t=this;t.$store.dispatch("auth/logout").then((function(n){t.$snotify.success("Logged out successfully"),"Index"!=t.$route.name&&t.$router.push({name:"Index"})})).catch((function(n){n.response&&n.response.status?t.handleInvalidAuthState(n.response.status):t.$snotify.error("Failed to log out")}))}}}}}]);