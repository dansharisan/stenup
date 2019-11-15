<template>
    <div class="animated fadeIn">
        <b-modal id="create-role-modal" modal-class="text-left" centered title="Create new role" @ok="createRole" ref="create-role-modal">
            <loading :active="createRoleRequest.loadStatus == 1"></loading>
            <template>
                <div class="invalid-feedback d-block" v-if="createRoleRequest.message">
                    {{ createRoleRequest.message }}
                </div>
                <b-form-group>
                    <label for="company">Role name</label>
                    <b-form-input type="text" placeholder="developer" v-model="createRoleRequest.form.role_name" v-on:input="$v.createRoleRequest.form.role_name.$touch()" :state="$v.createRoleRequest.form.role_name.$dirty ? !$v.createRoleRequest.form.role_name.$error : null" />
                </b-form-group>
                <div class="row">
                    <div class="col-12 invalid-feedback text-left d-block" v-if="createRoleRequest.data.validation && createRoleRequest.data.validation.role_name">
                        {{ createRoleRequest.data.validation.role_name[0] }}
                    </div>
                </div>
            </template>
        </b-modal>

        <div class="col-12 text-right pr-0 mb-4">
            <b-button size="md" class="btn btn-action" variant="primary" v-b-modal.create-role-modal>
                <i class="fas fa-plus text-white" aria-hidden="true"></i> <span class="text-white">Create Role</span>
            </b-button>
        </div>

        <div class="grid-container">
            <div class="middle-center" v-if="getRolesAndPermissionsRequest.loadStatus == 1 || getRolesWithPermissionsRequest.loadStatus == 1">
                <div>
                    <loading :active="true" :is-full-page="false"></loading>
                </div>
            </div>
            <p v-else-if="getRolesAndPermissionsRequest.loadStatus == 3 || getRolesWithPermissionsRequest.loadStatus == 3" class="text-center mb-0">Data load error</p>
            <template v-else-if="getRolesAndPermissionsRequest.loadStatus == 2 && getRolesWithPermissionsRequest.loadStatus == 2">
                <div class="grid" style="margin: auto">
                    <div class="grid-col grid-col--fixed-left">
                        <div class="grid-item grid-item--role-name">
                            <p class="m-0 ml-1 mr-1 text-center" style="line-height: 2.5em; font-size: large"><sub>permission</sub>\<sup>role</sup></p>
                        </div>
                        <template v-for="(permission, permissionIndex) in getRolesAndPermissionsRequest.data.permissions">
                            <div class="grid-item grid-item--permission-name">
                                <abbr :title="permission.name"><p class="m-0 ml-1 mr-1" style="line-height: 3em">{{ permission.name }}</p></abbr>
                            </div>
                        </template>
                    </div>
                    <div class="grid-col" v-for="(role, roleIndex) in getRolesAndPermissionsRequest.data.roles">
                        <div class="grid-item grid-item--role-name">
                            <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em">{{ role.name }}</p>
                        </div>
                        <div class="grid-item" v-for="(permission, permissionIndex) in getRolesAndPermissionsRequest.data.permissions">
                            <div class="custom-control form-control-lg text-center">
                                <input v-if="roleHasPermission(role.id, permission.id) && 1 == role.id" type="checkbox" checked disabled class="custom-checkbox" :id="'r_' + role.id + '_p_' + permission.id">
                                <input v-else-if="!roleHasPermission(role.id, permission.id) && 1 == role.id" type="checkbox" disabled class="custom-checkbox" :id="'r_' + role.id + '_p_' + permission.id">
                                <input v-else-if="roleHasPermission(role.id, permission.id)" type="checkbox" checked class="custom-checkbox" :id="'r_' + role.id + '_p_' + permission.id">
                                <input v-else type="checkbox" class="custom-checkbox" :id="'r_' + role.id + '_p_' + permission.id">
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="col-12 text-right pr-0" style="margin-top: 1.5rem">
            <b-button size="md" class="btn btn-action" variant="secondary" @click="reload()">
                <i class="fas fa-undo-alt text-white" aria-hidden="true"></i> <span class="text-white">Reload</span>
            </b-button>
            <b-button size="md" class="btn btn-action" variant="success">
                <i class="fas fa-check text-white" aria-hidden="true"></i> <span class="text-white">Apply</span>
            </b-button>
        </div>
    </div>
