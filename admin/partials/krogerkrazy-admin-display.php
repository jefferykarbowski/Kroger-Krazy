<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://krogerkrazy.com
 * @since      1.0.0
 *
 * @package    Krogerkrazy
 * @subpackage Krogerkrazy/admin/partials
 */
?>

<div id="vueApp">
    <v-app>
        <v-container>
            <h2>Printable Lists</h2>


            <div class="pa-5">{{lists}}</div>

            <div class="pa-5">{{listItems}}</div>


            <template>


                <v-subheader>Lists</v-subheader>
                <v-btn elevation="2" @click="addList()">Create List</v-btn>
                <v-list-item-group
                        color="primary"
                >
                    <v-list-item
                            v-for="(list, i) in lists"
                            :key="i"
                    >

                        <v-list-item-content>
                            <v-list-item-title v-text="list.name"></v-list-item-title>
                            <v-btn elevation="2" @click="updateList(list.id)">Update List</v-btn>
                            <v-btn elevation="2" @click="deleteList(list.id)">Delete List</v-btn>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>


            </template>


            <template>


                <v-subheader>List Items</v-subheader>
                <v-btn elevation="2" @click="addListItem()">Create List Item</v-btn>
                <v-list-item-group
                        color="primary"
                >
                    <v-list-item
                            v-for="(listItem, i) in listItems"
                            :key="i"
                    >

                        <v-list-item-content>
                            <v-list-item-title v-text="listItem.title.rendered"></v-list-item-title>
                            <v-btn elevation="2" @click="updateListItem(listItem.id)">Update List</v-btn>
                            <v-btn elevation="2" @click="deleteListItem(listItem.id)">Delete List</v-btn>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>


            </template>

        </v-container>
    </v-app>
</div>