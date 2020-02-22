require('./csrf.js');
import InfiniteLoading from 'vue-infinite-loading';
window._ = require('lodash');

Vue.component('infinite-loading', InfiniteLoading);
new Vue({
    el: '#tweet',
    data: {
        page: 0,
        tweets: [],
        tweet: '',
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
            this.tweet = '';
        },

        pushLike(tweet) {
            if (this.userAction.like.list.indexOf(tweet.id) == -1) {
                this.userAction.like.list.push(tweet.id);
                this.newPostLike(tweet.id);
            }
            this.userAction.like.debouncedList[tweet.id](tweet.id, !tweet.is_liked);
            tweet.is_liked = !tweet.is_liked;
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
            console.log(tweet.id);
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
