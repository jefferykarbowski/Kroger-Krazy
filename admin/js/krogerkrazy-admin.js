
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

let vm = new Vue({
	el: '#vueApp',
	directives: { sortable },
	data() {
		return {
			listName: '',
			listExpiry: '',
			listUpdating: false,
			expires: '',
			lists: [],
			dynamicLists: [],
			listItems: [],
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
					key: 'expires[0]',
					label: 'Expires',
					sortable: true,
				},
				{
					key: 'updated[0]',
					label: 'Updated',
					sortable: true,
				},
				{
					key: 'buttons',
					label: '',
					tdClass: 'no-wrap',
				},
			],
			defaultTableProps: {
				sortIconLeft:true,
				ref:"table",
				striped:"true",
				tbodyTransitionProps:this.transProps,
				noLocalSorting: false,
				showEmpty: {
					type: Boolean,
					default: false
				},
				emptyText: {
					type: String,
					default: 'There are no agencies to show'
				},
				emptyFilteredText: {
					type: String,
					default: 'There are no agencies matching your request'
				},
			},
			componentKey: 0,
			modelConfig: {
				type: 'string',
				mask: 'YYYY-MM-DD', // Uses 'iso' if missing
			},
		}
	},

	mounted() {
		this.fetchLists().catch(error => {
			console.error(error)
		})
		this.fetchListItems().catch(error => {
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
				this.dynamicLists.push({...element, sharing: false});
			})
		},

		async fetchListItems() {
			const params = '?per_page=100'
			this.listItems = await fetch('/kk_api/wp/v2/list_items' + params)
				.then(res => {
					return res.json()
				})
				.then(listItems => listItems)
		},



		submit : function(){
			this.$refs.form.submit()
		},


		addList(e) {
			e.preventDefault()
			const formData = new FormData(e.target)
			const date = new Date()
			formData.append('status', 'publish')
			formData.append('meta[expires]', formData.get('expires'))
			formData.append('meta[updated]', ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear())
			this.createRecord('lists',this.dynamicLists, formData)
			this.forceRerenderForm()
		},


		duplicateList(item) {
			const formData = new FormData()
			const date = new Date()
			formData.append('status', 'publish')
			formData.append('name', item.name + ' (duplicate)')
			formData.append('meta[expires]', item.expires[0])
			formData.append('meta[updated]', ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear())
			this.createRecord('lists',this.dynamicLists, formData)
			this.forceRerenderForm()
		},


		updateList(e) {
			e.preventDefault()
			let formData = new FormData(e.target)
			const date = new Date()
			formData.append('meta[expires]', formData.get('expires'))
			formData.append('meta[updated]', ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1))) + '/' + ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate())) + '/' + date.getFullYear())
			this.updateRecord(formData.get('id'), 'lists', this.dynamicLists, formData)
			this.forceRerenderForm()
		},


		deleteList(id) {
			let formData = new FormData()
			this.deleteRecord(id, 'lists', this.dynamicLists, formData)
		},

		addListItem(e) {
			const formData = new FormData()
			formData.append('status', 'publish')
			formData.append('title', 'My New List Item')
			formData.append('price', '10.00')
			formData.append('final_price', '9.99')
			formData.append('is_heading', false)
			formData.append('content', 'this is a new list item coming from the rest api')
			this.createRecord('list_items',this.listItems, formData)
		},


		updateListItem(id) {
			let formData = new FormData()
			formData.append('title', 'My Updated List Item')
			this.updateRecord(id, 'list_items', this.listItems, formData)
		},


		deleteListItem(id) {
			let formData = new FormData()
			this.deleteRecord(id, 'list_items', this.listItems, formData)
		},


		toggleDetails(row) {
			if(row._showDetails){
				this.$set(row, '_showDetails', false)
			}else{
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



		createRecord(endpoint='posts',dataset=this.items, args) {
			const data = new URLSearchParams()
			args.forEach((key, value) => {
				data.append(value, key)
			})
			const params = ''
			const options = {
				method: 'POST',
				headers: new Headers({
					'X-WP-Nonce': krogerkrazy_ajax_obj.nonce
				}),
				body: data,
			}
			fetch('/kk_api/wp/v2/'+endpoint+'/' + params, options)
				.then(res => {
					return res.json()
				})
				.then(item => {
					this.createdRecord = item
					dataset.push(item)
				})
		},



		updateRecord(id,endpoint='posts',dataset=this.items, args) {
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
			fetch('/kk_api/wp/v2/'+endpoint+'/' + params, options)
				.then(res => {
					return res.json()
				})
				.then(item => {
					Vue.set(dataset, dataset.map(e => e.id).indexOf(id), item)
				})
		},



		deleteRecord(id,endpoint='posts',dataset=this.items,force=false, reassign = null) {
			const data = new URLSearchParams()
			if (force)
				data.append('force', true)
			if (endpoint==='users')
				data.append('reassign', reassign)
			const params = id
			const options = {
				method: 'DELETE',
				headers: new Headers({
					'X-WP-Nonce': krogerkrazy_ajax_obj.nonce
				}),
				body: data,
			}
			fetch('/kk_api/wp/v2/'+endpoint+'/' + params, options)
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

	}
})