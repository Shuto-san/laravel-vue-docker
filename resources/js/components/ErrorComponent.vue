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
            <v-alert
            outlined
            text
            color="indigo"
            >
                {{ text }}
            </v-alert>
            <div class="text-center">
              <v-btn rounded color="primary">トップページへ戻る</v-btn>
            </div>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  export default {
    props:['text'],
    data () {
      return {
      }
    },
    methods: {
    }
  }
</script>
