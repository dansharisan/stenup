<template>
    <div>
        <b-modal id="create-user-modal" modal-class="text-left" centered title="Create new user" @ok="createOrUpdateUser" ok-variant="success" ref="create-role-modal">
            <loading :active="crudUserRequest.loadStatus == 1"></loading>
            <b-form-group>
                <label for="email">Email</label>
                <b-form-input type="text" placeholder="email@example.com" :class="{'border-danger' : (crudUserRequest.data.validation && crudUserRequest.data.validation.email)}" v-model="crudUserRequest.form.email" v-on:keyup.enter="createOrUpdateUser" />
                <div class="row">
                    <div class="col-12 invalid-feedback text-left d-block" v-if="crudUserRequest.data.validation && crudUserRequest.data.validation.email">
                        {{ crudUserRequest.data.validation.email[0] }}
                    </div>
                </div>
            </b-form-group>

            <b-form-group>
                <label for="password">Password</label>
                <b-input-group>
                    <b-input v-model="crudUserRequest.form.email" type="password" :class="{'border-danger' : (crudUserRequest.data.validation && crudUserRequest.data.validation.email)}" placeholder="my_p@ssw0rD" v-on:keyup.enter="submit"/>
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
                    placeholder="2020/06/15" 
                    :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                    locale="ja"
                />
                <div class="row">
                    <div class="col-12 invalid-feedback text-left d-block" v-if="crudUserRequest.data.validation && crudUserRequest.data.validation.email_verified_at">
                        {{ crudUserRequest.data.validation.email_verified_at[0] }}
                    </div>
                </div>
            </b-form-group>

            <b-form-group label="Roles">
                <b-form-checkbox
                    v-for="option in [{text: 'administrator', value: 1}, {text: 'moderator', value: 2}, {text: 'member', value: 3}]"
                    v-model="crudUserRequest.form.role_ids"
                    :key="option.value"
                    :value="option.value"
                    name="roles"
                >
                    {{ option.text }}
                </b-form-checkbox>
                <div class="row">
                    <div class="col-12 invalid-feedback text-left d-block" v-if="crudUserRequest.data.validation && crudUserRequest.data.validation.role_ids">
                        {{ crudUserRequest.data.validation.role_ids[0] }}
                    </div>
                </div>
            </b-form-group>
        </b-modal>

        <b-card header="Users" header-class="text-left" class="text-center">
            <p v-if="crudUserRequest.loadStatus == 3" class="text-center mb-0">Data load error</p>
            <div v-else id="master-table">
                <div class="row justify-content-between">
                    <div class="col-2 mb-3">
                        <b-input-group class="input-group-sm">
                          <b-form-select
                            @input="onChangePerPage"
                            v-model="crudUserRequest.tablePerPage"
                            id="per_page"
                            :plain="false"
                            :options="[{ text: '15', value: 15}, { text: '30', value: 30}, { text: '50', value: 50}]"
                            size="md"
                            value="Please select"
                            class="col-12"
                          />
                        </b-input-group>
                    </div>
                    <div class="col-2 text-right mb-3">
                        <b-button v-if="hasPermission(user, PERMISSION_NAME.CREATE_USERS)" size="md" class="btn btn-action" variant="primary" v-b-modal.create-user-modal>
                            <i class="fas fa-plus text-white" aria-hidden="true"></i> <span class="text-white">Create User</span>
                        </b-button>
                    </div>
                </div>

                <b-table
                :hover="true"
                :striped="true"
                :bordered="true"
                :small="false"
                :fixed="false"
                :items="crudUserRequest.data.data"
                :fields="crudUserRequest.tableFields"
                :current-page="crudUserRequest.tableCurrentPage"
                per-page=0
                responsive="md"
                show-empty
                empty-text="There are no records to show"
                :busy="crudUserRequest.loadStatus == 1"
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

                    <template v-slot:cell(status)="cell" v-if="crudUserRequest.data.data && crudUserRequest.data.data.length > 0">
                        <b-badge :variant="getBadge(cell.item.status)">
                            {{ cell.item.status }}
                        </b-badge>
                    </template>
                    <template v-slot:cell(created_at)="cell" v-if="crudUserRequest.data.data && crudUserRequest.data.data.length > 0">
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
                <nav v-if="crudUserRequest.loadStatus == 2">
                    <b-pagination
                    v-model="crudUserRequest.tableCurrentPage"
                    :total-rows="crudUserRequest.data.total"
                    :per-page="crudUserRequest.data.per_page"
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
import { PERMISSION_NAME } from '../../../const.js'
import { DOMUtils } from '../../../mixins/dom-utils.js'
export default {
    mixins:[
        DOMUtils,
    ],
    data: function () {
        return {
            PERMISSION_NAME: PERMISSION_NAME,
            crudUserRequest: {
                loadStatus: 0,
                data: {},
                form: {
                    email: '',
                    password: '',
                    email_verified_at: null,
                    role_ids: []
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
                tablePerPage: window.localStorage.getItem('per_page') || 15,
                tableCurrentPage: 1
            },
        }
    },
    computed: {
        user () {
            return this.$store.get('auth/user');
        },
    },
    methods: {
        createOrUpdateUser() {
            alert('TO DO')
        },
        getBadge (status) {
            return status === 'Active' ? 'success'
            : status === 'Inactive' ? 'secondary'
            : status === 'Banned' ? 'danger' : 'primary'
        },
        onChangePerPage (newPerPage) {
            // Remember user's preference for per_page
            window.localStorage.setItem('per_page', newPerPage)
        },
        getUsers (page = 1, perPage = 15) {
            var vm = this
            vm.crudUserRequest.loadStatus = 1
            UserAPI.getUsers(page, perPage)
            .then(response => {
                vm.crudUserRequest.data = response.data.users
                vm.crudUserRequest.loadStatus = 2
            })
            .catch(error => {
                // Handle unauthorized error
                if (error.response && error.response.status == 401) {
                    vm.handleInvalidAuthState(vm)
                } else {
                    vm.crudUserRequest.loadStatus = 3
                }
            })
        },
    },
    watch: {
        'crudUserRequest.tableCurrentPage': function (newVal, oldVal) {
            // Request to change data, but not on the first load
            if (oldVal) {
                this.getUsers(newVal, this.crudUserRequest.tablePerPage)
            }
        },
        'crudUserRequest.tablePerPage': function (newVal, oldVal) {
            // Request to change data, but not on the first load
            if (oldVal) {
                this.getUsers(1, newVal)
            }
        },
    },
    created () {
        var vm = this
        vm.getUsers()
    },
}
</script>
