<template>
  <v-app>
    <v-content>
      <v-container
        class="fill-height"
        fluid
      >
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="8"
            md="4"
          >
              <div class="text-center pa-4">
                  <p style="color:white; font-weight: bold;" :class="{'headline': $vuetify.breakpoint. smAndDown, 'display-1': $vuetify.breakpoint. mdAndUp}">SHOOT's ROOM</p>
                  <p style="color:white; font-weight: bold;" :class="{'headline': $vuetify.breakpoint. smAndDown, 'display-1': $vuetify.breakpoint. mdAndUp}">Yutori, Gather!!</p>
              </div>

            <div class="text-center pa-4">
                <div class="my-4">
                    <v-btn href="/tweet/index" rounded width="80%" color="primary">Join Tweet Room</v-btn>
                </div>
                <div class="my-4">
                    <v-btn href="/login" rounded width="80%" color="primary">Login</v-btn>
                </div>
                <div class="my-4">
                    <v-btn href="register" rounded width="80%" color="primary">Register</v-btn>
                </div>
            </div>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  export default {
    props:[],
    data () {
      return {
      }
    },
    methods: {
    }
  }
</script>
