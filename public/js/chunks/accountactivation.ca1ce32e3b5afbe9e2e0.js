(window.webpackJsonp=window.webpackJsonp||[]).push([[1],{dPxz:function(t,a,e){"use strict";e.r(a);var s=e("nlWZ"),n={name:"AccountActivation",data:function(){return{notification:{type:"danger",message:""},activateAccountRequest:{status:0},params:{token:""}}},created:function(){if(this.params.token=this.$route.params.token,!this.params.token)return this.notification.type="danger",this.notification.message="Token not found",!1;this.activateAccount(this.params.token)},methods:{activateAccount:function(t){var a=this;this.activateAccountRequest.status=1,s.a.activateAccount(t).then((function(t){a.notification.type="success",a.notification.message="Your account has been activated successfully. You can now login.",a.activateAccountRequest.status=2})).catch((function(t){a.notification.type="danger",a.activateAccountRequest.status=3,t.response?a.notification.message=t.response.data.error?t.response.data.error.message:t.response.data.message:a.notification.message="Network error"}))}}},i=e("KHd+"),o=Object(i.a)(n,(function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("div",{staticClass:"app flex-row align-items-center"},[e("div",{staticClass:"container"},[e("b-row",{staticClass:"justify-content-center"},[e("b-col",{staticClass:"mr-2 ml-2 pr-0 pl-0",attrs:{md:"4"}},[e("loading",{attrs:{active:1==t.activateAccountRequest.status}}),t._v(" "),e("b-card-group",[e("b-card",{staticClass:"mb-0",attrs:{"no-body":""}},[e("b-card-header",[e("h2",{staticClass:"m-0"},[t._v("Activate account")])]),t._v(" "),e("b-card-body",[e("p",{staticClass:"text-muted"},[t._v("\n                                Activate your account\n                            ")]),t._v(" "),t.notification.message?e("div",{class:"alert alert-"+t.notification.type,attrs:{id:"message",role:"alert"},domProps:{innerHTML:t._s(t.notification.message)}}):t._e(),t._v(" "),e("b-row",[e("b-col",{staticClass:"text-left",attrs:{cols:"6"}},[e("b-button",{staticClass:"px-0",attrs:{variant:"link"},on:{click:function(a){return t.$router.push({name:"Login"})}}},[t._v("\n                                        Log in\n                                    ")]),t._v(" "),e("br"),t._v(" "),e("b-button",{staticClass:"px-0",attrs:{variant:"link"},on:{click:function(a){return t.$router.push({name:"Home"})}}},[t._v("\n                                        Back to Home\n                                    ")])],1)],1)],1)],1)],1)],1)],1)],1)])}),[],!1,null,null,null);a.default=o.exports}}]);