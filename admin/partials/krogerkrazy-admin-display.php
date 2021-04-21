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

        <div class="pa-5">{{init}}</div>

        <v-divider></v-divider>
        <template>
            <v-card
                    class="mx-auto my-5"
                    max-width="300"
                    tile
            >
                <v-list disabled>
                    <v-subheader>Dummy List built with Vuetify</v-subheader>
                    <v-list-item-group
                            v-model="selectedItem"
                            color="primary"
                    >
                        <v-list-item
                                v-for="(item, i) in items"
                                :key="i"
                        >
                            <v-list-item-icon>
                                <v-icon v-text="item.icon"></v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title v-text="item.text"></v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list-item-group>
                </v-list>
            </v-card>
        </template>

        </v-container>
    </v-app>
</div>