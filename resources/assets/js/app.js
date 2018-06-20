
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
const autosize = require('autosize');
window.Vue = require('vue');



import VueFormWizard from 'vue-form-wizard';
import 'vue-form-wizard/dist/vue-form-wizard.min.css';
Vue.use(VueFormWizard);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('post-form-component', require('./components/PostFormComponent.vue'));
Vue.component('post-component', require('./components/PostComponent.vue'));
Vue.component('user-profile', require('./components/UserProfileComponent.vue'));
Vue.component('modal', {
    template: '#modal-template'
});

autosize($('textarea'));

const app = new Vue({
    el: '#app',
    data: {
        showModal   : false,
        editMode    : false,
        post_id     : false,
        caption     : '',
        deleted_id  : false
      },
    methods: {
        edit: function(id) {

            var caption = 'caption-' + id;
            var edit = 'edit-' + id ;
            this.editMode = true;
            this.post_id = id;
            this.caption = this.$refs[caption].innerText;

            this.$refs[edit].focus();
            setTimeout(() => {
                this.$refs[edit].focus();
                autosize.update(this.$refs[edit]);
              });
        },
        update: function(){
            var id = this.post_id;
            var edit = 'edit-' + id ;
            var caption = 'caption-' + id ;
            this.caption = this.$refs[edit].value;
            this.$refs[caption].innerText = this.caption;

            window.axios.post(`/user/post/update/${id}`, { 'caption':  this.caption}).then(() => {
                this.$refs[caption].innerText = this.$refs[edit].value;
                this.editMode = false;
                this.post_id = false;
                this.caption = '';
            });
        },
        cancel: function(){
            this.editMode = false;
            this.post_id = false;
            this.caption = '';
        },
        delete_post: function(id){
            var post = 'post-'+id;
            window.axios.post(`/user/post/delete/${id}`).then(() => {
                console.log(this.$refs[post]);
                this.deleted_id = id;
                this.editMode = false;
                this.post_id = false;
                this.caption = '';
            });
        },
    }
});



