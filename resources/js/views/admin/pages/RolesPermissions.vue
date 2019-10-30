<template>
    <div class="animated fadeIn">
        <div class="grid-container">
            <loading :is-full-page="false" :active="getRolesAndPermissionsRequest.loadStatus == 1 || getRolesWithPermissionsRequest.loadStatus == 1"></loading>
            <p v-if="getRolesAndPermissionsRequest.loadStatus == 3 || getRolesWithPermissionsRequest.loadStatus == 3" class="text-center mb-0">Data load error</p>
            <div class="grid" v-else-if="getRolesAndPermissionsRequest.loadStatus == 2 && getRolesWithPermissionsRequest.loadStatus == 2">
                <div class="grid-col grid-col--fixed-left">
                    <div class="grid-item grid-item--role-nameer">
                        <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em"><sub>permission</sub>\<sup>role</sup></p>
                    </div>
                    <div class="grid-item">
                        <p class="m-0 ml-1 mr-1" style="line-height: 3em">permission-name</p>
                    </div>
                    <div class="grid-item">
                        <p class="m-0 ml-1 mr-1" style="line-height: 3em">permission-name</p>
                    </div>
                    <div class="grid-item">
                        <p class="m-0 ml-1 mr-1" style="line-height: 3em">permission-name</p>
                    </div>
                    <div class="grid-item">
                        <p class="m-0 ml-1 mr-1" style="line-height: 3em">permission-name</p>
                    </div>
                    <div class="grid-item">
                        <p class="m-0 ml-1 mr-1" style="line-height: 3em">permission-name</p>
                    </div>
                    <div class="grid-item">
                        <p class="m-0 ml-1 mr-1" style="line-height: 3em">permission-name</p>
                    </div>
                    <div class="grid-item">
                        <p class="m-0 ml-1 mr-1" style="line-height: 3em">permission-name</p>
                    </div>
                    <div class="grid-item">
                        <p class="m-0 ml-1 mr-1" style="line-height: 3em">permission-name</p>
                    </div>
                    <div class="grid-item">
                        <p class="m-0 ml-1 mr-1" style="line-height: 3em">permission-name</p>
                    </div>
                    <div class="grid-item">
                        <p class="m-0 ml-1 mr-1" style="line-height: 3em">permission-name</p>
                    </div>
                </div>

                <div class="grid-col">
                    <div class="grid-item grid-item--role-nameer">
                        <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em">role-name</p>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                </div>

                <div class="grid-col">
                    <div class="grid-item grid-item--role-nameer">
                        <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em">role-name</p>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                </div>

                <div class="grid-col">
                    <div class="grid-item grid-item--role-nameer">
                        <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em">role-name</p>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                </div>

                <div class="grid-col">
                    <div class="grid-item grid-item--role-nameer">
                        <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em">role-name</p>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                </div>

                <div class="grid-col">
                    <div class="grid-item grid-item--role-nameer">
                        <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em">role-name</p>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                </div>

                <div class="grid-col">
                    <div class="grid-item grid-item--role-nameer">
                        <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em">role-name</p>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                </div>

                <div class="grid-col">
                    <div class="grid-item grid-item--role-nameer">
                        <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em">role-name</p>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                </div>

                <div class="grid-col">
                    <div class="grid-item grid-item--role-nameer">
                        <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em">role-name</p>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                </div>

                <div class="grid-col">
                    <div class="grid-item grid-item--role-nameer">
                        <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em">role-name</p>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                </div>

                <div class="grid-col">
                    <div class="grid-item grid-item--role-nameer">
                        <p class="m-0 ml-1 mr-1 text-center" style="line-height: 3em">role-name</p>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                    <div class="grid-item">
                        <div class="custom-control form-control-lg custom-checkbox text-center"><input type="checkbox" id="r_id_p_id"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AuthAPI from '../../../api/auth.js'
export default {
    components: {
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
        }
    },
    methods: {
    },
    created () {
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
    },
}
</script>

<style>
.grid-container {
    display: grid; /* This is a (hacky) way to make the .grid element size to fit its content */
    overflow: auto;
    height: 100%;
    width: 100%;
}
.grid {
    display: flex;
    flex-wrap: nowrap;
}
.grid-col {
    width: 150px;
    min-width: 150px;
}

.grid-item--role-nameer {
    height: 50px;
    min-height: 50px;
    position: sticky;
    position: -webkit-sticky;
    background: white;
    top: 0;
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

</style>
