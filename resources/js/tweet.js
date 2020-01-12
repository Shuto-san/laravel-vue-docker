require('./csrf.js');

new Vue({
  el: '#tweet',
  data: {
    tweet: ''
  },
  methods: {
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
    }
  }
});
