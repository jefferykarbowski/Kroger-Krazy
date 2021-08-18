<div id="kkDeal" class="kroger-krazy" v-cloak>
    <b-form-checkbox-group
            id="kk_coupons"
            v-model="$localStorage.savedListItems"
            name="list-item"
            switch
            size="lg"
    >
    <b-list-group >
        <b-list-group-item class="bg-white flex-column align-items-start" >
            <b-form-checkbox :value="item" class="pl-0"><strong><span v-html="item.title.rendered"></span></strong><br><span v-html="item.content.rendered"></span><br>
                <span>Final cost is as low as ${{item.price}}</span>
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
                rendered: '<?php echo $a['title']; ?>',
            },
            price: '<?php echo $a['final_price']; ?>',
            content: {
                rendered: '<?php echo $a['description']; ?>',
            },
            is_heading: 'false',
            heading: 'My Deals',
        }
    }
},
})
</script>