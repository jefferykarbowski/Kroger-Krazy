Vue.prototype.$localStorage = new Vue({
    data: {
        savedListItems: JSON.parse(window.localStorage.getItem('savedListItems')),
        customListItems: JSON.parse(window.localStorage.getItem('customListItems')),
    },
    watch:{
        savedListItems(value) {
            window.localStorage.setItem('savedListItems', JSON.stringify(value))
        },
        customListItems(value) {
            window.localStorage.setItem('customListItems', JSON.stringify(value))
        },
    }
})



Vue.mixin({

    data() {
        return {

        }
    },

    mounted() {
        if(!this.$localStorage.savedListItems)
            this.$localStorage.savedListItems = []
        if(!this.$localStorage.customListItems)
            this.$localStorage.customListItems = []
    },

    methods: {

        toUppercase(value) {
            return value.toUpperCase()
        },

        removeItem(i) {
            this.$localStorage.savedListItems.splice(i, 1)
        },

        removeCustomItem(i) {
            this.$localStorage.customListItems.splice(i, 1)
        },

        forceRerenderForm() {
            this.componentKey += 1
        },

        scrollToTop() {
            window.scrollTo(0,0)
        },

    },



    watch: {


    }
})


