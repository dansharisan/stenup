<template>
    <div>
        <b-card header="Overall Stats" header-class="text-left" class="text-center">
            <b-row>
                <b-col sm="6" lg="3">
                    <b-card no-body class="bg-success mb-0">
                        <div class="middle-center" v-if="getUserStatsRequest.loadStatus == 1" style="height: 152px">
                            <div>
                                <loading :active="true"></loading>
                            </div>
                        </div>
                        <template v-else-if="getUserStatsRequest.loadStatus == 2">
                            <b-card-body class="pb-0">
                                <h4 class="mb-0">{{ getUserStatsRequest.totalUser }}</h4>
                                <p>Total registered users</p>
                            </b-card-body>
                            <card-bar-chart :data="getUserStatsRequest.last7DayStats" label="New user(s)" backgroundColor="rgba(255,255,255,.3)" chartId="card-chart-01" class="chart-wrapper px-3" style="height:70px;" height="70"/>
                        </template>
                        <div class="mb-0 mt-0 middle-center" v-else-if="getUserStatsRequest.loadStatus == 3" style="height: 152px">Data load error</div>
                    </b-card>
                </b-col>
            </b-row>
        </b-card>
    </div>
</template>

<script>
import UserAPI from '../../../api/user.js'
import CardBarChart from '../components/CardBarChart'
export default {
    name      : 'Dashboard',
    components: {
        CardBarChart,
    },
    data: function () {
        return {
            getUserStatsRequest: {
                loadStatus: 0,
                totalUser: 0,
                last7DayStats: []
            },
        }
    },
    created () {
        var vm = this;
        vm.getUserStatsRequest.loadStatus = 1
        // Get user stats
        UserAPI.getUserStats()
        .then(response => {
            vm.getUserStatsRequest.totalUser = response.data.user_stats.total
            vm.getUserStatsRequest.last7DayStats = response.data.user_stats.last_7_day_stats
            vm.getUserStatsRequest.loadStatus = 2
        })
        .catch(error => {
            // Handle unauthorized error
            if (error.response && error.response.status == 401) {
                vm.handleInvalidAuthState(vm)
            } else {
                vm.getUserStatsRequest.loadStatus = 3
            }
        })
    },
}
</script>
