<template>
    <div>
        <div v-for="(post,index) in posts" :key='index' class="card" :id="'post-'+index">
           <div>
                <carousel
                per-page="1"
                mouse-drag=1>

                    <slide v-for="(photo,index) in  post.photos" :key="index">
                        <img class="img-fluid photo card-img-top" :src="'/storage/' + photo.filename" />
                    </slide>

                </carousel>
            </div>

            <div class="card-body">
                <div>
                    <div v-show="post.location.country != 'null'" class="location">
                        <a href="#!">
                            <i class="fa fa-map-marker" aria-hidden="true"></i> {{ post.location.country }}
                        </a>
                    </div>
                    <ul class="user-action">
                        <li>
                            <like-component v-bind:post-id="post.post_id" v-bind:user-id="userAuthId">
                            </like-component>
                        </li>
                        <li>
                            <div class="dropdown option">
                                <a class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-cog" aria-hidden="true"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    <a v-on:click="editPost(index)" href="#!" class="dropdown-item"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                                    <a v-on:click="deletePost(index)" href="#!" class="dropdown-item"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="clear"></div>
                <div class="content container">
                    <div class="row">
                        <div class="col-2">
                            <a :href="post.username">
                                <img width="50px" class="img-fluid avatar" :src="'/storage/default.jpg'" />
                            </a>
                        </div>
                        <div class="col-10">
                            <a :href="post.username" class="username">{{ post.username }}</a>
                            <p v-html="post.caption" class="caption"></p>
                        </div>
                    </div>
                </div>

                <comments-component
                    v-bind:post-id="post.post_id"
                    v-bind:user-id="userAuthId">
                </comments-component>

            </div>
        </div>
        <button id="load-more" v-if="posts.length < pagination.total" v-on:click="loadMore">load more</button>
    </div>
</template>

<style scoped>
a.username {
    font-weight: 700;
    color: black;
}
.user-action{
    margin: 10px 0;
}
.location {
    float: left;
}
.clear {
    clear: both;
}
.dropdown-item {
        display: block;
        width: 100%;
        padding: .25rem 1.5rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
    }
    .dropdown{
        position: relative;
    }
    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        float: left;
        min-width: 10rem;
        padding: .5rem 0;
        margin: .125rem 0 0;
        font-size: 16px;
        color: #212529;
        text-align: left;
        list-style: none;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: .25rem;
    }
    a.dropdown-toggle::after{
        content: none;
    }
    a.dropdown-toggle {
        /* width: 160px; */
        /* display: block; */
        text-align: right;
        font-size: 20px;
    }
    button#load-more{
        margin: 0 auto;
        display: block;
        color: #f5f8fa;
        background: #28a745;
        border-radius: 6px;
        padding: 6px 40px;
    }
</style>


<script>
    Vue.component('comments-component', require('./CommentsComponent.vue'));
    Vue.component('like-component', require('./LikeComponent.vue'));
    Vue.component('post-option-component', require('./PostOptionComponent.vue'));
    import VueCarousel from 'vue-carousel';
    import VueScrollTo from 'vue-scrollto';
    Vue.use(VueCarousel);
    Vue.use(VueScrollTo);
    export default {
        props: {
            userAuthId: {
                type: Number,
                required: true
            },
            userId: {
                type: Number,
                value: ''
            }
        },
        data() {
                return  {
                    posts: {},
                    pagination: {
                        'current_page': 1
                    }

                }
        },
        mounted() {
            console.log('Component mounted.');
            this.fetchPosts();
            console.log(this.posts);
        },
        methods: {
            fetchPosts: function($page, $user){
                axios.get('posts?page=' + this.pagination.current_page + '&user=' + this.userId)
                .then(response => {
                    this.posts = response.data.data;
                    this.pagination = response.data.pagination;
                })
                .catch(error => {
                    console.log(error.response.data);
                });
            },
            loadMore: function(){
                axios.get('posts?page=' + (this.pagination.current_page+1) + '&user=' + this.userId)
                .then(response => {
                    response.data.data.forEach(element => {
                        this.posts.push(element);
                    });

                    this.pagination = response.data.pagination;
                })
                .catch(error => {
                    console.log(error.response.data);
                });
            },
            editPost: function(index){
                console.log(this.posts[index]);
            },
            deletePost: function(index){
                console.log(index);
                this.posts.splice(index, 1);
                if(index > 0){
                    VueScrollTo.scrollTo('#post-' + index, 500, { easing: 'ease-in' } );
                }
            },
            onScroll: function(event){
                var d = document.documentElement;
                var offset = d.scrollTop + window.innerHeight;
                var height = d.offsetHeight;

                if (offset === height) {
                    this.loadMore();
                }
            }
        },
        created () {
            window.addEventListener('scroll', this.onScroll);
        },
        destroyed () {
            window.removeEventListener('scroll', this.onScroll);
        }
    }
</script>
