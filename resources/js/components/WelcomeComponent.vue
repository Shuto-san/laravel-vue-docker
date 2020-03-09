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
          sm="10"
          md="9"
        >
            <div class="text-center">
                <p style="color:white; font-weight: bold; font-size: 2vw;">SHOOT's ROOM</p>
                <p style="color:white; font-weight: bold; font-size: 3vw;">Yutori, Gather!!</p>
            </div>
        </v-col>
          <v-col
            cols="12"
            sm="8"
            md="4"
          >
            <div class="text-center">
                <div class="my-4">
                    <v-btn @click="toTweetRoom()" rounded width="80%" color="primary">Join Tweet Room</v-btn>
                </div>
                <div class="my-4">
                    <v-btn @click="toSignInPage()" rounded width="80%" color="primary">Login</v-btn>
                </div>
                <div class="my-4">
                    <v-btn @click="toRegisterPage()" rounded width="80%" color="primary">Register</v-btn>
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
        toTweetRoom() {
            window.location.href = window.location.origin + `/tweet/index`;
        },
        toSignInPage() {
            window.location.href = window.location.origin + `/login`;
        },
        toRegisterPage() {
            window.location.href = window.location.origin + `/register`;
        }
    }
  }
</script>
