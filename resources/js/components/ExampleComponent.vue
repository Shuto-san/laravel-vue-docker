<template>
    <v-app>
        <v-content>
            <v-container fluid fill-height>
                <v-layout wrap>
                    <v-btn color='primary'>Button</v-btn>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
