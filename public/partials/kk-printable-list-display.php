<div id="kkPrintableList" class="kroger-krazy" list-id="<?php echo $a['id']; ?>" v-cloak>


    <b-form-checkbox
            v-model="allSelected"
            :indeterminate="indeterminate"
            aria-describedby="kk_coupons"
            aria-controls="kk_coupons"
            @change="toggleAll"
    >
        {{ allSelected ? 'Remove all coupons' : 'Add all coupons' }}
    </b-form-checkbox>



    <b-form-checkbox-group
            id="kk_coupons"
            v-model="$localStorage.savedListItems"
            name="list-item"
            switch
            size="lg"
            @change="openSidebar"
    >

        <b-list-group >



            <template v-for="(item, i) in listItems">
                <b-list-group-item :class="(item.is_heading === 'true' ? 'bg-light' : 'bg-white') + ' flex-column align-items-start'" >

                    <template v-if="item.is_heading === 'false'">

                        <b-form-checkbox :value="item"  class="pl-0"><strong><span v-html="item.title.rendered"></span><span v-if="item.price"> - ${{formatPrice(item.price)}}</span> <span v-if="item.appended" v-html="item.appended"></span><br></strong>
                            <span v-if="item.content.rendered && item.content.rendered != ''" v-html="item.content.rendered"></span>
                            <span v-if="item.final_price">Final cost is as low as ${{formatPrice(item.final_price)}} {{item.price_appendum}}</span>
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