</template>

<script>
import AuthAPI from '../../../api/auth.js'
import { required } from 'vuelidate/lib/validators'

export default {
    validations () {
        return {
            createRoleRequest: {
                form: {
                    role_name: { required }
                },
            }
        }
    },
    data: function () {
        return {
            getRolesAndPermissionsRequest: {
                loadStatus: 0,
                data: {}
            },
            getRolesWithPermissionsRequest: {
                loadStatus: 0,
                data: {}
            },
            createRoleRequest: {
                loadStatus: 0,
                data: {},
                form: {
                    role_name: ''
                },
                message: ''
            }
        }
    },
    methods: {
        createRole(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault()

            var vm = this
            vm.createRoleRequest.loadStatus = 1
            AuthAPI.createRole(vm.createRoleRequest.form.role_name)
            .then((response) => {
                // Reload the matrix
                vm.loadMatrixData()

                vm.createRoleRequest.data = response.data
                vm.createRoleRequest.loadStatus = 2
                // Close the modal
                vm.$refs['create-role-modal'].hide()
                // Fire notification
                vm.$snotify.success("Created successfully")
            })
            .catch( function(error) {
                vm.createRoleRequest.loadStatus = 3
                if (error && error.response) {
                    vm.createRoleRequest.data = error.response.data
                    let msg = error.response.data.error ? error.response.data.error.message : error.response.data.message;
                    vm.$snotify.error("Failed to create this role: " + msg)
                } else {
                    vm.$snotify.error("Network error")
                }
            })
        },
        roleHasPermission(roleId, permissionId) {
            var hasRole = false

            for (let role of this.getRolesWithPermissionsRequest.data.roles) {
                if (role.id == roleId) {
                    for (let permission of role.permissions) {
                        if (permission.pivot.permission_id == permissionId) {
                            hasRole = true
                            break
                        }
                    }
                    break
                }
            }

            return hasRole
        },
        reload() {
            // Reload the matrix data
            this.loadMatrixData()
        },
        loadMatrixData() {
            var vm = this
            // Get all roles and permissions
            vm.getRolesAndPermissionsRequest.loadStatus = 1
            AuthAPI.getRolesAndPermissions()
            .then((response) => {
                vm.getRolesAndPermissionsRequest.data = response.data
                vm.getRolesAndPermissionsRequest.loadStatus = 2
            })
            .catch( function( e ) {
                vm.getRolesAndPermissionsRequest.data = {}
                vm.getRolesAndPermissionsRequest.loadStatus = 3
            })
            // Get all roles with associated permissions
            vm.getRolesWithPermissionsRequest.loadStatus = 1
            AuthAPI.getRolesWithPermissions()
            .then((response) => {
                vm.getRolesWithPermissionsRequest.data = response.data
                vm.getRolesWithPermissionsRequest.loadStatus = 2
            })
            .catch( function( e ) {
                vm.getRolesWithPermissionsRequest.data = {}
                vm.getRolesWithPermissionsRequest.loadStatus = 3
            })
        }
    },
    created () {
        this.loadMatrixData()
    },
}
</script>

<style>
.grid-container {
    display: grid; /* This is a (hacky) way to make the .grid element size to fit its content */
    overflow: auto;
    min-height: 550px;
    max-height: calc(100vh - 202px - 7.5rem);
    width: 100%;
}
.grid {
    display: flex;
    flex-wrap: nowrap;
}
.grid-col {
    width: 160px;
    min-width: 160px;
}

 .grid-item--permission-name {
    font-weight: bolder;
 }

 .grid-item--permission-name p {
     text-overflow: ellipsis;
     white-space: nowrap;
     overflow: hidden;
}

.grid-item--role-name {
    z-index: 1;
    height: 50px;
    min-height: 50px;
    position: sticky;
    position: -webkit-sticky;
    background: white;
    top: 0;
    font-weight: bolder;
}

.grid-col--fixed-left {
    position: sticky;
    left: 0;
    z-index: 1000;
    background: white;
}
.grid-col--fixed-right {
    position: sticky;
    right: 0;
    z-index: 1000;
    background: white;
}

.grid-item {
    height: 50px;
    border: 1px solid gray;
}

.custom-checkbox {
    width: 30px;
    height: 30px;
}
</style>
