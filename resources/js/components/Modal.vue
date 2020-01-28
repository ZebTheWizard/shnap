<template>
    <div class="fade modal" tabindex="-1" role="dialog" id="modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <textarea class="form-control" name="status" id="status" rows="3" v-model="message"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="makeTweet">Tweet</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['tweet'],
        data () {
            return {
                message: ''
            }
        },
        mounted() {
            this.message = this.tweet.status;
        },
        methods: {
            makeTweet() {
                window.axios.post('/tweet', {
                    status: this.message,
                    video: this.tweet.video,
                    audio: this.tweet.audio,
                    id: this.tweet.id,
                })
                .then(res => {
                    if (res.data) {
                        $('#modal').modal('hide')
                    }
                })
            }
        }
    }
</script>
