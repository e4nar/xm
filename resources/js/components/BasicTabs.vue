<template>
    <nav class="nav">
        <a class="nav-link" :class="{'active': activeTab == 'form' }" @click="showTab('form')">Form</a>
        <a class="nav-link" :class="{'active': activeTab == 'results' }" @click="showTab('table')">Table</a>
        <a class="nav-link" :class="{'active': activeTab == 'chart' }" @click="showTab('chart')">Chart</a>
    </nav>
</template>

<script>
    export default {
        name: "BasicTabs",

        props: ['value'],

        data() {
            return {
                activeTab: 'form'
            };
        },

        methods: {

            showTab: function (selectedTab) {
                this.activeTab = selectedTab;
                this.$emit('input', this.activeTab);
            }

        }
    }
</script>