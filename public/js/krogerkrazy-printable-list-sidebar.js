const printOptions = {
    name: '_blank',
    specs: [
        'fullscreen=yes',
        'titlebar=yes',
        'scrollbars=yes'
    ],
    styles: [
        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
        'https://unpkg.com/kidlat-css/css/kidlat.css'
    ],
    timeout: 1000, // default timeout before the print window appears
    autoClose: true, // if false, the window will not close after printing
    windowTitle: window.document.title, // override the window title
}

Vue.use(VueHtmlToPaper, printOptions)

let vmSidebar = new Vue({
    el: '#kkSidebar',
    data() {
        return {
            addedHeadings: [],
            heading: '',
            showListActions: false,
        }
    },

    created() {

    },

    mounted() {

    },

    methods: {

        async printList() {
            await this.$htmlToPaper('preparedListForPrint');
        },


        async emailList(e) {

            e.preventDefault()

            let formData = new FormData(e.target)
            formData.append('action', 'email_list')
            formData.append('printableList', JSON.stringify(this.printableList))
            formData.append('customPrintableList', JSON.stringify(this.customPrintableList))
            formData.append('_wpnonce', kk_ajax_obj.nonce)
            fetch(kk_ajax_obj.ajaxurl, {
                method: 'POST',
                credentials: 'same-origin',
                body: formData
            })
                .then(res => {
                    return res.json()
                })
                .then(data => {
                    if (!data.mail_sent) {
                        alert('Something went wrong with the mailer, please try again later.')
                    }
                })
                .then(finish => {
                    this.$refs.customCouponEmailFormModalRef.hide()
                    this.forceRerenderForm()
                })
        },

        addCustomCoupon: function (e) {
            e.preventDefault()
            const formData = new FormData(e.target)
            const values = [...formData.values()]

            this.$localStorage.customListItems.unshift({'title': values[0], 'price': values[1], 'content': values[2]})

            this.$refs.customCouponFormModalRef.hide()

            this.forceRerenderForm()
        },

        deleteItems() {
            if( ! confirm("Do you really want to delete your list items?") ){
                e.preventDefault();
            } else {
                this.$localStorage.savedListItems = []
            }

        },


    },

    computed: {
        customPrintableList: function () {
            let storedList = this.$localStorage.customListItems
            return storedList
        },
        printableList: function () {
            let storedList = this.$localStorage.savedListItems
            let sortedStoredList = storedList.sort((a, b) => {
                if (a.heading < b.heading) {
                    return -1;
                }
                if (a.heading > b.heading) {
                    return 1;
                }
                return 0;
            })
            sortedStoredList.forEach((item, i) => {
                if (item.is_heading === 'true') {
                    this.addedHeadings.push(item.heading)
                    return
                }
                if (this.heading !== item.heading && !this.addedHeadings.includes(item.heading)) {
                    this.heading = item.heading
                    this.addedHeadings.push(item.heading)
                    sortedStoredList.splice(i, 0, {title: item.heading, is_heading: 'true', heading: item.heading})
                }
            })
            return sortedStoredList
        }
    },
})