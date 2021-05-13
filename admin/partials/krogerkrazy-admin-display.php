<div id="vueApp">
        <b-container class="mt-4">
            <b-col>
            <h2>Printable Lists</h2>
            <div class="float-right mb-3">
                <b-form-group
                        label=""
                        label-for="past-lists-select"
                        label-cols-sm="6"
                        label-cols-md="4"
                        label-cols-lg="3"
                        label-align-sm="right"
                        label-size="sm"
                        class="my-3"
                >
                    <b-form-select
                            id="past-lists-select"
                            v-model="selectedPastList"
                            :options="pastLists"
                            size="sm"
                            class="mb-2"
                    ></b-form-select>

                    <b-input-group size="sm">
                        <b-form-input
                                id="filter-input"
                                type="search"
                                placeholder="Type to Search"
                        ></b-form-input>
                </b-form-group>
                <b-button-group>
                    <b-button variant="primary" v-b-toggle.add_new_list>Create New List</b-button>
                    <b-button variant="success" v-b-modal.form-modal>Import a List</b-button>
                    <b-button variant="warning" v-b-modal.form-modal>Divider Manager</b-button>
                    <b-button variant="info" v-b-modal.form-modal>Settings</b-button>
                </b-button-group>
            </div>


                <div class="mb-2 clear">

                    <b-collapse id="add_new_list" class="my-2" :key="componentKey">

                        <b-card style="max-width:100%">
                            <b-form @submit="addList">
                                <h4>Create a new list</h4>
                                <label class="sr-only" for="name">List Name</label>
                                <b-form-input size="sm" name="name" class="mb-2"
                                              placeholder="Name" :value="listName"></b-form-input>
                                <label class="sr-only" for="expires">List Expiry</label>
                                <v-date-picker  v-model="expires" :model-config="modelConfig" :min-date="new Date()" class="mb-2">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <div class="flex justify-center items-center">
                                            <b-form-input size="sm" class="mb-2 mr-sm-2 mb-sm-0" :value="inputValue" v-on="inputEvents" name="expires" placeholder="Expiry Date" ></b-form-input>
                                        </div>
                                    </template>
                                </v-date-picker>
                                <b-button type="submit" class="my-2 mr-sm-2 mb-sm-0" variant="primary">Submit</b-button>
                                <div v-if="listUpdating">
                                    <div class="mb-2 mr-sm-2 mb-sm-0">
                                        <b-spinner variant="primary" label="Updating Please Wait..."></b-spinner>
                                    </div>
                                </div>
                            </b-form>
                        </b-card>

                    </b-collapse>
                </div>

            <b-table striped hover sort-icon-left :items="dynamicLists" :fields="fields">

                <template v-slot:cell(shortcode)="row">
                    [printable_list id="{{row.item.id}}"]
                </template>


                <template v-slot:cell(buttons)="row">
                    <b-button-toolbar class="float-right">
                        <b-button-group>
                            <b-button size="sm" :class="(row.item.sharing === true ? 'btn-success' : '')" @click="row.item.sharing = !row.item.sharing">
                                <b-icon icon="share" aria-label="Share" ></b-icon>
                            </b-button>
                            <b-button  size="sm">
                                <b-icon icon="pencil-square" aria-label="Edit" @click="toggleDetails(row.item)"></b-icon>
                            </b-button>
                            <b-button  size="sm">
                                <b-icon icon="plus-square" aria-label="Duplicate" @click="duplicateList(row.item)"></b-icon>
                            </b-button>
                            <b-button  size="sm">
                                <b-icon icon="trash" aria-label="Delete"  @click="showConfirmDelete(row.item.id, 'list')"></b-icon>
                            </b-button>
                        </b-button-group>
                    </b-button-toolbar>
                </template>


                <template v-slot:row-details="{ item }">

                    <b-card-text>
                        <b-form @submit="updateList">
                            <h4>Update list</h4>
                            <label class="sr-only" for="name">List Name</label>
                            <input type="hidden" name="id" :value="item.id">
                            <b-form-input size="sm" name="name" class="mb-2"
                                          placeholder="Name" :value="listName"></b-form-input>
                            <label class="sr-only" for="expires">List Expiry</label>
                            <v-date-picker  v-model="expires" :model-config="modelConfig" :min-date="new Date()" class="mb-2">
                                <template v-slot="{ inputValue, inputEvents }">
                                    <div class="flex justify-center items-center">
                                        <b-form-input size="sm" class="mb-2 mr-sm-2 mb-sm-0" :value="listExpiry" v-on="inputEvents" name="expires" placeholder="Expiry Date" ></b-form-input>
                                    </div>
                                </template>
                            </v-date-picker>
                            <b-button type="submit" class="my-2 mr-sm-2 mb-sm-0" variant="primary">Submit</b-button>
                            <div v-if="listUpdating">
                                <div class="mb-2 mr-sm-2 mb-sm-0">
                                    <b-spinner variant="primary" label="Updating Please Wait..."></b-spinner>
                                </div>
                            </div>
                        </b-form>
                    </b-card-text>









                </template>


            </b-table>
            </b-col>
        </b-container>
    <b-modal
            id="create-list-modal"
            title="New List"
            buttonSize="sm"
            hideHeaderClose
            centered
            noCloseOnBackdrop
            noCloseOnEsc
    >
        <b-form @submit="addList">
            <b-form-group
                    label-for="name"
                    description="(i.e. Target, Meijer, Kitchen, etc...)"
                    class="mb-2"
            >
                <b-form-input
                        id="list-name"
                        name="name"
                        placeholder="List Title"
                        required
                ></b-form-input>
            </b-form-group>
            <b-form-group
                    label-for="expires"
                    class="mb-2"
            >
                <b-form-input
                        id="expires"
                        name="expires"
                        placeholder="Expires Date"
                        required
                ></b-form-input>

            </b-form-group>
        </b-form>
    </b-modal>
    <b-modal
        id="form-modal"
        title="Form Modal"
        buttonSize="sm"
        hideHeaderClose
        centered
        noCloseOnBackdrop
        noCloseOnEsc
    >
        <p class="my-4">Form goes here!</p>
    </b-modal>
    <b-modal
        id="list-modal"
        title="Kroger Atlanta 4/14 - 4/20"
        buttonSize="sm"
        size="xl"
        hideHeaderClose
        noCloseOnBackdrop
        noCloseOnEsc
    >
        <div class="container">
            <div class="row">
                <b-button-group>
                    <b-button variant="primary" v-b-modal.form-modal><b-icon icon="plus-square" aria-label="Add Item"></b-icon></b-button>
                    <b-button variant="info" v-b-modal.form-modal><b-icon icon="node-plus" aria-label="Add Divider"></b-icon></b-button>
                    <b-button variant="secondary" v-b-modal.form-modal><b-icon icon="journal-arrow-down" aria-label="Import Dividers"></b-icon></b-button>
                </b-button-group>
            </div>
            <div class="row">Form Goes Here</div>
        </div>
    </b-modal>
</div>