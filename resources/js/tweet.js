require('./csrf.js');

new Vue({
    el: '#tweet',
    data: {
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
        storeTweet() {
            this.preventDoubleClick();

            this.postTweet();
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
                this.tweet='';
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
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
