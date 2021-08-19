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
            const params = '?lists=' + listId + '&per_page=100&orderitemsby=order'
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




    },


    computed: {

    },
})