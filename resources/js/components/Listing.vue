<template>
    <div>
        <div class="card mb-3">
            <div class="card-body">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">r/</span>
                    </div>
                    <input type="text" class="form-control" placeholder="aww" v-model="subreddit">
                    <div class="input-group-append">
                        <button @click="search" class="btn btn-primary" type="button">Search</button>
                    </div>
                </div>
            </div>
        </div>
        <post v-for="post in listing" :key="post.data.id" :data="post.data" @tweeting="showModal($event)"></post>
        <modal :tweet="tweet"></modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                subreddit: 'animalsbeingderps',
                listing: {},
                tweet: {},
            }
        },
        methods: {
            search () {
                var uri = `https://www.reddit.com/r/${this.subreddit}/search.json?q=url%3Ahttps%3A%2F%2Fv.redd.it&restrict_sr=1&sort=top&t=day`;
                fetch(uri)
                .then((response) => {
                    return response.json();
                })
                .then(({data}) => {
                    this.listing = data.children;
                });
            },

            showModal(data) {
                this.tweet = data;
                $('#modal').modal('show')
            }
        }
    }

</script>
