<template>
    <div class="basic-form">
        <div class="jumbotron text-center">
            <h1>My XM application</h1>
            <p>Fetches data from API endpoint and display result in table</p>
        </div>

        <div class="row">
            <basic-tabs v-model="activeTab"></basic-tabs>
        </div>
        <div class="row form-row" v-if="activeTab == 'form'">

            <div class="loader" :class="{'d-none': !doingRequest}">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div class="col-lg-12">
                <form id="xm_form" class="needs-validation">

                    <div class="form-group required">
                        <label for="company_symbol">Company Symbol:</label>

                        <select id="company_symbol" v-model="selected.symbol" class="form-control" required>
                            <option value="" selected></option>
                            <option v-for="symbolOption in symbolOptions"
                                    :key="symbolOption.symbol"
                                    :value="symbolOption.symbol">{{ symbolOption.company_name }}</option>
                        </select>

                        <div class="invalid-feedback">Select a company symbol</div>
                    </div>

                    <div class="form-group required">
                        <label for="start_date">Start Date:</label>

                        <input type="input" id="start_date" v-model="selected.startDate" class="form-control" placeholder="Enter start date" required>

                        <div class="invalid-feedback">Please enter a valid date</div>
                    </div>

                    <div class="form-group required">
                        <label for="end_date">End Date:</label>

                        <input type="input" id="end_date" v-model="selected.endDate" class="form-control" placeholder="Enter end date" required>

                        <div class="invalid-feedback">Please enter a valid date</div>
                    </div>

                    <div class="form-group required">
                        <label for="email">Email address:</label>

                        <input type="text" class="form-control" v-model="selected.email" placeholder="Enter email" id="email" pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>

                        <div class="invalid-feedback">Please enter a valid email</div>
                    </div>

                    <button type="button" class="btn btn-default" @click="resetForm">Reset</button>
                    <button type="button" class="btn btn-primary" @click="getResults">Submit</button>

                </form>
            </div>
        </div>

        <div class="row" v-if="activeTab == 'table'">
            <basic-table :prop-results="stockResults"></basic-table>
        </div>

        <div class="row" v-if="activeTab == 'chart'">
            <basic-chart :prop-results="stockResults"></basic-chart>
        </div>

    </div>
</template>

<script>
    import BasicTabs from "./BasicTabs";
    import BasicTable from "./BasicTable";
    import BasicChart from "./BasicChart";

    export default {
        name: "BasicForm",

        components: {BasicChart, BasicTable, BasicTabs},

        props: {
            'symbol-options' : {
                type: Array,
                required: true
            }
        },

        data() {
            return {
                activeTab: 'form',
                selected: {
                    symbol: '',
                    startDate: '',
                    endDate: '',
                    email: 'elias@gearsoft.gr'
                },
                doingRequest: false,
                stockResults: []
            }
        },

        methods: {
            resetForm() {
                // get form element
                var form = document.getElementById("xm_form");

                // reset form
                form.reset();

                // remove class that was-validated
                form.classList.remove('was-validated');

                // initialize array
                this.selected = {
                    symbol: '',
                    startDate: '',
                    endDate: '',
                    email: ''
                };
            },

            getResults: function() {
                // get form element
                var form = document.getElementById("xm_form");

                //add class to know that it is validated
                form.classList.add('was-validated');

                // does validation
                if (form.checkValidity() === false) {
                    return;
                }

                this.doingRequest = true;

                this.$http.get(URLS.API_GET_HISTORICAL_DATA, {params: this.selected}).then(response => {

                    this.doingRequest = false;
                    console.log(response);
                    if (response.body.status == true) {

                        this.stockResults = response.body.data;
                        this.activeTab = 'table';

                    } else {
                        this.stockResults = [];
                        alert('Error: ' + response.body.error);
                    }

                }, response => {

                    this.doingRequest = false;
                    console.log('error');

                });
            }
        }
    }
</script>
