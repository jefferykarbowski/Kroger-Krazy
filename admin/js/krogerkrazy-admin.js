let vm = new Vue({
	el: '#vueApp',
	vuetify: new Vuetify(),
	data() {
		return {
			lists: [],
			listItems: [],
			listId: '',
			selectedItem: 1,
			items: [
				{ text: 'Real-Time', icon: 'mdi-clock' },
				{ text: 'Audience', icon: 'mdi-account' },
				{ text: 'Conversions', icon: 'mdi-flag' },
			],
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
		},

		async fetchListItems() {
			const params = '?per_page=100'
			this.listItems = await fetch('/kk_api/wp/v2/list_items' + params)
				.then(res => {
					return res.json()
				})
				.then(listItems => listItems)
		},


		addList(e) {
			const formData = new FormData()
			formData.append('status', 'publish')
			formData.append('name', 'My New List')
			formData.append('expires', 'Expires Date Here')
			this.createRecord('lists',this.lists, formData)
		},


		updateList(id) {
			let formData = new FormData()
			formData.append('name', 'Updated List')
			this.updateRecord(id, 'lists', this.lists, formData)
		},


		deleteList(id) {
			let formData = new FormData()
			this.deleteRecord(id, 'lists', this.lists, formData)
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











	},

	computed: {

	}
})