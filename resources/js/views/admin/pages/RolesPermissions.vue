<template>
    <div>
        <b-modal id="create-role-modal" modal-class="text-left" centered title="Create new role" @ok="createRole" ok-only ok-title="Create" ok-variant="success" ref="create-role-modal">
            <loading :active="crudRoleRequest.loadStatus == 1"></loading>
            <b-form-group>
                <label for="role_name">Role name</label>
                <b-form-input type="text" placeholder="developer" :class="{'border-danger' : (crudRoleRequest.data.validation && crudRoleRequest.data.validation.role_name)}" v-model="crudRoleRequest.form.role_name" v-on:keyup.enter="createRole" />
                <div class="row">
                    <div class="col-12 invalid-feedback text-left d-block" v-if="crudRoleRequest.data.validation && crudRoleRequest.data.validation.role_name">
                        {{ crudRoleRequest.data.validation.role_name[0] }}
                    </div>
                </div>
            </b-form-group>
        </b-modal>

        <b-card header="Roles-Permissions Matrix" header-class="text-left" class="text-center">
            <div class="col-12 text-right pr-0 mb-3">
                <b-button v-if="hasPermission(user, PERMISSION_NAME.CREATE_ROLES)" size="md" class="btn btn-action" variant="primary" v-b-modal.create-role-modal>
                    <i class="fas fa-plus text-white" aria-hidden="true"></i> <span class="text-white">Create Role</span>
                </b-button>
            </div>

            <div class="grid-container">
                <div class="middle-center" style="position: inherit" v-if="getRolesAndPermissionsRequest.loadStatus == 1 || getRolesWithPermissionsRequest.loadStatus == 1">
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
                            <div class="grid-item grid-item--permission-name" v-for="permission in getRolesAndPermissionsRequest.data.permissions" :key="permission.id">
                                <abbr :title="permission.name"><p class="m-0 ml-1 mr-1" style="line-height: 3em">{{ permission.name }}</p></abbr>
                            </div>
                        </div>
                        <div class="grid-col" v-for="role in getRolesAndPermissionsRequest.data.roles" :key="role.id">
                            <div class="grid-item grid-item--role-name">
                                <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em">
                                    {{ role.name }}
                                    <template v-if="1 != role.id && hasPermission(user, PERMISSION_NAME.DELETE_ROLES)">
                                        <b-button size="sm" class="btn btn-action" variant="danger" @click="deleteRole(role.id)">
                                            <i class="fas fa-trash text-white" aria-hidden="true"></i>
                                        </b-button>
                                    </template>
                                </p>
                            </div>
                            <div class="grid-item" v-for="permission in getRolesAndPermissionsRequest.data.permissions" :key="permission.id">
                                <div class="custom-control form-control-lg text-center">
                                    <input v-if="roleHasPermission(role.id, permission.id) && (1 == role.id || !hasPermission(user, PERMISSION_NAME.UPDATE_PERMISSIONS))" type="checkbox" checked disabled class="custom-checkbox" :id="'r_' + role.id + '_p_' + permission.id">
                                    <input v-else-if="!roleHasPermission(role.id, permission.id) && (1 == role.id || !hasPermission(user, PERMISSION_NAME.UPDATE_PERMISSIONS))" type="checkbox" disabled class="custom-checkbox" :id="'r_' + role.id + '_p_' + permission.id">
                                    <input v-else-if="roleHasPermission(role.id, permission.id) && hasPermission(user, PERMISSION_NAME.UPDATE_PERMISSIONS)" type="checkbox" checked class="custom-checkbox" :id="'r_' + role.id + '_p_' + permission.id">
                                    <input v-else type="checkbox" class="custom-checkbox" :id="'r_' + role.id + '_p_' + permission.id">
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="col-12 text-right pr-0 mt-3" v-if="hasPermission(user, PERMISSION_NAME.UPDATE_PERMISSIONS)">
                <b-button size="md" class="btn btn-action" variant="secondary" @click="reload()">
                    <i class="fas fa-undo-alt text-white" aria-hidden="true"></i> <span class="text-white">Reload</span>
                </b-button>
                <b-button size="md" class="btn btn-action" variant="success" @click="applyPermissions()">
                    <i class="fas fa-check text-white" aria-hidden="true"></i> <span class="text-white">Apply</span>
                </b-button>
            </div>
        </b-card>
    </div>
</template>

