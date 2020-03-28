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
            <v-card class="elevation-12">
              <v-toolbar
                color="primary"
                dark
                flat
              >
                <v-toolbar-title>Register form</v-toolbar-title>
                <v-spacer />
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                    label="Name"
                    name="name"
                    prepend-icon="person"
                    type="text"
                    v-model="name"
                  />
                  <v-text-field
                    label="Email"
                    name="email"
                    prepend-icon="email"
                    type="email"
                    v-model="email"
                  />
                  <v-text-field
                    label="Password"
                    name="password"
                    prepend-icon="lock"
                    type="password"
                    v-model="password"
                  />
                  <v-text-field
                    label="Password Confirmation"
                    name="password_confirmation"
                    prepend-icon="lock"
                    type="password"
                    v-model="password_confirmation"
                  />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer />
                <v-btn @click="postRegister" color="primary">Register</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  export default {
    props: {
      source: String,
    },
    data () {
      return {
        name: "",
        email: "",
        password: "",
        password_confirmation: ""
      }
    },
    methods: {
        postRegister(){
            axios.post(window.location.origin + `/register`, {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation
            })
            .then(response => {
                window.location.href = window.location.origin + `/tweet/index`;
            })
            .catch(error => {
                console.log("validation error");
            });
        }
    }
  }
</script>
