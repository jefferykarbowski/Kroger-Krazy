<div id="vueApp">
    <b-container class="mt-4" fluid>

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
                                v-model="filter"
                                type="search"
                                placeholder="Type to Search"
                        ></b-form-input>

                        <b-input-group-append>
                            <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
                        </b-input-group-append>
                    </b-input-group>
                </b-form-group>
                <b-button-group>
                    <b-button variant="primary" v-b-toggle.add_new_list>Create New List</b-button>
                    <b-button variant="success" v-b-modal.import-list-modal>Import a List</b-button>
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


            <b-table
                    striped
                    hover
                    sort-icon-left
                    :items="dynamicLists"
                    :fields="fields"
                    :filter="filter"
                    :filter-included-fields="filterOn"
            >

                <template v-slot:cell(name)="row">
                    <b-button variant="link" @click="showListItemsModal(row.item.id)">{{row.item.name}}</b-button>
                </template>

                <template v-slot:cell(shortcode)="row">
                    [printable_list id="{{row.item.id}}"]
                </template>


                <template v-slot:cell(buttons)="row">
                    <b-button-toolbar class="float-right">
                        <b-button-group>
                            <!--<b-button size="sm" :class="(row.item.sharing === true ? 'btn-success' : '')" @click="row.item.sharing = !row.item.sharing">
                                <b-icon icon="share" aria-label="Share"></b-icon>
                            </b-button>-->
                            <b-button size="sm" @click="toggleDetails(row.item)">
                                <b-icon icon="pencil-square" aria-label="Edit"></b-icon>
                            </b-button>
                            <b-button size="sm" @click="duplicateList(row.item)">
                                <b-icon icon="plus-square" aria-label="Duplicate"></b-icon>
                            </b-button>
                            <b-button size="sm" @click="showConfirmDelete(row.item.id, 'list')">
                                <b-icon icon="trash" aria-label="Delete"></b-icon>
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
        </b-form>
    </b-modal>
    <b-modal
            id="modal-list-items"
            title="List Items"
            buttonSize="sm"
            size="xl"
            scrollable
            hideHeaderClose
            hideFooter
            centered
            noCloseOnBackdrop
            noCloseOnEsc
            @close="listItems=[]"
    >
        <div :class="(is_mobile ? '' : 'd-flex justify-content-between align-items-center')">
            <b-button-toolbar class="mb-2">
                <b-button-group>
                    <b-button variant="primary" @click="addListItem('true')">Add Heading</b-button>
                    <b-button variant="success" @click="addListItem('false')">Add Item</b-button>
                </b-button-group>
            </b-button-toolbar>

            <div :class="(is_mobile ? '' : 'd-flex justify-content-between')">
                <b-form @submit="sortListItems" :inline="!is_mobile" :class="(is_mobile ? 'mb-2' : 'mr-2')">
                    <label class="sr-only" for="inline-form-input-name">Sort By</label>
                    <b-form-select size="sm" v-model="sortItemsByForm.sortBy" :class="(is_mobile ? 'mb-2' : 'mr-2')">
                        <b-form-select-option :value="null">Sort By</b-form-select-option>
                        <b-form-select-option value="title">Title</b-form-select-option>
                        <b-form-select-option value="price">Price</b-form-select-option>
                        <b-form-select-option value="final_price">Final Price</b-form-select-option>
                    </b-form-select>

                    <label class="sr-only" for="inline-form-input-name">Direction</label>
                    <b-form-select size="sm" v-model="sortItemsByForm.sortByDirection"
                                   :class="(is_mobile ? 'mb-2' : 'mr-2')">
                        <b-form-select-option value="asc">ASC</b-form-select-option>
                        <b-form-select-option value="dsc">DSC</b-form-select-option>
                    </b-form-select>

                    <b-button type="submit" size="sm" variant="primary">Sort</b-button>
                </b-form>
                <b-button type="button" :class="(is_mobile ? 'mb-2' : 'mr-2')" size="sm" variant="primary"
                          v-b-modal.import-list-items-modal>Import List Items
                </b-button>

                <span :class="(is_mobile ? 'mb-2' : 'mr-2')">
                <b-button title="Toggle Sharing" size="sm" :class="(listSharing ? 'btn-success' : 'btn-primary')"
                          @click="toggleSharing()">
                    <b-icon icon="share" aria-label="Share"></b-icon>
                </b-button>
                </span>
                <b-button type="button" size="sm" variant="primary" @click="importHeadings()">Import Headings
                </b-button>
            </div>


        </div>

        <draggable tag="ul" :list="listItems" class="list-group" v-bind="dragOptions" @end="updateListItemsOrder"
                   handle=".handle">

            <b-list-group-item v-for="(item, i) in listItems"
                               :class="'list-group-item' + (item.is_heading === 'false' ? ' ' : ' bg-secondary')"
                               v-bind:key="item.order" :id="'list-item-' + i">
                <b-button-group vertical class="item-buttons">
                    <b-button size="sm" :variant="(item.is_heading === 'false' ? 'link' : 'outline-light')"
                              class="handle  border-0">
                        <b-icon icon="arrow-down-up"></b-icon>
                    </b-button>
                    <b-button size="sm" variant="link" @click="addListItem('false', i)"
                              v-if="item.is_heading === 'false'">
                        <b-icon icon="plus-circle-fill" aria-label="Add List Item Here"></b-icon>
                    </b-button>
                </b-button-group>

                <b-form @change="updateListItem(item.id, $event.target)" ref="list-item-form">
                    <input type="hidden" name="id" :value="item.id">
                    <input type="hidden" name="order" :value="i">
                    <b-container fluid>
                        <b-row class="row">
                            <template :class="'coupon_item_'+i" v-if="item.is_heading === 'false'">
                                <b-col>
                                    <b-row class="my-1">
                                        <b-col>
                                            <b-form-input
                                                    :id="'title-'+i"
                                                    :value="decode(item.title.rendered)"
                                                    type="text"
                                                    placeholder="Title"
                                                    required
                                                    name="title"
                                            ></b-form-input>
                                        </b-col>
                                    </b-row>
                                    <b-row class="my-1">
                                        <b-col lg="6" my-2 mx-0-lg>
                                            <b-input-group prepend="$">
                                                <b-form-input
                                                        :id="'price-'+i"
                                                        v-model="item.price"
                                                        type="number"
                                                        placeholder="Price"
                                                        required
                                                        name="price"
                                                ></b-form-input>
                                            </b-input-group>
                                        </b-col>
                                        <b-col lg="6" my-2 mx-0-lg>
                                            <b-input-group prepend="$">
                                                <b-form-input
                                                        :id="'final-price-'+i"
                                                        v-model="item.final_price"
                                                        type="number"
                                                        placeholder="Final Price"
                                                        required
                                                        name="final_price"
                                                ></b-form-input>
                                            </b-input-group>
                                        </b-col>
                                    </b-row>
                                    <b-row class="my-1">
                                        <b-col>
                                            <b-form-input
                                                    :id="'appended-'+i"
                                                    :value="decode(item.appended)"
                                                    type="text"
                                                    placeholder="Appended Title Text"
                                                    required
                                                    name="appended"
                                                    description="e.g. (thru xx/xx)"
                                            ></b-form-input>
                                        </b-col>
                                    </b-row>
                                </b-col>

                                <b-col lg="6" class="itemDescription">
                                    <quill-editor
                                            ref="quillEditor"
                                            class="editor"
                                            v-model="item.content.rendered"
                                            :options="quillOptions"
                                            @change="updateListItem(item.id, $event.target)"
                                    />
                                </b-col>
                            </template>
                            <template :class="'coupon_heading_'+i" v-else>
                                <b-col>
                                    <b-form-input
                                            :id="'title-'+i"
                                            :value="decode(item.title.rendered)"
                                            type="text"
                                            placeholder="Title"
                                            required
                                            name="title"
                                    ></b-form-input>
                                </b-col>
                            </template>
                            <b-col>
                                <b-button-toolbar class="float-right" :vertical="is_mobile">
                                    <b-button-group>
                                        <b-button title="Share" size="sm"
                                                  :class="(item.sharing === 'true' ? 'btn-success' : '')"
                                                  @click="shareListItem(item, (item.sharing === 'true' ? 'false' : 'true'))">
                                            <b-icon icon="share" aria-label="Share"></b-icon>
                                        </b-button>
                                        <b-button title="Duplicate" size="sm" @click="duplicateListItem(item, i)">
                                            <b-icon icon="plus-square" aria-label="Duplicate"
                                            ></b-icon>
                                        </b-button>
                                        <b-button title="Trash" size="sm"
                                                  @click="showConfirmDelete(item.id, 'listItem')">
                                            <b-icon icon="trash" aria-label="Delete"
                                            ></b-icon>
                                        </b-button>
                                    </b-button-group>
                                </b-button-toolbar>
                            </b-col>

                        </b-row>

                    </b-container>


                </b-form>


            </b-list-group-item>
        </draggable>

        </b-list-group>


    </b-modal>

    <b-modal
            id="import-list-modal"
            buttonSize="sm"
            noCloseOnBackdrop
            noCloseOnEsc
            title="Choose a list to import"
            hide-footer
    >
        <b-container>
            <b-form @submit="importList">
                <b-form-select
                        v-model="selectedListToImport"
                        :options="dynamicLists"
                        class="mb-3"
                        value-field="id"
                        text-field="name"
                ></b-form-select>
                <b-button type="submit" class="my-2 mr-sm-2 mb-sm-0" variant="primary">Import</b-button>
            </b-form>
        </b-container>
    </b-modal>


    <b-modal
            id="import-list-items-modal"
            buttonSize="sm"
            noCloseOnBackdrop
            noCloseOnEsc
            title="Choose a list to import"
            hide-footer
    >
        <div class="container">
            <b-form @submit="importListItems">
                <b-form-select
                        v-model="selectedListToImport"
                        :options="dynamicLists"
                        class="mb-3"
                        value-field="id"
                        text-field="name"
                ></b-form-select>
                <b-button type="submit" class="my-2 mr-sm-2 mb-sm-0" variant="primary">Import</b-button>
            </b-form>
        </div>
    </b-modal>

    </div>