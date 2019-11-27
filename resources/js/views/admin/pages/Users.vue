<template>
    <div class="animated fadeIn">
        <b-card header="Users" header-class="text-left" class="text-center">
            <p v-if="crudUsersRequest.loadStatus == 3" class="text-center mb-0">Data load error</p>
            <div v-else id="master-table">
                <div class="row justify-content-between">
                    <div class="col-2 pl-0 pr-0 mb-3 mt-2 ml-3">
                        <b-input-group class="input-group-sm">
                          <b-form-select
                            @input="onChangePerPage"
                            v-model="crudUsersRequest.tablePerPage"
                            id="per_page"
                            :plain="false"
                            :options="[{ text: '15', value: 15}, { text: '30', value: 30}, { text: '50', value: 50}]"
                            size="md"
                            value="Please select"
                            class="col-12"
                          />
                        </b-input-group>
                    </div>
                    <!-- <div class="col-8 text-right mb-3 mt-2 pr-0 pl-2">
                        <b-button size="sm" class="btn-action" variant="danger" @click="deleteItems()" v-if="hasChecked">
                            <i class="fa fa-remove text-white" aria-hidden="true"></i> <span class="text-white">Delete</span>
                        </b-button>
                        <b-button size="sm" class="btn-action" variant="primary" @click="prepareCreatingItem()" v-b-modal.edit-form-modal>
                            <i class="fa fa-file text-white" aria-hidden="true"></i> <span class="text-white">Create</span>
                        </b-button>
                    </div> -->
                </div>

                <b-table
                :hover="true"
                :striped="true"
                :bordered="true"
                :small="false"
                :fixed="false"
                :items="crudUsersRequest.data.data"
                :fields="crudUsersRequest.tableFields"
                :current-page="crudUsersRequest.tableCurrentPage"
                per-page=0
                responsive="md"
                show-empty
                empty-text="There are no records to show"
                :busy="crudUsersRequest.loadStatus == 1"
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

                    <template v-slot:cell(status)="cell" v-if="crudUsersRequest.data.data && crudUsersRequest.data.data.length > 0">
                        <b-badge :variant="getBadge(cell.item.status)">
                            {{ cell.item.status }}
                        </b-badge>
                    </template>
                    <template v-slot:cell(created_at)="cell" v-if="crudUsersRequest.data.data && crudUsersRequest.data.data.length > 0">
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
                <nav v-if="crudUsersRequest.loadStatus == 2">
                    <b-pagination
                    v-model="crudUsersRequest.tableCurrentPage"
                    :total-rows="crudUsersRequest.data.total"
                    :per-page="crudUsersRequest.data.per_page"
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
export default {
    data: function () {
        return {
            crudUsersRequest: {
                loadStatus: 0,
                data: {},
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
    methods: {
        getBadge (status) {
            return status === 'Active' ? 'success'
            : status === 'Inactive' ? 'secondary'
            : status === 'Banned' ? 'danger' : 'primary'
        },
        onChangePerPage (newPerPage) {
            // Remember user's preference for per_page
            window.localStorage.setItem('per_page', newPerPage)
        },
        getUsers(page = 1, perPage = 15) {
            var vm = this
            vm.crudUsersRequest.loadStatus = 1
            UserAPI.getUsers(page, perPage)
            .then(response => {
                vm.crudUsersRequest.data = response.data.users
                vm.crudUsersRequest.loadStatus = 2
            })
            .catch(error => {
                vm.crudUsersRequest.loadStatus = 3
            })
        },
    },
    watch: {
        'crudUsersRequest.tableCurrentPage': function (newVal, oldVal) {
            // Request to change data, but not on the first load
            if (oldVal) {
                this.getUsers(newVal, this.crudUsersRequest.tablePerPage)
            }
        },
        'crudUsersRequest.tablePerPage': function (newVal, oldVal) {
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
