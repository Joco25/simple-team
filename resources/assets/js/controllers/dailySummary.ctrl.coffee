<template>
    <div class="row">
        <div class="col-sm-12">
            <h1>Daily Summary</h1>

            <div class="row">
                <div class="col-sm-6">
                    Saturday, 25 Jul 2015
                </div>
                <div class="col-sm-6 text-right">
                    <div class="btn-group">
                        <button class="btn btn-default">&lt;</button>
                        <button class="btn btn-default">Today</button>
                        <button class="btn btn-default">&gt;</button>
                    </div>

                    <input type="date">
                </div>
            </div>

            <div class="panel">
                <div class="panel-heading">Michael J. Calkins</div>
                <div class="panel-body">
                    <div class="form-group">
                        <input
                            type="text"
                            class="form-control"
                            v-model="dailySummaryBody"
                            placeholder="Today I..." v-on="keyup:createDailySummary | key 'enter'">
                    </div>

                    <table class="table">
                        <tr v-repeat="dailySummary: dailySummaries">
                            <td>
                                <div
                                    v-show="dailySummary != dailySummaryCopy"
                                    v-on="dblclick: editDailySummary(dailySummary)">{{dailySummary.body}}</div>
                                <div v-show="dailySummary == dailySummaryCopy">
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="dailySummaryCopy.body"
                                        v-summary-focus="dailySummary == dailySummaryCopy"
                                        v-on="
                                            blur: updateDailySummary(dailySummary),
    										keyup: updateDailySummary(dailySummary) | key 'enter',
    										keyup: cancelDailySummary(dailySummary) | key 'esc'
                                        ">
                                </div>
                            </td>
                            <td class="text-right">
                                <button class="btn btn-primary" v-on="click: editDailySummary(dailySummary)">Edit</button>
                                <button class="btn btn-danger" v-on="click: deleteDailySummary(dailySummary)">Delete</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
var Vue = require('Vue')

module.exports = {
    data: function () {
        return {
            dailySummaryBody: '',
            dailySummaries: [],
            dailySummaryCopy: {}
        }
    },

    ready: function() {
        this.loadDailySummaries()
    },

    methods: {
        loadDailySummaries: function() {
            var _this = this
            this.$http.get('/api/dailySummaries', function(data) {
                _this.dailySummaries = data.dailySummaries
            })
        },

        deleteDailySummary: function(dailySummary) {
            if ( ! confirm("Delete this daily summary?")) return

            var _this = this
            this.$http.delete('/api/dailySummaries/' + dailySummary.id, function(data) {
                _this.dailySummaries.$remove(dailySummary)
            })
        },

        editDailySummary: function(dailySummary) {
            this.dailySummaryCopy = dailySummary
        },

        updateDailySummary: function(dailySummary) {
            var _this = this
            this.$http.put('/api/dailySummaries/' + dailySummary.id, {
                body: dailySummary.body
            }, function(data) {
                _this.dailySummaries = data.dailySummaries
            })
            _this.dailySummaryCopy = {}
        },

        cancelDailySummary: function() {
            this.dailySummaryCopy = {}
        },

        createDailySummary: function() {
            var _this = this
            this.$http.post('/api/dailySummaries', {
                body: this.dailySummaryBody
            }, function(data) {
                _this.dailySummaries.unshift(data.dailySummary)
            })
            _this.dailySummaryBody = ''
        }
    },

    directives: {
		'summary-focus': function (value) {
			if (!value) {
				return;
			}
			var el = this.el;
			Vue.nextTick(function () {
				el.focus();
			});
		}
	}
}
</script>
