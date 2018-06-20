<template>
    <div class="comment-form">
        <form v-on:submit.prevent="saveComment()" method="post" >
            <input type="hidden" name="_token" v-model="csrf">
            <div class="form-group">
                <textarea class="form-control" id="message" name="message" v-model="message" ref="message"></textarea>
            </div>
            <input type="hidden" name="post_id" id="post_id" v-model="post_id"/>
            <input type="hidden" name="user_id" id="user_id" v-model="user_id"/>
            <button type="submit" class="btn btn-primary">add comment</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            postId: {
                type: String,
                required: true
            },
            userId: {
                type: String,
                required: true
            }
        },
        data:function() {
                return  {
                    post_id: '',
                    message: '',
                    user_id: '',
                    csrf: ''
                }
        },
        mounted() {
            this.post_id = this.postId;
            this.user_id = this.userId;
            this.csrf = $('meta[name="csrf-token"]').attr('content');
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
                    }
                })
                .catch( function(error){
                    console.log(error);
                });
            }
        }
    }
</script>
