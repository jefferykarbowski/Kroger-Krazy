<div id="kkDeal" class="kroger-krazy kk-deal" v-cloak>
    <b-form-checkbox-group
            id="kk_coupons"
            v-model="$localStorage.savedListItems"
            name="list-item"
            switch
            size="lg"
    >
    <b-list-group >
        <b-list-group-item class="bg-white flex-column align-items-start" >
            <b-form-checkbox :value="item" class="pl-0"><strong><span v-html="item.title.rendered"></span></strong><br><div v-html="item.content.rendered"></div>
                <span>Final cost is as low as {{(item.final_price != 'FREE' ? '$' + formatPrice(item.final_price) : item.final_price)}} {{item.append_price_text}}</span>
            </b-form-checkbox>
        </b-list-group-item>
    </b-list-group>
    </b-form-checkbox-group>
</div>
<script>
let vm = new Vue({
	el: '#kkDeal',
	data() {
		return {
			item: {
				title: {
					rendered: '<?php echo addslashes(html_entity_decode($a['title'])); ?>',
				},
				final_price: '<?php echo ($a['final_price'] == '' || $a['final_price'] == 0 ? 'FREE' : $a['final_price']); ?>',
				append_price_text: '<?php echo addslashes(html_entity_decode($a['append_price_text'])); ?>',
				content: {
					rendered: '<?php echo html_entity_decode($content); ?>'
				},
				is_heading: 'false',
				heading: 'My Deals',
			}
		}
	},
	methods: {

		formatPrice(value) {
			let val = (value/1).toFixed(2)
			return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    	},

    },
})
</script>