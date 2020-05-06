<template>
    <div>
        <b-modal id="crud-user-modal" ref="crud-user-modal" modal-class="text-left" centered>
            <template v-slot:modal-header="{ close }">
                <h5 class="modal-title" v-if="crudUserRequest.action == 'create'">Create new user</h5>
                <h5 class="modal-title" v-else-if="crudUserRequest.action == 'update'">Update user</h5>
                <button type="button" aria-label="Close" class="close" @click="close()">×</button>
            </template>
            
            <loading :active="crudUserRequest.loadStatus == 1 || rolesAndPermissionsLoadStatus == 1"></loading>
            <p v-if="rolesAndPermissionsLoadStatus == 3" class="text-center mb-0">Data load error</p>
            <template v-else-if="rolesAndPermissionsLoadStatus == 2">
                <b-form-group>
                    <label for="email">Email</label>
                    <b-form-input type="text" placeholder="email@example.com" :class="{'border-danger' : (crudUserRequest.data.validation && crudUserRequest.data.validation.email)}" v-model="crudUserRequest.form.email" v-on:keyup.enter="createUser" />
                    <div class="row">
                        <div class="col-12 invalid-feedback text-left d-block" v-if="crudUserRequest.data.validation && crudUserRequest.data.validation.email">
                            {{ crudUserRequest.data.validation.email[0] }}
                        </div>
                    </div>
                </b-form-group>

                <b-form-group>
                    <label for="password">Password</label>
                    <b-input-group>
                        <b-input v-model="crudUserRequest.form.password" type="password" :class="{'border-danger' : (crudUserRequest.data.validation && crudUserRequest.data.validation.password)}" placeholder="my_p@ssw0rD" v-on:keyup.enter="createUser"/>
                        <b-input-group-append is-text class="item-header-text cursor-pointer" @click="togglePasswordVisibility($event)">
                            <i class="fa fa-eye-slash"></i>
                        </b-input-group-append>
                    </b-input-group>
                    <div class="row">
                        <div class="col-12 invalid-feedback text-left d-block" v-if="crudUserRequest.data.validation && crudUserRequest.data.validation.password">
                            {{ crudUserRequest.data.validation.password[0] }}
                        </div>
                    </div>
                </b-form-group>

                <b-form-group>
                    <label for="email_verified_at">Verified at</label>
                    <b-datepicker 
                        v-model="crudUserRequest.form.email_verified_at" 
                        placeholder="06/15/2020" 
                        today-button
                        reset-button
                        close-button
                        :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                        locale="en"
                    />
                    <div class="row">
                        <div class="col-12 invalid-feedback text-left d-block" v-if="crudUserRequest.data.validation && crudUserRequest.data.validation.email_verified_at">
                            {{ crudUserRequest.data.validation.email_verified_at[0] }}
                        </div>
                    </div>
                </b-form-group>

                <b-form-group label="Roles">
                    <b-form-checkbox
                        v-for="role in rolesAndPermissions.roles"
                        v-model="crudUserRequest.form.role_ids"
                        :key="role.id"
                        :value="role.id"
                        name="roles"
                    >
                        {{ role.name }}
                    </b-form-checkbox>
                    <div class="row">
                        <div class="col-12 invalid-feedback text-left d-block" v-if="crudUserRequest.data.validation && crudUserRequest.data.validation.role_ids">
                            {{ crudUserRequest.data.validation.role_ids[0] }}
                        </div>
                    </div>
                </b-form-group>
            </template>

            <template v-slot:modal-footer>
                <b-button v-if="crudUserRequest.action == 'create'" size="md" class="btn btn-action" variant="success" @click="createUser()">
                    <span class="text-white">Create</span>
                </b-button>
                <b-button v-else-if="crudUserRequest.action == 'update'" size="md" class="btn btn-action" variant="success" @click="updateUser()">
                    <span class="text-white">Update</span>
                </b-button>
            </template>
        </b-modal>

        <b-card header="Users" header-class="text-left" class="text-center">
            <p v-if="listUsersRequest.loadStatus == 3" class="text-center mb-0">Data load error</p>
            <div v-else id="master-table">
                <div class="row justify-content-between">
                    <div class="col-4 mb-3">
                        <b-input-group class="input-group-sm">
                          <b-form-select
                            @input="onChangePerPage"
                            v-model="listUsersRequest.data.per_page"
                            id="per_page"
                            :plain="false"
                            :options="[{ text: '15', value: 15}, { text: '30', value: 30}, { text: '50', value: 50}]"
                            size="md"
                            value="Please select"
                            class="col-12"
                          />
                        </b-input-group>
                    </div>
                    <div class="col-8 text-right mb-3">
                        <b-button v-if="hasPermission(user, PERMISSION_NAME.CREATE_USERS)" size="md" class="btn btn-action" variant="primary" @click="openCRUDModal('create')">
                            <span class="text-white">Create User</span>
                        </b-button>
                    </div>
                </div>

                <b-table
                :hover="true"
                :striped="true"
                :bordered="true"
                :small="false"
                :fixed="false"
                :items="listUsersRequest.data.data"
                :fields="listUsersRequest.tableFields"
                :current-page="listUsersRequest.data.current_page"
                per-page=0
                responsive="md"
                show-empty
                empty-text="There are no records to show"
                :busy="listUsersRequest.loadStatus == 1"
                >
                    <div slot="table-busy" class="align-middle text-center text-info my-2">
                        <loading :active="true" :is-full-page="false"></loading>
                    </div>

                    <!-- <template slot="checkbox" slot-scope="row">
                        <b-form-checkbox
                          :id="'checkbox-item-' + row.item.id"
                          :name="'checkbox-item-' + row.item.id"
                          :data-id="row.item.id"
                          class="checkbox-item"
                          @input="selectItem(row.item)"
                          value="checked"
                          unchecked-value="unchecked"
                        >
                        </b-form-checkbox>
                    </template> -->

                    <template v-slot:cell(status)="cell" v-if="listUsersRequest.data.data && listUsersRequest.data.data.length > 0">
                        <b-badge :variant="getBadge(cell.item.status)">
                            {{ cell.item.status }}
                        </b-badge>
                    </template>
                    <template v-slot:cell(created_at)="cell" v-if="listUsersRequest.data.data && listUsersRequest.data.data.length > 0">
                        {{ cell.item.created_at.slice(0, -8) }}
                    </template>

                    <!-- <template slot="actions" slot-scope="row">
                        <b-button size="md" class="btn-action mt-1 mb-1" variant="warning" @click="prepareEditingItem(row.item)">
                            <i class="fa fa-pencil text-white" aria-hidden="true"></i> <span class="text-white">Edit</span>
                        </b-button>
                        <b-button size="md" class="btn-action mt-1 mb-1" variant="danger" @click="deleteItem(row.item)">
                            <i class="fa fa-trash text-white" aria-hidden="true"></i> <span class="text-white">Delete</span>
                        </b-button>
                    </template> -->
                </b-table>
                <nav v-if="listUsersRequest.loadStatus == 2">
                    <b-pagination
                    v-model="listUsersRequest.data.current_page"
                    :total-rows="listUsersRequest.data.total"
                    :per-page="listUsersRequest.data.per_page"
                    class="mb-2"
                    size="md"
                    >
                    </b-pagination>
                </nav>
            </div>
        </b-card>
    </div>
