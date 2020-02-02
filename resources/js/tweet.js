require('./csrf.js');
import InfiniteLoading from 'vue-infinite-loading';

Vue.component('infinite-loading', InfiniteLoading);
new Vue({
    el: '#tweet',
    data: {
        page: 0,
        tweets: [],
        tweet: '',
        isValidated: {
            tweet: false
        },
        validationConditions: {
            max: 255
        },
        validationErrorMessages: {
            max: "255文字以下で書いてください"
        },
        validationErrorMessage: {
            tweet: null
        },
        canSubmit: false,
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
                console.log(response.data.tweets);
                if (response.data.tweets.length) {
                    this.page++;
                    response.data.tweets.forEach (value => {
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
            this.tweet = '';
        },
        preventDoubleClick() {
            if (!this.canSubmit) {
                return;
            }
            this.canSubmit = false;
        },

        getTweets(fetchedTweetIdList, $state) {
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
        }
    },
    watch: {
        tweet: function(newTweet, oldTweet) {
            if (this.tweet === '') {
                this.canSubmit = false;
                console.log('can not submit');
                return;
            }

            if (this.tweet.length > this.validationConditions.max) {
                this.validationErrorMessage.tweet = this.validationErrorMessages.max;
                this.isValidated.tweet = false;
                this.canSubmit = false;
                return;
            }

            this.validationErrorMessage.tweet = null;
            this.isValidated.tweet = true;
            this.canSubmit = true;

            return;
        }
    }
});
