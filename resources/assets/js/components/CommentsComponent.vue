<template>
    <div class="comment-container">
        <div class="comments">
            <h5><a href="#!">comments</a></h5>
            <div class="comment" v-if="comments" v-for="(comment,index) in commentsData" :key='index'>
                <!-- <div class="comment-date">{{comment.date}}</div> -->
                <div class="row">
                    <div class="col-2">
                        <a :href="'/' + comment.username">
                            <img width="50px" class="image-responsive" :src="'/storage/' + comment.avatar"/>
                        </a>
                    </div>
                    <div class="col-10">
                         <div class="comment-meta">
                            <div class="comment-username"><a :href="'/' + comment.username">{{comment.username}}</a></div>
                            <div class="message">{{comment.message}}</div>
                            <ul class="comment-user-actions">
                                <li><a href="#!"><i class="fa fa-reply" aria-hidden="true"></i></a></li>
                                <li><a href="#!"><i class="fa fa-trash" aria-hidden="true"></i></a></li>
                                <li><a href="#!"><i class="fa fa-ban" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <button id="load-more" v-if="commentsData.length < pagination.total" v-on:click="loadMore">load more comments</button>
        </div>

        <div class="comment-form">
            <form v-on:submit.prevent="saveComment()" method="post" >
                <input type="hidden" name="_token" v-model="csrf">
                <div class="form-group">
                    <textarea class="form-control" id="message" name="message" v-model="message" ref="message"></textarea>
                </div>
                <input type="hidden" name="post_id" id="post_id" v-model="post_id"/>
                <input type="hidden" name="user_id" id="user_id" v-model="user_id"/>
                <button type="submit" class="btn btn-primary">add comment</button>
                <div style="clear: both;"></div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.comment-container {
    background: #f8f9fa;
    padding: 20px;
}
.comment {
    border: 1px solid #e9ecef;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 3px;
    background: #fff;
}
.comment-username {
    font-weight: 600;
}
.comment-username a{
    color: #000;
}
.comment-date {
    text-align: right;
    font-size: small;
}
.comment-form button{
    float: right;
}
ul.comment-user-actions{
    text-align: right;
    border-top: 1px solid #eee;
    margin-top: 10px;
    padding-top: 10px;
}
ul.comment-user-actions li{
    display: inline;
}
ul.comment-user-actions li a{
    font-size: 14px;
    padding: 3px;
}
button#load-more {
    background: none;
    border: 1px solid #eee;
    margin: 0 auto;
    display: block;
    width: 200px;
    margin-bottom: 20px;
    color: #212529;
    border-radius: 3px;
}
</style>


<script>
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
                    post_id: '',
                    message: '',
                    user_id: '',
                    csrf: '',
                    comments: [],
                    commentsData: [],
                    comments: 0,
                    pagination: {
                        'current_page': 1
                    }
                }
        },
        mounted() {
            this.post_id = this.postId;
            this.user_id = this.userId;
            this.csrf = $('meta[name="csrf-token"]').attr('content');
            console.log("mounted");
            this.fetchComments();
        },
        methods: {
            saveComment: function(){
                window.axios.post('/comment/store',
                {
                    'post_id':  this.post_id,
                    'user_id':  this.user_id,
                    'message':  this.message,
                    'parent_id': 0,
                })
                .then( (response) => {
                    if(response.status===200) {
                        //reload posts
                        this.message = '';
                        console.log(response);
                        this.commentsData.push(response.data.data);
                    }
                })
                .catch( function(error){
                    console.log(error);
                });
            },
            fetchComments: function() {
                axios.get('comments?page=' + this.pagination.current_page + '&post=' +  this.postId)
                .then( (response) => {
                    if(response.status===200) {
                        this.commentsData = response.data.data;
                        this.comments = 1;
                        this.pagination = response.data.pagination;
                    }
                })
                .catch( function(error){
                    console.log(error);
                });
            },
            loadMore: function(){
                axios.get('comments?page=' + (this.pagination.current_page+1) + '&post=' +  this.postId)
                .then(response => {
                    response.data.data.forEach(element => {
                        this.commentsData.push(element);
                    });

                    this.pagination = response.data.pagination;
                })
                .catch(error => {
                    console.log(error.response.data);
                });
            },
        }
    }
</script>
