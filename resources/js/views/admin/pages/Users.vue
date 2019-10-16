<template>
    <div class="animated fadeIn">
        <vue-bootstrap4-table :vc="this" :rows="rows" :columns="columns" :config="config" :classes="classes" :actions="actions" @on-export="exportMultiRows">
        </vue-bootstrap4-table>
    </div>
</template>

<script>
import VueBootstrap4Table from 'vue-bootstrap4-table'
import UserAPI from '../../../api/user.js'
export default {
    name      : 'User',
    components: {
    },
    data: function () {
        return {
            getUsersRequest: {
                loadStatus: 0,
                data: {}
            },
            usersTable: {
                columns: [

                ],
                actions: [
                    {
                        btn_text: "Export as CSV",
                        class: "btn btn-success",
                        event_name: "on-export",
                        event_payload: {
                            msg: "TODO: Export as CSV"
                        }
                    }
                ],
                config: {
                    show_refresh_button: false,
                    show_reset_button: false,
                    pagination: true,
                    pagination_info: true,
                    num_of_visibile_pagination_buttons: 7,
                    per_page: 10,
                    highlight_row_hover: true,
                    rows_selectable: false,
                    multi_column_sort: false,
                    // highlight_row_hover_color:"grey",
                    card_title: "Users",
                    card_mode: false,
                    selected_rows_info: false,
                    checkbox_rows: false,
                    per_page_options: [50, 100],
                    global_search: {
                        placeholder: "Search",
                    }
                },
                classes: {
                    table: "table-bordered table-striped small-font-size"
                }
            }
        }
    },
    methods: {
        getUsers(page = 1, perPage = 15) {
            var vm = this
            vm.getUsersRequest.loadStatus = 1
            UserAPI.getUsers(page, perPage)
            .then(response => {
                vm.getUsersRequest.data = response.data.users
                vm.getUsersRequest.loadStatus = 2
            })
            .catch(error => {
                vm.getUsersRequest.loadStatus = 3
            })
        },
    },
    created () {
        var vm = this
        vm.getUsers()
    },
}
</script>
