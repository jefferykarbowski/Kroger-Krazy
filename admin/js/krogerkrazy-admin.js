const createSortable = (el, options, vnode) => {
    return Sortable.create(el, {
        ...options
    })
}

const sortable = {
    name: 'sortable',
    bind(el, binding, vnode) {
        const table = el
        table._sortable = createSortable(table.querySelector("tbody"), binding.value, vnode)
    }
}

const dollarSign = '$'
const emptyString = ''
const comma = ','
const period = '.'
const minus = '-'
const minusRegExp = /-/
const nonDigitsRegExp = /\D+/g
const number = 'number'
const digitRegExp = /\d/
const caretTrap = '[]'

Vue.use(VueQuillEditor)
Vue.use(VueMask.VueMaskPlugin)


let vm = new Vue({
    el: '#vueApp',
    components: {
        draggable: window['vuedraggable']
    },
    directives: {sortable},
    data() {
        return {
            listName: '',
            listExpiry: '',
            listUpdating: false,
            expires: '',
            lists: [],
            dynamicLists: [],
            listSharing: false,
            listItems: [],
            tempItems: [],
            selectedListToImport: '',
            createdRecord: [],
            listId: '',
            selectedPastList: 'Current List',
            pastLists: ['Current List', '30 Days +', '60 Days +', '90 Days +', '120 Days +', 'My Archived Lists',],
            selectedItem: 1,
            sortableOptions: {
                chosenClass: 'is-selected'
            },
            fields: [
                {
                    key: 'name',
                    label: 'List Name',
                    sortable: true,
                    tdClass: 'w-50',
                },
                {
                    key: 'shortcode',
                    label: 'Shortcode',
                    sortable: false,
                    tdClass: 'w-50',
                },
                {
                    key: 'meta.updated',
                    label: 'Updated',
                    sortable: true,
                },
                {
                    key: 'buttons',
                    label: '',
                    tdClass: 'no-wrap',
                },
            ],
            itemsFields: [
                {
                    key: 'price',
                    label: 'Price',
                    tdClass: 'no-wrap',
                },
                {
                    key: 'final',
                    label: 'Final',
                    tdClass: 'no-wrap',
                },
                {
                    key: 'title',
                    label: 'Title',
                    tdClass: 'no-wrap',
                },
                {
                    key: 'description',
                    label: 'Description',
                    tdClass: 'w-50',
                },
            ],
            filter: null,
            filterOn: [],
            itemsFilter: null,
            itemsFilterOn: [],
            componentKey: 0,
            modelConfig: {
                type: 'string',
                mask: 'YYYY-MM-DD', // Uses 'iso' if missing
            },
            quillOptions: {
                theme: 'snow',
                modules: {
                    imageResize: {},
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        ['link', 'image'],
                        [{'list': 'ordered'}, {'list': 'bullet'}]
                    ],
                    clipboard: {
                        magicPasteLinks: true,
                    }
                }
            },
            sortItemsByForm: {
                sortBy: null,
                sortByDirection: 'dsc',
            },
        }
    },


    created() {


    },

    mounted() {
        this.fetchLists().catch(error => {
            console.error(error)
        })

    },


    methods: {
        async fetchLists() {
            const params = '?per_page=100'
            this.lists = await fetch('/kk_api/wp/v2/lists' + params)
                .then(res => {
                    return res.json()
                })
                .then(lists => lists)
            this.lists.forEach(element => {
                this.dynamicLists.push({...element, sharing: false})
            })
        },

        async fetchListItems(listId, append = false) {

            const params = '?lists=' + listId + '&per_page=100&orderitemsby=order'
            let fetchedListItems = []
            fetchedListItems = await fetch('/kk_api/wp/v2/list_items' + params)
                .then(res => {
                    return res.json()
                })
            if (append) {
                filteredListItems = []
                filteredListItems = fetchedListItems.filter(item => item.sharing.indexOf('true') > -1)

                filteredListItems.forEach((item, i) => {
                    this.duplicateListItem(item, i)
                })
                // this.listItems = this.listItems.concat(filteredListItems)
                this.$root.$emit("bv::hide::modal", "import-list-items-modal")
                this.forceRerenderForm()
            } else {
                this.listItems = fetchedListItems
            }

            if ( this.listItems.every( item => item.sharing === 'true') )
               this.listSharing = true
        },

        submit: function () {
            this.$refs.form.submit()
        },


        addList(e) {
            e.preventDefault()
            const formData = new FormData(e.target)
            const date = new Date()
            formData.append('status', 'publish')
            formData.append('meta[updated]', ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear())
            this.createRecord('lists', this.dynamicLists, formData)
            this.forceRerenderForm()
        },


        duplicateList(item, sharingItemsOnly = false) {
            const formData = new FormData()
            const date = new Date()
            let newName = item.name + '-' + Date.now()
            formData.append('status', 'publish')
            formData.append('name', item.name)
            formData.append('slug', newName)
            formData.append('meta[updated]', ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear())
            this.createRecord('lists', this.dynamicLists, formData, '', null, item.id, sharingItemsOnly)
            this.forceRerenderForm()
        },

        importList(e) {
            e.preventDefault()
            let item = this.dynamicLists.find(item => item.id === this.selectedListToImport)
            if (this.listItems === []) {
                this.duplicateList(item, true)
            }
            this.$root.$emit("bv::hide::modal", "import-list-modal")
            this.forceRerenderForm()
        },


        updateList(e) {
            e.preventDefault()
            let formData = new FormData(e.target)
            const date = new Date()
            formData.append('meta[updated]', ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear())
            this.updateRecord(formData.get('id'), 'lists', this.dynamicLists, formData)
            this.forceRerenderForm()
            this.dynamicLists.forEach(item => {
                this.$set(item, '_showDetails', false)
            })

        },


        deleteList(id) {
            let formData = new FormData()
            this.deleteRecord(id, 'lists', this.dynamicLists, formData)
        },


        showListItemsModal(id) {
            this.listItems = []
            this.listId = id
            this.fetchListItems(id).then(() => {
                this.$root.$emit("bv::show::modal", "modal-list-items")
            }).catch(error => {
                console.error(error)
            })
        },


        addListItem(is_heading = 'false', i = null) {
            const formData = new FormData()
            formData.append('status', 'publish')
            formData.append('title', '')
            formData.append('price', '')
            formData.append('final_price', '')
            formData.append('is_heading', is_heading)
            formData.append('appended', '')
            formData.append('content', '')
            formData.append('lists', this.listId)
            formData.append('order', 0)
            formData.append('sharing', 'false')
            const params = ''
            if (i) {
                let heading, index = i
                // Get Heading
                for ( ; index >= 0; index--) {
                    if (this.listItems[index].is_heading === 'true') {
                        heading = this.listItems[index]
                        break
                    }
                }
                formData.append('heading', heading.title.rendered)
                i = i + 1
            }
            this.createRecord('list_items', this.listItems, formData, params, i)
            this.updateListItemsOrder()
        },


        updateListItem(id, target) {
            let formData = new FormData()
            if (target.quill) {
                formData.append('content', target.html)
            } else {
                formData.append(target.name, target.value)
            }
            this.updateRecord(id, 'list_items', this.listItems, formData)
        },

        shareListItem(item, sharing) {
            console.log(sharing)
            item.sharing = sharing
            let formData = new FormData()
            formData.append('sharing', sharing)
            this.updateRecord(item.id, 'list_items', this.listItems, formData)
        },


        toggleSharing() {
            if (!this.listSharing) {
                this.listItems.forEach((item, i) => {
                    if (item.sharing === 'false') {
                        this.shareListItem(item, 'true')
                    }
                })
                this.listSharing = true
            } else {
                this.listItems.forEach((item, i) => {
                    if (item.sharing === 'true') {
                        this.shareListItem(item, 'false')
                    }
                })
                this.listSharing = false
            }
        },


        duplicateListItem(item, i) {
            let newName = item.title.rendered + '-' + Date.now()
            const formData = new FormData()
            formData.append('status', 'publish')
            formData.append('title', item.title.rendered)
            formData.append('slug', newName)
            formData.append('price', item.price)
            formData.append('final_price', item.final_price)
            formData.append('is_heading', item.is_heading)
            formData.append('heading', item.heading)
            formData.append('appended', item.appended)
            formData.append('content', item.content.rendered)
            formData.append('lists', this.listId)
            formData.append('order', i + 1)
            formData.append('sharing', 'false')
            const params = ''

            this.createRecord('list_items', this.listItems, formData, params, i + 1)
            this.updateListItemsOrder()
        },


        importListItems(e) {
            e.preventDefault()
            this.fetchListItems(this.selectedListToImport, true)
            this.$root.$emit("bv::hide::modal", "import-modal")
            this.forceRerenderForm()
        },


        async importHeadings() {
            const params = '?per_page=100'
            this.lists = await fetch('/kk_api/wp/v2/list_headings' + params)
                .then(res => {
                    return res.json()
                })
                .then((headings, i) => {
                    headings.forEach(heading => {
                        const formData = new FormData()
                        formData.append('status', 'publish')
                        formData.append('title', heading.name)
                        formData.append('is_heading', 'true')
                        formData.append('lists', this.listId)
                        formData.append('order', 0)
                        formData.append('sharing', 'false')
                        this.createRecord('list_items', this.listItems, formData, params, i)

                    })
                })
                .then(() => this.updateListItemsOrder())
        },


        sortListItems(e) {
            e.preventDefault()
            let headingIndexes = []
            this.sortItemsBy = this.sortItemsByForm.sortBy
            this.listItems.forEach((item, i) => {
                if (item.is_heading === 'true') {
                    headingIndexes.push(i)
                }
            })
            headingIndexes.forEach((headingIndex, i, sortItemsBy) => {
                let sliced = this.listItems.slice(headingIndexes[i] + 1, headingIndexes[i + 1])
                sliced = sliced.sort((a, b)=> {

                    if (this.sortItemsByForm.sortBy === 'title') {
                        if (a.title.rendered > b.title.rendered)
                            return (this.sortItemsByForm.sortByDirection === 'asc' ? -1 : 1)
                        if (b.title.rendered > a.title.rendered)
                            return (this.sortItemsByForm.sortByDirection === 'asc' ? 1 : -1)
                        return 0
                    } else {
                        return (
                            this.sortItemsByForm.sortByDirection === 'asc' ?
                                a[this.sortItemsByForm.sortBy] - b[this.sortItemsByForm.sortBy] : b[this.sortItemsByForm.sortBy] - a[this.sortItemsByForm.sortBy])
                    }

                })
                this.listItems.splice(headingIndex+1, sliced.length, ...sliced)
            })
            this.updateListItemsOrder()
        },



        updateListItemsOrder() {
            let heading = ''
            this.listItems.forEach((item, i) => {
                if (item.is_heading === 'true') {
                    heading = item.title.rendered
                }
                let formData = new FormData()
                formData.append('order', i)
                formData.append('heading', heading)
                this.updateRecord(item.id, 'list_items', this.listItems, formData)
            })
            this.forceRerenderForm()
        },


        deleteListItem(id) {
            let formData = new FormData()
            this.deleteRecord(id, 'list_items', this.listItems, formData)
        },


        toggleDetails(row) {
            if (row._showDetails) {
                this.$set(row, '_showDetails', false)
            } else {
                this.dynamicLists.forEach(item => {
                    this.$set(item, '_showDetails', false)
                })
                this.$nextTick(() => {
                    this.$set(row, '_showDetails', true)
                    this.listId = row.id
                    this.listName = row.name
                    this.listExpiry = row.expires[0]
                })
            }
        },


        createRecord(endpoint = 'posts', dataset = this.items, args, params = '', insertAt = null, duplicateListId = null, sharingItemsOnly = false) {

            const data = new URLSearchParams()
            args.forEach((key, value) => {
                data.append(value, key)
            })
            const options = {
                method: 'POST',
                headers: new Headers({
                    'X-WP-Nonce': krogerkrazy_ajax_obj.nonce
                }),
                body: data,
            }
            fetch('/kk_api/wp/v2/' + endpoint + '/?' + params, options)
                .then(res => {
                    return res.json()
                })
                .then(item => {
                    this.createdRecord = item
                    if (dataset === this.dynamicLists) {
                        item.sharing = false
                    }
                    if (insertAt) {
                        dataset.splice(insertAt, 0, item)
                    } else {
                        dataset.unshift(item)
                    }
                    if (duplicateListId) {
                        const listId = this.createdRecord.id
                        this.fetchListItems(duplicateListId).then(() => {
                            let filteredListItems = this.listItems
                            if (sharingItemsOnly) {
                                filteredListItems = []
                                filteredListItems = this.listItems.filter(item => item.sharing.indexOf('true') > -1)
                            }
                            filteredListItems.forEach((item, i) => {
                                let newName = item.title.rendered + '-' + Date.now()
                                const formData = new FormData()
                                formData.append('status', 'publish')
                                formData.append('title', item.title.rendered)
                                formData.append('slug', newName)
                                formData.append('price', item.price)
                                formData.append('final_price', item.final_price)
                                formData.append('is_heading', item.is_heading)
                                formData.append('heading', item.heading)
                                formData.append('appended', item.appended)
                                formData.append('content', item.content.rendered)
                                formData.append('lists', listId)
                                formData.append('order', item.order)
                                formData.append('sharing', 'false')
                                const params = ''
                                this.createRecord('list_items', this.tempItems, formData, params)
                            })
                        })
                    }
                })
        },


        updateRecord(id, endpoint = 'posts', dataset = this.items, args) {
            const data = new URLSearchParams()
            args.forEach((key, value) => data.append(value, key))
            const params = id
            const options = {
                method: 'POST',
                headers: new Headers({
                    'X-WP-Nonce': krogerkrazy_ajax_obj.nonce
                }),
                body: data,
            }
            fetch('/kk_api/wp/v2/' + endpoint + '/' + params, options)
                .then(res => {
                    return res.json()
                })
                .then(item => {
                    Vue.set(dataset, dataset.map(e => e.id).indexOf(this.listId), item)
                })
                .then(() => {
                    (this.listItems.every( item => item.sharing === 'true')) ? this.listSharing = true : this.listSharing = false
                })
        },


        deleteRecord(id, endpoint = 'posts', dataset = this.items, force = false, reassign = null) {
            const data = new URLSearchParams()
            if (force)
                data.append('force', true)
            if (endpoint === 'users')
                data.append('reassign', reassign)
            const params = id
            const options = {
                method: 'DELETE',
                headers: new Headers({
                    'X-WP-Nonce': krogerkrazy_ajax_obj.nonce
                }),
                body: data,
            }
            fetch('/kk_api/wp/v2/' + endpoint + '/' + params, options)
                .then(res => {
                    return res.json()
                })
                .then(
                    Vue.delete(dataset, dataset.map(e => e.id).indexOf(id))
                )
        },


        showConfirmDelete(id, deleteType) {
            let _this = this
            let msg
            if (deleteType == 'list')
                msg = 'Please confirm that you want to delete this list.'
            else
                msg = 'Please confirm that you want to delete this list item.'
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


        encode (value) {
            if (value)
                this.name = he.encode(value)
        },

        decode (value) {
            if (value)
                return he.decode(value)
        },



        createNumberMask({
                             prefix = dollarSign,
                             suffix = emptyString,
                             includeThousandsSeparator = true,
                             thousandsSeparatorSymbol = comma,
                             allowDecimal = false,
                             decimalSymbol = period,
                             decimalLimit = 2,
                             requireDecimal = false,
                             allowNegative = false,
                             allowLeadingZeroes = false,
                             integerLimit = null
                         } = {}) {
            const prefixLength = prefix && prefix.length || 0
            const suffixLength = suffix && suffix.length || 0
            const thousandsSeparatorSymbolLength = thousandsSeparatorSymbol && thousandsSeparatorSymbol.length || 0

            function numberMask(rawValue = emptyString) {
                const rawValueLength = rawValue.length

                if (
                    rawValue === emptyString ||
                    (rawValue[0] === prefix[0] && rawValueLength === 1)
                ) {
                    return prefix.split(emptyString).concat([digitRegExp]).concat(suffix.split(emptyString))
                } else if(
                    rawValue === decimalSymbol &&
                    allowDecimal
                ) {
                    return prefix.split(emptyString).concat(['0', decimalSymbol, digitRegExp]).concat(suffix.split(emptyString))
                }

                const isNegative = (rawValue[0] === minus) && allowNegative
                //If negative remove "-" sign
                if(isNegative) {
                    rawValue = rawValue.toString().substr(1)
                }

                const indexOfLastDecimal = rawValue.lastIndexOf(decimalSymbol)
                const hasDecimal = indexOfLastDecimal !== -1

                let integer
                let fraction
                let mask

                // remove the suffix
                if (rawValue.slice(suffixLength * -1) === suffix) {
                    rawValue = rawValue.slice(0, suffixLength * -1)
                }

                if (hasDecimal && (allowDecimal || requireDecimal)) {
                    integer = rawValue.slice(rawValue.slice(0, prefixLength) === prefix ? prefixLength : 0, indexOfLastDecimal)

                    fraction = rawValue.slice(indexOfLastDecimal + 1, rawValueLength)
                    fraction = convertToMask(fraction.replace(nonDigitsRegExp, emptyString))
                } else {
                    if (rawValue.slice(0, prefixLength) === prefix) {
                        integer = rawValue.slice(prefixLength)
                    } else {
                        integer = rawValue
                    }
                }

                if (integerLimit && typeof integerLimit === number) {
                    const thousandsSeparatorRegex = thousandsSeparatorSymbol === '.' ? '[.]' : `${thousandsSeparatorSymbol}`
                    const numberOfThousandSeparators = (integer.match(new RegExp(thousandsSeparatorRegex, 'g')) || []).length

                    integer = integer.slice(0, integerLimit + (numberOfThousandSeparators * thousandsSeparatorSymbolLength))
                }

                integer = integer.replace(nonDigitsRegExp, emptyString)

                if (!allowLeadingZeroes) {
                    integer = integer.replace(/^0+(0$|[^0])/, '$1')
                }

                integer = (includeThousandsSeparator) ? addThousandsSeparator(integer, thousandsSeparatorSymbol) : integer

                mask = convertToMask(integer)

                if ((hasDecimal && allowDecimal) || requireDecimal === true) {
                    if (rawValue[indexOfLastDecimal - 1] !== decimalSymbol) {
                        mask.push(caretTrap)
                    }

                    mask.push(decimalSymbol, caretTrap)

                    if (fraction) {
                        if (typeof decimalLimit === number) {
                            fraction = fraction.slice(0, decimalLimit)
                        }

                        mask = mask.concat(fraction)
                    }

                    if (requireDecimal === true && rawValue[indexOfLastDecimal - 1] === decimalSymbol) {
                        mask.push(digitRegExp)
                    }
                }

                if (prefixLength > 0) {
                    mask = prefix.split(emptyString).concat(mask)
                }

                if (isNegative) {
                    // If user is entering a negative number, add a mask placeholder spot to attract the caret to it.
                    if (mask.length === prefixLength) {
                        mask.push(digitRegExp)
                    }

                    mask = [minusRegExp].concat(mask)
                }

                if (suffix.length > 0) {
                    mask = mask.concat(suffix.split(emptyString))
                }

                return mask
            }

            numberMask.instanceOf = 'createNumberMask'

            return numberMask
        },


    },

    computed: {
        is_mobile() {
            const isMobile = window.matchMedia("only screen and (max-width: 760px)")
            return isMobile.matches ? true : false
        },

        dragOptions() {
            return {
                animation: 200,
                group: "description",
                disabled: false,
                ghostClass: "ghost"
            };
        }
    },
})





function convertToMask(strNumber) {
    return strNumber
        .split(emptyString)
        .map((char) => digitRegExp.test(char) ? digitRegExp : char)
}

// http://stackoverflow.com/a/10899795/604296
function addThousandsSeparator(n, thousandsSeparatorSymbol) {
    return n.replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparatorSymbol)
}
