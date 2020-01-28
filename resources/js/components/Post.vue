<template>
    <div class="card mb-3" v-show="!broken">
        <div class="card-header d-flex align-items-center">
            <div class="text-left mr-3" v-if="is_gif">
                <i class="fas fa-volume-mute fa-lg mr-1"></i>
                <strong>GIF</strong>
            </div>
            <div class="text-left mr-3" v-else>
                <i class="fas fa-volume fa-lg mr-1"></i>
                <strong>Video</strong>
            </div>
            <div class="text-left mr-3">
                <i class="fas fa-arrow-up fa-lg mr-1"></i>
                <strong>{{ post.ups }}</strong>
            </div>
            <div class="text-left mr-3">
                <i class="fas fa-trophy-alt fa-lg mr-1"></i>
                <strong>{{ post.total_awards_received }}</strong>
            </div>
            <div class="text-left mr-3">
                <i class="fas fa-clock fa-lg mr-1"></i>
                <strong>{{ timeSince(post.created_utc) }}</strong>
            </div>

            <div class="text-left" style="flex-grow: 1">
                <div class="rounded overflow-hidden bg-dark">
                    <div class="bg-success text-sm pl-3" :style="{height: '20px', width: upvote_ratio + '%'}">{{ upvote_ratio }}%</div>
                </div> 
            </div>
        </div>

        <div class="card-body">
            <h3>{{ title }}</h3>
            <vid v-if="post.media"
                 :poster="post.preview.images[0].source.url"
                 :video_url="post.media.reddit_video.fallback_url"
                 :width="post.media.reddit_video.width"
                 :height="post.media.reddit_video.height"
                 :audio_url="post.url + '/audio'" 
                 :is_gif="post.is_gif">
            </vid>
            <div class="d-flex align-items-center">
                <button class="btn btn-primary mr-3" @click="showModal">Post</button>
                <a href="" class="btn btn-success mr-3">Download</a>
                <a :href="post.url" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">View</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['data'],
        data () {
            return {
                post: {},
                listing: {},
                upvote_ratio: 50,
                title: '',
                is_gif: true,
                broken: false,
            }
        },
        methods: {
            timeSince(timeStamp) {
                timeStamp = new Date(1000 * timeStamp);
                var now = new Date();
                var secondsPast = (now.getTime() - timeStamp) / 1000;
                if (secondsPast < 60) {
                    return parseInt(secondsPast) + 's';
                }
                if (secondsPast < 3600) {
                    return parseInt(secondsPast / 60) + 'm';
                }
                if (secondsPast <= 86400) {
                    return parseInt(secondsPast / 3600) + 'h';
                }
                if (secondsPast > 86400) {
                    var day = timeStamp.getDate();
                    var month = timeStamp.toDateString().match(/ [a-zA-Z]*/)[0].replace(" ", "");
                    var year = timeStamp.getFullYear() == now.getFullYear() ? "" : " " + timeStamp.getFullYear();
                    return day + " " + month + year;
                }
            },
            showModal () {
                console.log('trying to show modal')
                this.$emit('tweeting', {
                    video: this.post.media.reddit_video.fallback_url,
                    audio: this.post.url + '/audio',
                    id: this.post.id,
                    status: this.title,
                })
            }
        },
        mounted() {
            this.listing = this.data;
            var link = `https://www.reddit.com${this.listing.permalink}.json`;
            fetch(link)
            .then((response) => {
                return response.json();
            })
            .then(res => {
                this.post = res[0].data.children[0].data;
                if (!this.post.media) {
                    this.broken = true;
                    return
                }
                this.title = this.post.title || this.listing.title;
                this.upvote_ratio = Math.floor((this.post.upvote_ratio || this.listing.upvote_ratio) * 100)
                this.is_gif = this.post.media.reddit_video.is_gif
            })
        }
    }
</script>