</template>

<script>
import UserAPI from '../../../api/user.js'
import AuthAPI from '../../../api/auth.js'
import { PERMISSION_NAME } from '../../../const.js'
import { DOMUtils } from '../../../mixins/dom-utils.js'
export default {
    mixins:[
        DOMUtils,
    ],
    data: function () {
        return {
            PERMISSION_NAME: PERMISSION_NAME,
            listUsersRequest: {
                loadStatus: 0,
                data: {
                    per_page: window.localStorage.getItem('per_page') || 15,
                    current_page: 1
                },
                tableFields:  [
                    // { key: 'checkbox', label: ' ' },
                    { key: 'id', label: 'ID' },
                    // { key: 'id', label: 'ID', thClass: 'd-none', tdClass: 'd-none' },  -> this is to hide this column
                    { key: 'email' },
                    { key: 'display_roles', label: 'Role(s)' },
                    { key: 'status', label: 'Status' },
                    { key: 'created_at', label: 'Registration date' },
                    // { key: 'actions' }
                ],
            },
            crudUserRequest: {
                loadStatus: 0,
                action: '',
                data: {},
                form: {
                    email: '',
                    password: '',
                    email_verified_at: null,
                    role_ids: []
                }
            }
        }
    },
    computed: {
        user () {
            return this.$store.get('auth/user');
        },
        rolesAndPermissions() {
            return this.$store.get('auth/rolesAndPermissions');
        },
        rolesAndPermissionsLoadStatus() {
            return this.$store.get('auth/rolesAndPermissionsLoadStatus');
        },
    },
    methods: {
        openCRUDModal(action) {
            var vm = this
            switch(action) {
                case 'create':
                    vm.initCRUDUserModal()
                    break
                case 'update':
                    vm.initCRUDUserModal()
                    break
            }
            // Set action
            vm.crudUserRequest.action = action
            // Open the modal
            vm.$refs['crud-user-modal'].show()
        },
        initCRUDUserModal() {
            this.crudUserRequest.loadStatus = 0
            this.crudUserRequest.action = ''
            this.crudUserRequest.data = {}
            this.crudUserRequest.form = {
                email: '',
                password: '',
                email_verified_at: null,
                role_ids: []
            }
        },
        createUser() {
            var vm = this
            vm.crudUserRequest.loadStatus = 1
            UserAPI.createUser(vm.crudUserRequest.form.email, vm.crudUserRequest.form.email_verified_at, vm.crudUserRequest.form.password, vm.crudUserRequest.form.password, vm.crudUserRequest.form.role_ids.join(','))
            .then((response) => {
                // Reload list of users on the first page
                vm.getUsers(1, vm.listUsersRequest.data.per_page)

                vm.crudUserRequest.data = response.data
                vm.crudUserRequest.loadStatus = 2
                // Close the modal
                vm.$refs['crud-user-modal'].hide()
                // Fire notification
                vm.$snotify.success("Create user successfully")
            })
            .catch(function(error) {
                 // Handle unauthorized error
                if (error.response && (error.response.status == 401 || error.response.status == 403)) {
                    vm.handleInvalidAuthState(error.response.status)
                } else {
                    vm.crudUserRequest.loadStatus = 3
                    if (error && error.response) {
                        vm.crudUserRequest.data = error.response.data
                        vm.$snotify.error(error.response.data.error ? error.response.data.error.message : error.response.data.message)
                    } else {
                        vm.$snotify.error("Network error")
                    }
                }
            })
        },
        updateUser() {
            alert('TO DO')
        },
        getBadge(status) {
            return status === 'Active' ? 'success'
            : status === 'Inactive' ? 'secondary'
            : status === 'Banned' ? 'danger' : 'primary'
        },
        onChangePerPage(newPerPage) {
            // Remember user's preference for per_page
            window.localStorage.setItem('per_page', newPerPage)
        },
        getUsers(page = 1, perPage = 15) {
            var vm = this
            vm.listUsersRequest.loadStatus = 1
            UserAPI.getUsers(page, perPage)
            .then(response => {
                vm.listUsersRequest.data = response.data.users
                vm.listUsersRequest.loadStatus = 2
            })
            .catch(error => {
                // Handle unauthorized error
                if (error.response && (error.response.status == 401 || error.response.status == 403)) {
                    vm.handleInvalidAuthState(error.response.status)
                } else {
                    vm.listUsersRequest.loadStatus = 3
                }
            })
        },
    },
    watch: {
        'listUsersRequest.data.current_page': function (newVal, oldVal) {
            this.getUsers(newVal, this.listUsersRequest.data.per_page)
        },
        'listUsersRequest.data.per_page': function (newVal, oldVal) {
            this.getUsers(1, newVal)
        },
    },
    created() {
        // Initialize CRUD user modal
        this.initCRUDUserModal()
        // Load list of users
        this.getUsers(1, this.listUsersRequest.data.per_page)
        // Preload permissions (will be used when creating/updating an user)
        if (this.rolesAndPermissionsLoadStatus != 2 && this.rolesAndPermissionsLoadStatus != 1) {
            this.$store.dispatch('auth/getRolesAndPermissions')
        }
    },
}
</script>
