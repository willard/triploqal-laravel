<template>
    <div class="container">
         <div class="user-profile sidebar-sticky">
                <div class="avatar">
                    <a href="#!" v-on:click="avatarUploadClick">
                        <img class="img-fluid img-thumbnail" :src="avatar"/>
                    </a>
                    <vue-croppie
                        ref=croppieRef
                        :enableOrientation="false"
                        :enableResize="false"
                        :mouseWheelZoom="false"
                        :viewport="{ width: 200, height: 200, type: 'circle' }"
                        @result="fn"
                    >
                    </vue-croppie>
                    <form enctype="multipart/form-data">
                        <input  @change="onFileChange" accept="image/jpeg" class="avatar-upload" type="file">
                    </form>
                </div>
                <div class="username"><h3>{{userName}}</h3></div>
                <div class="bio">
                    <div v-if="bio">
                        {{bio}}
                        <div v-show="userAuthId == userId"><a href="#!">edit</a></div>
                    </div>
                    <div v-else>
                        <div v-show="userAuthId == userId"><a href="#!">add bio</a></div>
                    </div>
                </div>
            </div>
    </div>
</template>

<style scoped>
    .avatar-upload{
        display: none !important;
    }
</style>


<script>
    import VueCroppie from 'vue-croppie';
    Vue.use(VueCroppie);
    export default {
        props: {
            userId: {
                type: Number,
                required: true
            },
            userName: {
                type: String,
                required: true
            },
            avatar: {
                type: String,
                required: true
            },
            bio: {
                type: String,
                required: true,
                value: null
            },
            userAuthId: {
                type: Number,
                required: true
            }
        },
        data() {
            return  {

            }
        },
        mounted() {
            console.log('Component mounted.');
            this.$refs.croppieRef.bind({
                url: this.avatar,
            });
        },
        methods: {
            editBio: function(){

            },
            avatarUploadClick: function(){
                $('.avatar-upload').click();
            },
            onFileChange(e){
                var file = e.target.files;
                var vm = this;   // HERE
                if(file){
                    var reader = new FileReader();
                    reader.onload = function(e){
                    //vm.images.push(e.target.result);
                        vm.avatar = e.target.result;    // HERE
                        vm.$refs.croppieRef.bind({
                            url: vm.avatar,
                        });
                    }
                    vm.attach = true;
                    reader.readAsDataURL(file[0]);

                }

            },
            editAvatar: function(){

            }
        }
    }
</script>
