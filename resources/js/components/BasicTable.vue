<template>
    <div class="col-lg-12 results-table">
        <table class="table table-striped table-hover table-sm">
            <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Open</th>
                <th scope="col">High</th>
                <th scope="col">Low</th>
                <th scope="col">Close</th>
                <th scope="col">Volume</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="stockResult in paginatedStockResults">
                    <td>{{ stockResult.date }}</td>
                    <td>{{ stockResult.open }}</td>
                    <td>{{ stockResult.high }}</td>
                    <td>{{ stockResult.low }}</td>
                    <td>{{ stockResult.close }}</td>
                    <td>{{ stockResult.volume }}</td>
                </tr>
                <tr v-if="_.isEmpty(stockResults)">
                    <td class="no-results" colspan="6">No results found</td>
                </tr>
            </tbody>
        </table>

        <nav aria-label="Page navigation example" v-if="totalPages > 1">
            <ul class="pagination">

                <li class="page-item" :class="{disabled: (currentPage == 1)}">
                    <a class="page-link" aria-label="First" @click="setPage(1)">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">First</span>
                    </a>
                </li>

                <li class="page-item" :class="{disabled: (currentPage == 1)}">
                    <a class="page-link" aria-label="Previous" @click="setPage(currentPage - 1)">
                        <span aria-hidden="true">Previous</span>
                        <span class="sr-only">Previous</span>
                    </a>
                </li>

                <li class="page-item" :class="{active: page == currentPage}" v-for="page in totalPages">
                    <a class="page-link" @click="setPage(page)">{{ page }}</a>
                </li>

                <li class="page-item" :class="{disabled: (currentPage == totalPages)}">
                    <a class="page-link" aria-label="Next" @click="setPage(currentPage + 1)">
                        <span aria-hidden="true">Next</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>

                <li class="page-item" :class="{disabled: (currentPage == totalPages)}">
                    <a class="page-link" aria-label="Last" @click="setPage(totalPages)">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Last</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    export default {
        name: "BasicTable",

        props: {
            'prop-results' : {
                type: Array,
                required: true
            }
        },

        data() {
            return {
                currentPage: 1,
                itemsPerPage: 10,
            }
        },

        computed: {
            stockResults() {

                //return value from prop variable
                if (this.propResults) {
                    return this.propResults;
                }

                //or return empty array
                return [];
            },

            totalPages() {
                //compute the total number of pages
                return Math.ceil(this.stockResults.length / this.itemsPerPage);
            },

            paginatedStockResults() {

                var startIndex = (this.currentPage - 1) * this.itemsPerPage;
                var endIndex   = startIndex + this.itemsPerPage;

                var _stockRes = [];
                _.forEach(this.stockResults, function(stockdayResult, key) {

                    if (key >= startIndex && key <= endIndex) {
                        return _stockRes.push(stockdayResult);

                    } else if (key > endIndex) {
                        // stop iteration
                        return false;
                    }

                });


                return _stockRes;
            }
        },

        methods: {
            //set current page
            setPage: function (page) {
                this.currentPage = page;
            },
        }
    }
</script>

<style scoped>

</style>