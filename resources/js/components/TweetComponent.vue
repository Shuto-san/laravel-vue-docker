<template>
    <v-app>
    <v-app-bar
    color="white"
    height="auto"
    fixed>
    <v-row
      align="center"
      justify="center"
    >
      <v-col
        cols="12"
        sm="8"
        md="4"
      >
            <v-form
            ref="form"
            v-model="valid"
            lazy-validation>
                  <v-text-field
                    label="Say your thoughts!"
                    v-model.trim="tweet"
                    :counter="255"
                    :rules="tweetRules"
                    required
                    solo
                  >
                  </v-text-field>
                  <v-spacer />
                  <v-btn
                  color="primary mr-4"
                  @click="storeTweet"
                  :disabled="!canSubmit"
                  >Tweet!</v-btn>
            </v-form>
        </v-col>
        </v-row>

    </v-app-bar>
    <v-content
    style="padding-top: 130px;"
    >
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
                <v-row v-for="tweet in tweets" :key="tweet.id" :id="tweet.id" :class="{unviewed: !tweet.is_viewed}"
                class="pa-3">
                    <v-card
                      class="mx-auto"
                      color="primary"
                      width="100%"
                      dark
                    >
                      <v-card-title>
                        <v-icon
                          small
                          left
                        >
                          mdi-twitter
                        </v-icon>
                        <span class="subtitle-2 font-weight-light">Twitter</span>
                      </v-card-title>

                      <v-card-text class="body-1 font-weight-bold">
                        "{{ tweet.tweet }}"
                      </v-card-text>

                      <v-card-actions>
                        <v-list-item class="grow">
                          <v-list-item-avatar color="grey darken-3">
                            <v-img
                              class="elevation-6"
                              src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
                            ></v-img>
                          </v-list-item-avatar>

                          <v-list-item-content>
                            <v-list-item-title>{{ tweet.nickname }}</v-list-item-title>
                          </v-list-item-content>

                          <v-row
                            align="center"
                            justify="end"
                          >
                            <v-icon v-if="tweet.is_liked" @click="pushLike(tweet)" class="mr-1" color="red" dark>mdi-heart</v-icon>
                            <v-icon v-else @click="pushLike(tweet)" class="mr-1">mdi-heart</v-icon>
                            <span class="subheading mr-2">{{ tweet.like_count }}</span>
                            <span class="mr-1"></span>
                            <v-icon v-if="tweet.is_reported" @click="openModal(tweet)" class="mr-1" color="blue" dark>mdi-flag</v-icon>
                            <v-icon v-else @click="openModal(tweet)" class="mr-1">mdi-flag</v-icon>
                            <span class="subheading"></span>
                          </v-row>
                        </v-list-item>
                      </v-card-actions>
                    </v-card>

                    <v-dialog
                      v-model="tweet.isDisplayed"
                      max-width="290"
                    >
                      <v-card>
                        <v-card-title class="headline" v-if="tweet.is_reported">通報を取り消しますか？</v-card-title>
                        <v-card-title class="headline" v-else>通報しますか？</v-card-title>

                        <v-card-text>
                            ※意味不明なツイート、不快なツイート、誹謗中傷を含むツイートは通報できます
                        </v-card-text>

                        <v-card-actions>
                          <v-spacer></v-spacer>

                          <v-btn
                            color="green darken-1"
                            text
                            @click="closeModal(tweet)"
                          >
                            閉じる
                          </v-btn>
                          <v-btn
                            v-if="tweet.is_reported" class="btn"
                            color="green darken-1"
                            text
                            @click="pushReport(tweet)"
                          >
                            通報取消
                          </v-btn>
                          <v-btn
                            v-else
                            color="green darken-1"
                            text
                            @click="pushReport(tweet)"
                          >
                            通報する
                          </v-btn>

                        </v-card-actions>
                      </v-card>
                    </v-dialog>
                </v-row>
                <infinite-loading @infinite="fetchTweets"></infinite-loading>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
    <v-bottom-navigation
      :value="activeBtn"
      color="primary lighten-1"
      fixed
    >
      <v-btn href="/">
        <span>Home</span>
        <v-icon>mdi-home</v-icon>
      </v-btn>

      <v-btn href="/tweet/index">
        <span>Timeline</span>
        <v-icon>mdi-timeline</v-icon>
      </v-btn>
    </v-bottom-navigation>
</v-app>
</template>

