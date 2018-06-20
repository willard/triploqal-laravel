<template>
    <div class="like">
        <a href="#!" v-on:click="saveLike()" v-bind:class="{ liked: liked }">
            <i v-show="!liked" class="fa fa-heart-o" aria-hidden="true"></i>
            <i v-show="liked" class="fa fa-heart" aria-hidden="true"></i>
        </a>
        <!-- <span v-if="likes" class="likes">{{ likes }} {{ 'like' | pluralize(likes) }}</span> -->
    </div>
</template>
<style scoped>
    a.liked {
        color: #dc3545;
    }
    .like a {
        font-size: 20px;
    }
</style>


<script>
    Vue.filter('pluralize', (word, amount) => amount > 1 ? `${word}s` : word)
    export default {
        props: {
            postId: {
                type: Number,
                required: true
            },
            userId: {
                type: String,
                required: true
            }
        },
        data() {
                return  {
                    likes: 0,
                    liked: false
                }
        },
        mounted() {
            this.fetchLikes();
            this.isliked();
        },
        methods: {
            saveLike: function(){
                if(this.liked == false){
                    window.axios.post('/like/store',
                    {
                        'post_id':  this.postId,
                        'user_id':  this.userId,
                    })
                    .then( (response) => {
                        if(response.status===200) {
                            //reload posts
                            console.log(response);
                            this.fetchLikes();
                            this.liked = true;
                        }
                    })
                    .catch( function(error){
                        console.log(error);
                    });
                }else{
                    window.axios.post('/like/destroy',
                    {
                        'post_id':  this.postId,
                        'user_id':  this.userId,
                    })
                    .then( (response) => {
                        if(response.status===200) {
                            //reload posts
                            console.log(response);
                            this.fetchLikes();
                            this.liked = false;
                        }
                    })
                    .catch( function(error){
                        console.log(error);
                    });
                }

            },
            fetchLikes: function() {
                window.axios.get('likes/' + this.postId)
                .then( (response) => {
                    if(response.status===200) {
                        this.likes = response.data;
                        console.log(response.data);
                    }
                })
                .catch( function(error){
                    console.log(error);
                });
            },
            isliked: function(){
                window.axios.get('like/is-liked/' + this.postId + '/' + this.userId)
                .then( (response) => {
                    if(response.status===200) {
                        if(response.data == 1){
                            this.liked = true;
                        }
                        console.log(response.data);
                    }
                })
                .catch( function(error){
                    console.log(error);
                });
            }
        }
    }
</script>
