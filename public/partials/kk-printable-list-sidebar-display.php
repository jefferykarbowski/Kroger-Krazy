<div id="kkSidebar" class="kroger-krazy">

    <template>
        <div>
            <b-button v-b-toggle.kk-sidebar-right class="position-fixed btn-toggle-kk-sidebar">Kroger Krazy List
            </b-button>
            <b-sidebar id="kk-sidebar-right" title="Kroger Krazy List" right shadow no-header>
                <b-img src="<?php echo plugin_dir_url( __DIR__ ); ?>images/logo.svg" fluid></b-img>
                <hr class="bg-light text-dark">
                <div class="px-3 py-2 d-flex justify-content-between align-items-center">
                    <b-button-group>
                        <b-button variant="light" @click="deleteItems()" class="ml-2">
                            <b-icon icon="trash"></b-icon>
                        </b-button>
                        <b-button variant="light" v-b-modal.list-actions-modal>
                            <b-icon icon="printer"></b-icon>
                        </b-button>
                        <b-button variant="light" v-b-modal.email-form-modal>
                            <b-icon icon="envelope"></b-icon>
                        </b-button>
                    </b-button-group>
                    <b-button variant="light" v-b-toggle.kk-sidebar-right>
                        <b-icon icon="arrow-right"></b-icon>
                    </b-button>
                </div>
                <div class="px-3 py-2">
                    <b-list-group>
                        <b-list-group-item class="d-flex justify-content-between align-items-center"
                                           v-for="(item, i) in $localStorage.savedListItems"
                                           v-if="item.is_heading !== 'true'">
                            <div><span v-html="item.title.rendered"></span><span
                                        v-if="item.price"> - ${{item.price}}</span></div>
                            <b-button variant="outline-primary" class="ml-2" size="sm">
                                <b-icon @click="removeItem(i)" icon="trash-fill"></b-icon>
                            </b-button>
                        </b-list-group-item>
                    </b-list-group>
                </div>
            </b-sidebar>
        </div>
    </template>

    <b-modal
            id="list-actions-modal"
            v-model="showListActions"
            title=""
            buttonSize="sm"
            static
            size="xl"
            hideHeaderClose
            noCloseOnBackdrop
            noCloseOnEsc
            hide-footer
    >

        <template #modal-header="{ close }">
            <b-button-group>
                <b-button variant="primary" @click="printList">
                    <b-icon icon="printer"></b-icon>
                    Print My List
                </b-button>
                <b-button variant="info" v-b-modal.form-modal>
                    <b-icon icon="plus"></b-icon>
                    Add My Own Items
                </b-button>
                <b-button variant="secondary" v-b-modal.email-form-modal>
                    <b-icon icon="envelope"></b-icon>
                    Send My List
                </b-button>
            </b-button-group>
            <button type="button" aria-label="Close" class="close" @click="showListActions=false">×</button>
        </template>

        <b-list-group id="preparedListForPrint">

            <b-list-group-item v-if="customPrintableList.length > 0">
                <template>
                    <h4 class="mb-0">My Custom Items</h4>
                </template>
            </b-list-group-item>
            <b-list-group-item v-for="(item, i) in customPrintableList" class="d-flex justify-content-between align-items-center">
                <div>
                    <template>
                        <strong>
                            <b-icon icon="square" font-scale="1.5"></b-icon>
                            <span v-html="item.title"></span>
                        </strong><br>
                        <span v-html="item.content"></span>
                    </template>
                </div>
                <b-button variant="outline-primary" class="ml-2" size="sm">
                    <b-icon @click="removeCustomItem(i)" icon="trash-fill"></b-icon>
                </b-button>
            </b-list-group-item>

            <template v-if="printableList.length > 0">
                <b-list-group-item v-for="(item, i) in printableList">
                    <template v-if="item.is_heading !== 'true'">
                        <strong>
                            <b-icon icon="square" font-scale="1.5"></b-icon>
                            <span v-html="item.title.rendered"></span> - <span v-if="item.price">${{item.price}}</span>
                            <span v-if="item.appended" v-html="item.appended"></span></strong><br><span
                                v-html="item.content.rendered"></span>
                    </template>
                    <template v-else>
                        <h4 class="mb-0"><span v-html="item.heading"></span></h4>
                    </template>
                </b-list-group-item>
            </template>
        </b-list-group>


    </b-modal>


    <b-modal
            id="form-modal"
            ref="customCouponFormModalRef"
            title=""
            buttonSize="sm"
            static
            hideHeaderClose
            noCloseOnBackdrop
            noCloseOnEsc
            hide-footer
    >
        <b-form @submit="addCustomCoupon">
            <b-form-group
                    id="fieldset-custom-coupon-title"
                    label-for="custom-coupon-title"
            >
                <b-form-input name="custom-coupon-title" placeholder="Title" class="my-2"></b-form-input>
            </b-form-group>

            <b-form-group
                    id="fieldset-custom-coupon-description"
                    label-for="custom-coupon-description"
            >
                <b-form-textarea
                        id="textarea"
                        placeholder="Description..."
                        rows="3"
                        max-rows="6"
                        name="custom-coupon-description"
                        class="my-2"
                ></b-form-textarea>
            </b-form-group>
            <b-button type="submit" variant="primary">Submit</b-button>
        </b-form>
    </b-modal>




    <b-modal
            id="email-form-modal"
            ref="customCouponEmailFormModalRef"
            title=""
            buttonSize="sm"
            static
            hideHeaderClose
            noCloseOnBackdrop
            noCloseOnEsc
            hide-footer
    >
        <b-form @submit="emailList">
            <b-form-group
                    id="fieldset-email"
                    label-for="email-address"
            >
                <b-form-input type="email" name="email-address" placeholder="Email" class="my-2"></b-form-input>
            </b-form-group>
            <b-button type="submit" variant="primary">Email</b-button>
        </b-form>
    </b-modal>

</div>

