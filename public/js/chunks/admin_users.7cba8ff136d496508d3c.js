(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{"1pTT":function(e,t,s){"use strict";var a=s("lYuH");t.a={getUsers:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:25;return axios.get(a.a.API_URL+"/users?page="+e+"&per_page="+t)},banUser:function(e){return axios.patch(a.a.API_URL+"/users/"+e+"/ban")},unbanUser:function(e){return axios.patch(a.a.API_URL+"/users/"+e+"/unban")},deleteUser:function(e){return axios.delete(a.a.API_URL+"/users/"+e)},deleteUsers:function(e){return axios.post(a.a.API_URL+"/users/collection:batchDelete",{ids:e})},editUser:function(e,t,s){return axios.patch(a.a.API_URL+"/users/"+e,{email_verified_at:t,role_ids:s})},createUser:function(e,t,s,r,i){return axios.post(a.a.API_URL+"/users",{email:e,password:s,password_confirmation:r,role_ids:i,email_verified_at:t})},getUserStats:function(){return axios.get(a.a.API_URL+"/users/registered_user_stats")}}},EG5p:function(e,t,s){"use strict";s.r(t);var a=s("1pTT"),r=(s("nlWZ"),s("lYuH")),i={mixins:[s("I958").a],data:function(){return{PERMISSION_NAME:r.c,listUsersRequest:{loadStatus:0,data:{per_page:window.localStorage.getItem("per_page")||15,current_page:1},tableFields:[{key:"id",label:"ID"},{key:"email"},{key:"display_roles",label:"Role(s)"},{key:"status",label:"Status"},{key:"created_at",label:"Registration date"}]},crudUserRequest:{loadStatus:0,action:"",data:{},form:{email:"",password:"",email_verified_at:null,role_ids:[]}}}},computed:{user:function(){return this.$store.get("auth/user")},rolesAndPermissions:function(){return this.$store.get("auth/rolesAndPermissions")},rolesAndPermissionsLoadStatus:function(){return this.$store.get("auth/rolesAndPermissionsLoadStatus")}},methods:{openCRUDModal:function(e){switch(e){case"create":case"update":this.initCRUDUserModal()}this.crudUserRequest.action=e,this.$refs["crud-user-modal"].show()},initCRUDUserModal:function(){this.crudUserRequest.loadStatus=0,this.crudUserRequest.action="",this.crudUserRequest.data={},this.crudUserRequest.form={email:"",password:"",email_verified_at:null,role_ids:[]}},createUser:function(){var e=this;e.crudUserRequest.loadStatus=1,a.a.createUser(e.crudUserRequest.form.email,e.crudUserRequest.form.email_verified_at,e.crudUserRequest.form.password,e.crudUserRequest.form.password,e.crudUserRequest.form.role_ids.join(",")).then((function(t){e.getUsers(1,e.listUsersRequest.data.per_page),e.crudUserRequest.data=t.data,e.crudUserRequest.loadStatus=2,e.$refs["crud-user-modal"].hide(),e.$snotify.success("Create user successfully")})).catch((function(t){!t.response||401!=t.response.status&&403!=t.response.status?(e.crudUserRequest.loadStatus=3,t&&t.response?(e.crudUserRequest.data=t.response.data,e.$snotify.error(t.response.data.error?t.response.data.error.message:t.response.data.message)):e.$snotify.error("Network error")):e.handleInvalidAuthState(t.response.status)}))},updateUser:function(){alert("TO DO")},getBadge:function(e){return"Active"===e?"success":"Inactive"===e?"secondary":"Banned"===e?"danger":"primary"},onChangePerPage:function(e){window.localStorage.setItem("per_page",e)},getUsers:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:15,s=this;s.listUsersRequest.loadStatus=1,a.a.getUsers(e,t).then((function(e){s.listUsersRequest.data=e.data.users,s.listUsersRequest.loadStatus=2})).catch((function(e){!e.response||401!=e.response.status&&403!=e.response.status?s.listUsersRequest.loadStatus=3:s.handleInvalidAuthState(e.response.status)}))}},watch:{"listUsersRequest.data.current_page":function(e,t){this.getUsers(e,this.listUsersRequest.data.per_page)},"listUsersRequest.data.per_page":function(e,t){this.getUsers(1,e)}},created:function(){this.initCRUDUserModal(),this.getUsers(1,this.listUsersRequest.data.per_page),2!=this.rolesAndPermissionsLoadStatus&&1!=this.rolesAndPermissionsLoadStatus&&this.$store.dispatch("auth/getRolesAndPermissions")}},o=s("KHd+"),n=Object(o.a)(i,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("b-modal",{ref:"crud-user-modal",attrs:{id:"crud-user-modal","modal-class":"text-left",centered:""},scopedSlots:e._u([{key:"modal-header",fn:function(t){var a=t.close;return["create"==e.crudUserRequest.action?s("h5",{staticClass:"modal-title"},[e._v("Create new user")]):"update"==e.crudUserRequest.action?s("h5",{staticClass:"modal-title"},[e._v("Update user")]):e._e(),e._v(" "),s("button",{staticClass:"close",attrs:{type:"button","aria-label":"Close"},on:{click:function(e){return a()}}},[e._v("×")])]}},{key:"modal-footer",fn:function(){return["create"==e.crudUserRequest.action?s("b-button",{staticClass:"btn btn-action",attrs:{size:"md",variant:"success"},on:{click:function(t){return e.createUser()}}},[s("span",{staticClass:"text-white"},[e._v("Create")])]):"update"==e.crudUserRequest.action?s("b-button",{staticClass:"btn btn-action",attrs:{size:"md",variant:"success"},on:{click:function(t){return e.updateUser()}}},[s("span",{staticClass:"text-white"},[e._v("Update")])]):e._e()]},proxy:!0}])},[e._v(" "),s("loading",{attrs:{active:1==e.crudUserRequest.loadStatus||1==e.rolesAndPermissionsLoadStatus}}),e._v(" "),3==e.rolesAndPermissionsLoadStatus?s("p",{staticClass:"text-center mb-0"},[e._v("Data load error")]):2==e.rolesAndPermissionsLoadStatus?[s("b-form-group",[s("label",{attrs:{for:"email"}},[e._v("Email")]),e._v(" "),s("b-form-input",{class:{"border-danger":e.crudUserRequest.data.validation&&e.crudUserRequest.data.validation.email},attrs:{type:"text",placeholder:"email@example.com"},on:{keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.createUser(t)}},model:{value:e.crudUserRequest.form.email,callback:function(t){e.$set(e.crudUserRequest.form,"email",t)},expression:"crudUserRequest.form.email"}}),e._v(" "),s("div",{staticClass:"row"},[e.crudUserRequest.data.validation&&e.crudUserRequest.data.validation.email?s("div",{staticClass:"col-12 invalid-feedback text-left d-block"},[e._v("\n                        "+e._s(e.crudUserRequest.data.validation.email[0])+"\n                    ")]):e._e()])],1),e._v(" "),s("b-form-group",[s("label",{attrs:{for:"password"}},[e._v("Password")]),e._v(" "),s("b-input-group",[s("b-input",{class:{"border-danger":e.crudUserRequest.data.validation&&e.crudUserRequest.data.validation.password},attrs:{type:"password",placeholder:"my_p@ssw0rD"},on:{keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.createUser(t)}},model:{value:e.crudUserRequest.form.password,callback:function(t){e.$set(e.crudUserRequest.form,"password",t)},expression:"crudUserRequest.form.password"}}),e._v(" "),s("b-input-group-append",{staticClass:"item-header-text cursor-pointer",attrs:{"is-text":""},on:{click:function(t){return e.togglePasswordVisibility(t)}}},[s("i",{staticClass:"fa fa-eye-slash"})])],1),e._v(" "),s("div",{staticClass:"row"},[e.crudUserRequest.data.validation&&e.crudUserRequest.data.validation.password?s("div",{staticClass:"col-12 invalid-feedback text-left d-block"},[e._v("\n                        "+e._s(e.crudUserRequest.data.validation.password[0])+"\n                    ")]):e._e()])],1),e._v(" "),s("b-form-group",[s("label",{attrs:{for:"email_verified_at"}},[e._v("Verified at")]),e._v(" "),s("b-datepicker",{attrs:{placeholder:"06/15/2020","today-button":"","reset-button":"","close-button":"","date-format-options":{year:"numeric",month:"2-digit",day:"2-digit"},locale:"en"},model:{value:e.crudUserRequest.form.email_verified_at,callback:function(t){e.$set(e.crudUserRequest.form,"email_verified_at",t)},expression:"crudUserRequest.form.email_verified_at"}}),e._v(" "),s("div",{staticClass:"row"},[e.crudUserRequest.data.validation&&e.crudUserRequest.data.validation.email_verified_at?s("div",{staticClass:"col-12 invalid-feedback text-left d-block"},[e._v("\n                        "+e._s(e.crudUserRequest.data.validation.email_verified_at[0])+"\n                    ")]):e._e()])],1),e._v(" "),s("b-form-group",{attrs:{label:"Roles"}},[e._l(e.rolesAndPermissions.roles,(function(t){return s("b-form-checkbox",{key:t.id,attrs:{value:t.id,name:"roles"},model:{value:e.crudUserRequest.form.role_ids,callback:function(t){e.$set(e.crudUserRequest.form,"role_ids",t)},expression:"crudUserRequest.form.role_ids"}},[e._v("\n                    "+e._s(t.name)+"\n                ")])})),e._v(" "),s("div",{staticClass:"row"},[e.crudUserRequest.data.validation&&e.crudUserRequest.data.validation.role_ids?s("div",{staticClass:"col-12 invalid-feedback text-left d-block"},[e._v("\n                        "+e._s(e.crudUserRequest.data.validation.role_ids[0])+"\n                    ")]):e._e()])],2)]:e._e()],2),e._v(" "),s("b-card",{staticClass:"text-center",attrs:{header:"Users","header-class":"text-left"}},[3==e.listUsersRequest.loadStatus?s("p",{staticClass:"text-center mb-0"},[e._v("Data load error")]):s("div",{attrs:{id:"master-table"}},[s("div",{staticClass:"row justify-content-between"},[s("div",{staticClass:"col-2 mb-3"},[s("b-input-group",{staticClass:"input-group-sm"},[s("b-form-select",{staticClass:"col-12",attrs:{id:"per_page",plain:!1,options:[{text:"15",value:15},{text:"30",value:30},{text:"50",value:50}],size:"md",value:"Please select"},on:{input:e.onChangePerPage},model:{value:e.listUsersRequest.data.per_page,callback:function(t){e.$set(e.listUsersRequest.data,"per_page",t)},expression:"listUsersRequest.data.per_page"}})],1)],1),e._v(" "),s("div",{staticClass:"col-2 text-right mb-3"},[e.hasPermission(e.user,e.PERMISSION_NAME.CREATE_USERS)?s("b-button",{staticClass:"btn btn-action",attrs:{size:"md",variant:"primary"},on:{click:function(t){return e.openCRUDModal("create")}}},[s("span",{staticClass:"text-white"},[e._v("Create User")])]):e._e()],1)]),e._v(" "),s("b-table",{attrs:{hover:!0,striped:!0,bordered:!0,small:!1,fixed:!1,items:e.listUsersRequest.data.data,fields:e.listUsersRequest.tableFields,"current-page":e.listUsersRequest.data.current_page,"per-page":"0",responsive:"md","show-empty":"","empty-text":"There are no records to show",busy:1==e.listUsersRequest.loadStatus},scopedSlots:e._u([e.listUsersRequest.data.data&&e.listUsersRequest.data.data.length>0?{key:"cell(status)",fn:function(t){return[s("b-badge",{attrs:{variant:e.getBadge(t.item.status)}},[e._v("\n                        "+e._s(t.item.status)+"\n                    ")])]}}:null,e.listUsersRequest.data.data&&e.listUsersRequest.data.data.length>0?{key:"cell(created_at)",fn:function(t){return[e._v("\n                    "+e._s(t.item.created_at.slice(0,-8))+"\n                ")]}}:null],null,!0)},[s("div",{staticClass:"align-middle text-center text-info my-2",attrs:{slot:"table-busy"},slot:"table-busy"},[s("loading",{attrs:{active:!0,"is-full-page":!1}})],1)]),e._v(" "),2==e.listUsersRequest.loadStatus?s("nav",[s("b-pagination",{staticClass:"mb-2",attrs:{"total-rows":e.listUsersRequest.data.total,"per-page":e.listUsersRequest.data.per_page,size:"md"},model:{value:e.listUsersRequest.data.current_page,callback:function(t){e.$set(e.listUsersRequest.data,"current_page",t)},expression:"listUsersRequest.data.current_page"}})],1):e._e()],1)])],1)}),[],!1,null,null,null);t.default=n.exports},I958:function(e,t,s){"use strict";s.d(t,"a",(function(){return a}));var a={methods:{togglePasswordVisibility:function(e){var t=$(e.currentTarget),s=t.closest(".input-group").find("input").first();"password"==s.attr("type")?(s.attr("type","text"),t.find("svg").toggleClass("fa-eye-slash fa-eye")):(s.attr("type","password"),t.find("svg").toggleClass("fa-eye-slash fa-eye"))}}}}}]);