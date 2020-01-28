<template>
    <div class="position-relative mb-3">
        <content-loader :width="width" :height="height"></content-loader>
        <audio :src="audio_url" loop ref="audio"></audio>
        <div style="position:absolute;top:0;bottom:0;left:0;right:0;overflow:hidden;">
            <video  style="min-width:100%; min-height:100%;width:auto;height:auto;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);"
                    v-show="video_loaded"
                    class="w-full" 
                    :poster="poster" 
                    preload="auto" 
                    controls 
                    loop
                    ref="video"
                    @canplay="ready"
                    @play="sync" 
                    @pause="sync">
                <source :src="video_url" type="video/mp4">
            </video>
        </div>
        
    </div>
</template>

<script>
    import { ContentLoader } from 'vue-content-loader'

    export default {
        components: {
            ContentLoader
        },
        props: ['is_gif', 'video_url', 'audio_url', 'height', 'width', 'poster'],
        data () {
            return {
                video_loaded: false,
                paused: true,
            }
        },
        methods: {
            sync() {
                // if(!this.is_gif) {
                    var video = this.$refs.video;
                    var audio = this.$refs.audio;
                    this.paused = audio.paused;
                    if (audio.paused) {
                        audio.currentTime = video.currentTime
                        audio.play();
                    } else {
                        audio.pause();
                    }
                // }
            },
            ready() {
                this.video_loaded = true;
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
