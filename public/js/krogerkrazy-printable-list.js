Vue.filter('uppercase', (value) => {
    return value.toUpperCase()
})

let vm = new Vue({
    el: '#kkPrintableList',
    data() {
        return {
            listId: null,
            componentKey: 0,
            listItems: [],
            allSelected: false,
            indeterminate: false
        }
    },

    created() {

    },

    mounted() {
        this.listId = this.$el.getAttribute('list-id')
        if (this.listId)
            this.fetchListItems(this.listId).catch(error => {
                console.error(error)
            })
    },

    methods: {

        async fetchListItems(listId) {
            const params = '?lists=' + listId + '&per_page=1000&orderitemsby=order'
            this.listItems = await fetch('/kk_api/wp/v2/list_items' + params)
                .then(res => {
                    return res.json()
                })
                .then(listItems => listItems)
        },

        submit: function () {
            this.$refs.form.submit()
        },

        forceRerenderForm() {
            this.componentKey += 1
        },


        htmlDecode(input) {
            let doc = new DOMParser().parseFromString(input, "text/html")
            return doc.documentElement.textContent
        },


        openSidebar() {
            vmSidebar.isSidebarOpen = true
        },

        toggleAll(checked) {
            this.$localStorage.savedListItems = checked ? this.listItems.slice() : []
        }


    },


    computed: {

    },

    watch: {

        selected(newValue, oldValue) {
            if (newValue.length === 0) {
                this.indeterminate = false
                this.allSelected = false
            } else if (newValue.length === this.listItems.length) {
                this.indeterminate = false
                this.allSelected = true
            } else {
                this.indeterminate = true
                this.allSelected = false
            }
        }

    },
})