<script>
  export default {
    props: {
      source: String,
    },
    data () {
      return {
          page: 0,
          tweets: [],
          tweet: '',
          valid: true,
          tweetRules: [
            v => !!v || 'Tweet is required',
            v => (v && v.length <= 255) || 'Tweet must be less than 255 characters',
          ],
          userAction: {
              like: {
                  list: [],
                  debouncedList: []
              },
              report: {
              },
              impression: {
                  tweetIdList: [],
                  postedTweetIdList: [],
                  updateTweetImpressionCountTimer: null
              }
          },
          isValidated: {
              tweet: false
          },
          validationConditions: {
              min: 0,
              max: 255
          },
          canSubmit: false,
          activeBtn: 1,
      }
    },
    created: function() {
        this.updateTweetImpressionCountTimer = setInterval(this.pushImpressionCount, 3000)
        window.addEventListener('scroll', this.countImpression);
    },

    methods: {
        fetchTweets($state) {
            let fetchedTweetIdList = this.fetchedTweetIdList();

            axios.get('/tweet', {
                params: {
                    fetchedTweetIdList: JSON.stringify(fetchedTweetIdList),
                    page: this.page
                }
            })
            .then(response => {
                if (response.data.tweets.length) {
                    this.page++;
                    response.data.tweets.forEach (value => {
                        value.isDisplayed = false;
                        this.tweets.push(value);
                    });
                    $state.loaded();
                } else {
                    $state.complete();
                }
            })
            .catch(error => {
                console.log(error);
            })

        },

        storeTweet() {
            this.preventDoubleClick();
            this.postTweet();
            this.$refs.form.reset();
            this.tweet = '';
        },

        pushLike(tweet) {
            if (this.userAction.like.list.indexOf(tweet.id) == -1) {
                this.userAction.like.list.push(tweet.id);
                this.newPostLike(tweet.id);
            }
            this.userAction.like.debouncedList[tweet.id](tweet.id, !tweet.is_liked);
            tweet.is_liked = !tweet.is_liked;
            if (tweet.is_liked) {
                tweet.like_count++;
            } else {
                tweet.like_count--;
            }
        },

        newPostLike(tweetId) {
            this.userAction.like.debouncedList[tweetId] = _.debounce(this.postLike, 1000);
        },

        postLike(tweetId, likePushed) {
            axios.post(window.location.origin + `/tweet/like`, {
                tweetId: tweetId,
                likePushed: likePushed
            })
            .then(response => {

            })
            .catch(error => {
            });

        },

        pushReport(tweet) {
            this.postReport(tweet.id, !tweet.is_reported);
            tweet.is_reported = !tweet.is_reported;
            tweet.isDisplayed = false;
        },

        postReport(tweetId, reportPushed) {
            axios.post(window.location.origin + `/tweet/report`, {
                tweetId: tweetId,
                reportPushed: reportPushed
            })
            .then(response => {
            })
            .catch(error => {
            });
        },

        countImpression() {
            let windowTop = pageYOffset;
            let windowHeight = window.outerHeight;
            let impressionTweetList = document.querySelectorAll(".unviewed");
            for (let i = 0; i < impressionTweetList.length ; i++) {
                let impressionTweetId = impressionTweetList[i].id;
                let elemPosition = impressionTweetList[i].offsetTop + impressionTweetList[i].offsetHeight *3/4;
                if (!(this.userAction.impression.tweetIdList.indexOf(impressionTweetId) >= 0)){
                    if (elemPosition > windowTop && elemPosition < windowTop + windowHeight) {
                        this.userAction.impression.tweetIdList.push(impressionTweetId);
                    }
                }
            }
        },

        pushImpressionCount() {
            let postTweetIdList = this.userAction.impression.tweetIdList.filter(id =>
                !this.userAction.impression.postedTweetIdList.includes(id)
            );
            postTweetIdList.forEach(value => {
                this.userAction.impression.postedTweetIdList.push(value);
            });
            if (postTweetIdList.length !== 0) {
                this.postImpressionCount(postTweetIdList);
            }
        },

        postImpressionCount(tweetIdList) {
            axios.post(window.location.origin + `/tweet/impression`, {
                impressionTweetIdList: JSON.stringify(tweetIdList)
            })
            .then(response => {
            })
            .catch(error => {
            });
        },

        preventDoubleClick() {
            if (!this.canSubmit) {
                return;
            }
            this.canSubmit = false;
        },

        postTweet() {
            axios.post('/tweet', {
                tweet: this.tweet
            })
            .then(function (response) {
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
        },

        fetchedTweetIdList() {
            let fetchedTweetIdList = [];
            for (let i = 0; i < this.tweets.length; i++) {
                fetchedTweetIdList.push(this.tweets[i].id);
            }
            return fetchedTweetIdList;
        },

        openModal(tweet) {
            tweet.isDisplayed = true;
        },

        closeModal(tweet) {
            tweet.isDisplayed = false;
        },

        validate () {
          this.$refs.form.validate()
        },

        toHome() {

        }
    },
    watch: {
        tweet: function(newTweet, oldTweet){
            if (newTweet.length <= this.validationConditions.min || newTweet.length > this.validationConditions.max) {
                this.canSubmit = false;
                return;
            }

            this.canSubmit = true;
            return;
        }
    }
  }
</script>
