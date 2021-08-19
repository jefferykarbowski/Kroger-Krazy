<div id="kkPrintableList" class="kroger-krazy" list-id="<?php echo $a['id']; ?>" v-cloak>


    <b-form-checkbox-group
            id="kk_coupons"
            v-model="$localStorage.savedListItems"
            name="list-item"
            switch
            size="lg"
    >

    <b-list-group >

        <template v-for="(item, i) in listItems">
        <b-list-group-item :class="(item.is_heading === 'true' ? 'bg-light' : 'bg-white') + ' flex-column align-items-start'" >

        <template v-if="item.is_heading === 'false'">

                <b-form-checkbox :value="item" class="pl-0"><strong><span v-html="item.title.rendered"></span><span v-if="item.price"> - ${{item.price}}</span> <span v-if="item.appended" v-html="item.appended"></span></strong><br><span
                            v-html="item.content.rendered"></span><span v-if="item.final_price"><br>Final cost is as low as ${{item.final_price}}</span>
                </b-form-checkbox>

        </template>

        <template v-else>

                <h3 class="coupon-heading mb-0"><span v-html="toUppercase(item.title.rendered)"></span></h3>

        </template>

        </b-list-group-item>
        </template>
    </b-list-group>

    </b-form-checkbox-group>
</div>