<script>
import AuthAPI from '../../../api/auth.js'
import { PERMISSION_NAME } from '../../../const.js'
export default {
    data: function() {
        return {
            PERMISSION_NAME: PERMISSION_NAME,
            getRolesAndPermissionsRequest: {
                loadStatus: 0,
                data: {}
            },
            getRolesWithPermissionsRequest: {
                loadStatus: 0,
                data: {}
            },
            crudRoleRequest: {
                loadStatus: 0,
                data: {},
                form: {
                    role_name: ''
                }
            }
        }
    },
    computed: {
        user() {
            return this.$store.get('auth/user');
        },
    },
    methods: {
        applyPermissions() {
            var vm = this
            this.$swal({
                title: 'You sure to apply this role-permission matrix?',
                text: "This action can't be undone. However you can use administrator account to re-set permissions for all roles.",
                icon: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#4dbd74',
                cancelButtonColor: '#a4b7c1',
                confirmButtonText: 'Apply'
            }).then((result) => {
                if (result.value) {
                    // vm.getRolesAndPermissionsRequest.data.permissions
                    // vm.getRolesAndPermissionsRequest.data.roles
                    var matrix = new Object()
                    // Prepare roles permissions matrix to send
                    for (var roleObj of vm.getRolesAndPermissionsRequest.data.roles) {
                        // Initialize the role object we need to send
                        matrix[roleObj.name] = []

                        // Extract the id of permission on each checkbox id using regex
                        var thisRolePerms = $('input[id^=r_' + roleObj.id + ']:checked')
                        for (var checkedPermEl of thisRolePerms) {
                            // Here the permission id
                            var regex = /r_\d+_p_(\d+)/gm
                            var permId = regex.exec(checkedPermEl.id)[1]
                            // Find permission name
                            for (var permObj of vm.getRolesAndPermissionsRequest.data.permissions) {
                                if (permObj.id == permId) {
                                    matrix[roleObj.name].push(permObj.name)
                                }
                            }
                        }
                    }

                    // Send the matrix to server
                    vm.getRolesWithPermissionsRequest.loadStatus = 1
                    AuthAPI.updateRolesPermissionsMatrix(matrix)
                    .then((response) => {
                        vm.getRolesWithPermissionsRequest.data = response.data
                        vm.getRolesWithPermissionsRequest.loadStatus = 2
                        // Fire notification
                        vm.$snotify.success("Applied roles-permissions matrix successfully")
                    })
                    .catch( function(error) {
                        // Handle unauthorized error
                        if (error.response && error.response.status == 401) {
                            vm.handleInvalidAuthState(vm)
                        }
                        // vm.getRolesWithPermissionsRequest.data = {}
                        // vm.getRolesWithPermissionsRequest.loadStatus = 3
                        vm.loadRolesWithPermissions()
                        // Fire notification
                        vm.$snotify.error("Failed to apply roles-permissions matrix")
                    })
                }
            })
        },
        createRole(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault()

            var vm = this
            vm.crudRoleRequest.loadStatus = 1
            AuthAPI.createRole(vm.crudRoleRequest.form.role_name)
            .then((response) => {
                // Reload the matrix
                vm.loadMatrixData()

                vm.crudRoleRequest.data = response.data
                vm.crudRoleRequest.loadStatus = 2
                // Close the modal
                vm.$refs['create-role-modal'].hide()
                // Fire notification
                vm.$snotify.success("Created role successfully")
            })
            .catch( function(error) {
                // Handle unauthorized error
                if (error.response && error.response.status == 401) {
                    vm.handleInvalidAuthState(vm)
                } else {
                    vm.crudRoleRequest.loadStatus = 3
                    if (error && error.response) {
                        vm.crudRoleRequest.data = error.response.data
                        vm.$snotify.error(error.response.data.error ? error.response.data.error.message : error.response.data.message)
                    } else {
                        vm.$snotify.error("Network error")
                    }
                }
            })
        },
        deleteRole(roleId) {
            var vm = this

            this.$swal({
                title: 'You sure to delete this role?',
                text: "This action can't be undone. You might need to set roles for members who were associated with this role.",
                icon: 'warning',
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonColor: '#f86c6b',
                cancelButtonColor: '#a4b7c1',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.value) {
                    vm.crudRoleRequest.loadStatus = 1
                    AuthAPI.deleteRole(roleId)
                    .then((response) => {
                        // Reload the matrix
                        vm.loadMatrixData()
                        vm.crudRoleRequest.data = response.data
                        vm.crudRoleRequest.loadStatus = 2
                        // Fire notification
                        vm.$snotify.success("Deleted role successfully")
                    })
                    .catch(function(error) {
                        // Handle unauthorized error
                        if (error.response && error.response.status == 401) {
                            vm.handleInvalidAuthState(vm)
                        } else {
                            vm.crudRoleRequest.loadStatus = 3
                            if (error && error.response) {
                                vm.crudRoleRequest.data = error.response.data
                                vm.$snotify.error(error.response.data.error ? error.response.data.error.message : error.response.data.message)
                            } else {
                                vm.$snotify.error("Network error")
                            }
                        }
                    })
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
            vm.loadRolesAndPermissions()
            // Get all roles with associated permissions
            vm.loadRolesWithPermissions()
        },
        loadRolesAndPermissions() {
            var vm = this
            vm.getRolesAndPermissionsRequest.loadStatus = 1
            AuthAPI.getRolesAndPermissions()
            .then((response) => {
                vm.getRolesAndPermissionsRequest.data = response.data
                vm.getRolesAndPermissionsRequest.loadStatus = 2
            })
            .catch(function(error) {
                // Handle unauthorized error
                if (error.response && error.response.status == 401) {
                    vm.handleInvalidAuthState(vm)
                } else {
                    vm.getRolesAndPermissionsRequest.data = {}
                    vm.getRolesAndPermissionsRequest.loadStatus = 3
                }
            })
        },
        loadRolesWithPermissions() {
            var vm = this
            vm.getRolesWithPermissionsRequest.loadStatus = 1
            AuthAPI.getRolesWithPermissions()
            .then((response) => {
                vm.getRolesWithPermissionsRequest.data = response.data
                vm.getRolesWithPermissionsRequest.loadStatus = 2
            })
            .catch(function(error) {
                // Handle unauthorized error
                if (error.response && error.response.status == 401) {
                    vm.handleInvalidAuthState(vm)
                } else {
                    vm.getRolesWithPermissionsRequest.data = {}
                    vm.getRolesWithPermissionsRequest.loadStatus = 3
                }
            })
        }
    },
    created() {
        this.loadMatrixData()
    },
}
</script>

<style>
.grid-container {
    display: grid; /* This is a (hacky) way to make the .grid element size to fit its content */
    overflow: auto;
    min-height: 100%;
    max-height: calc(100vh - 298px - 6rem);
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
