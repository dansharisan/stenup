(window.webpackJsonp=window.webpackJsonp||[]).push([[4],{"1pTT":function(t,e,a){"use strict";var s=a("lYuH");e.a={getUsers:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:25;return axios.get(s.a.API_URL+"/users?page="+t+"&per_page="+e)},readUser:function(t){return axios.get(s.a.API_URL+"/users/"+t)},banUser:function(t){return axios.patch(s.a.API_URL+"/users/"+t+"/ban")},unbanUser:function(t){return axios.patch(s.a.API_URL+"/users/"+t+"/unban")},deleteUser:function(t){return axios.delete(s.a.API_URL+"/users/"+t)},deleteUsers:function(t){return axios.post(s.a.API_URL+"/users/collection:batchDelete",{ids:t})},updateUser:function(t,e,a,r){return axios.patch(s.a.API_URL+"/users/"+t,{email:e,email_verified_at:a,role_ids:r})},createUser:function(t,e,a,r,n){return axios.post(s.a.API_URL+"/users",{email:t,password:a,password_confirmation:r,role_ids:n,email_verified_at:e})},getUserStats:function(){return axios.get(s.a.API_URL+"/users/registered_user_stats")}}},GYLo:function(t,e,a){"use strict";var s=a("M417");a.n(s).a},M417:function(t,e,a){var s=a("SfyM");"string"==typeof s&&(s=[[t.i,s,""]]);var r={hmr:!0,transform:void 0,insertInto:void 0};a("aET+")(s,r);s.locals&&(t.exports=s.locals)},SfyM:function(t,e,a){(t.exports=a("I1BE")(!1)).push([t.i,"\n.chartjs-tooltip {\r\n    position: absolute;\r\n    z-index: 1021;\r\n    display: flex;\r\n    flex-direction: column;\r\n    padding: 0.25rem 0.5rem;\r\n    color: #fff;\r\n    pointer-events: none;\r\n    background: rgba(0, 0, 0, 0.7);\r\n    opacity: 0;\r\n    transition: all 0.25s ease;\r\n    transform: translate(-50%, 0);\r\n    border-radius: 0.25rem;\n}\n.chartjs-tooltip .tooltip-header {\r\n    margin-bottom: 0.5rem;\n}\n.chartjs-tooltip .tooltip-header-item {\r\n    font-size: 0.765625rem;\r\n    font-weight: 700;\n}\n.chartjs-tooltip .tooltip-body-item {\r\n    display: flex;\r\n    align-items: center;\r\n    font-size: 0.765625rem;\r\n    white-space: nowrap;\n}\n.chartjs-tooltip .tooltip-body-item-color {\r\n    display: inline-block;\r\n    width: 0.875rem;\r\n    height: 0.875rem;\r\n    margin-right: 0.875rem;\n}\n.chartjs-tooltip .tooltip-body-item-value {\r\n    padding-left: 1rem;\r\n    margin-left: auto;\r\n    font-weight: 700;\n}\r\n",""])},cIH9:function(t,e,a){"use strict";a.r(e);var s=a("1pTT"),r=a("H8ri"),n=a("H++W"),o={extends:r.a,props:["height","data","label","backgroundColor"],mounted:function(){var t=[{label:this.label,backgroundColor:this.backgroundColor,borderColor:"transparent",data:Object.values(this.data),barPercentage:.5,categoryPercentage:1}];this.renderChart({labels:Object.keys(this.data),datasets:t},{tooltips:{enabled:!1,custom:n.CustomTooltips},maintainAspectRatio:!1,legend:{display:!1},scales:{xAxes:[{display:!1}],yAxes:[{display:!1}]}})}},i=(a("GYLo"),a("KHd+")),l={name:"Dashboard",components:{CardBarChart:Object(i.a)(o,void 0,void 0,!1,null,null,null).exports},data:function(){return{getUserStatsRequest:{loadStatus:0,totalUser:0,last7DayStats:[]}}},created:function(){var t=this;t.getUserStatsRequest.loadStatus=1,s.a.getUserStats().then((function(e){t.getUserStatsRequest.totalUser=e.data.user_stats.total,t.getUserStatsRequest.last7DayStats=e.data.user_stats.last_7_day_stats,t.getUserStatsRequest.loadStatus=2})).catch((function(e){!e.response||401!=e.response.status&&403!=e.response.status?t.getUserStatsRequest.loadStatus=3:t.handleInvalidAuthState(e.response.status)}))}},c=Object(i.a)(l,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("b-card",{staticClass:"text-center",attrs:{header:"Overall Stats","header-class":"text-left"}},[a("b-row",[a("b-col",{attrs:{sm:"6",lg:"3"}},[a("b-card",{staticClass:"bg-success mb-0",attrs:{"no-body":""}},[1==t.getUserStatsRequest.loadStatus?a("div",{staticClass:"middle-center",staticStyle:{height:"152px"}},[a("div",[a("loading",{attrs:{active:!0}})],1)]):2==t.getUserStatsRequest.loadStatus?[a("b-card-body",{staticClass:"pb-0"},[a("h4",{staticClass:"mb-0"},[t._v(t._s(t.getUserStatsRequest.totalUser))]),t._v(" "),a("p",[t._v("Total registered users")])]),t._v(" "),a("card-bar-chart",{staticClass:"chart-wrapper px-3",staticStyle:{height:"70px"},attrs:{data:t.getUserStatsRequest.last7DayStats,label:"New user(s)",backgroundColor:"rgba(255,255,255,.3)",chartId:"card-chart-01",height:"70"}})]:3==t.getUserStatsRequest.loadStatus?a("div",{staticClass:"mb-0 mt-0 middle-center",staticStyle:{height:"152px"}},[t._v("Data load error")]):t._e()],2)],1)],1)],1)],1)}),[],!1,null,null,null);e.default=c.exports}}]);