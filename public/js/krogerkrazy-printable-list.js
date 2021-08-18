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

        showConfirmDelete(id, deleteType) {
            let _this = this
            let msg
            msg = 'Please confirm that you want to delete this List.'
            const opts = {
                title: 'Please Confirm',
                size: 'sm',
                buttonSize: 'sm',
                okVariant: 'danger',
                okTitle: 'YES',
                cancelTitle: 'NO',
                footerClass: 'p-2',
                hideHeaderClose: false,
                centered: true,
                noCloseOnBackdrop: true,
                noCloseOnEsc: true,
            }
            this.$bvModal.msgBoxConfirm(msg, opts)
                .then(value => {
                    if (value) {
                        if (deleteType === 'list') {
                            _this.deleteRecord(id, endpoint = 'lists', dataset = _this.dynamicLists, force = true)
                        }
                        if (deleteType === 'listItem') {
                            this.deleteListItem(id)
                        }
                    }
                })
                .catch(err => {
                    // An error occurred
                })
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