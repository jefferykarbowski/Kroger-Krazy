let vm = new Vue({
	el: '#vueApp',
	vuetify: new Vuetify(),
	data() {
		return {
			init: 'Vue is ready for Admin Use',
			selectedItem: 1,
			items: [
				{ text: 'Real-Time', icon: 'mdi-clock' },
				{ text: 'Audience', icon: 'mdi-account' },
				{ text: 'Conversions', icon: 'mdi-flag' },
			],
		}
	},

	mounted() {

	},

	methods: {

	},

	computed: {


	}
})