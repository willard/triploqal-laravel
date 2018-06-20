
<template>
    <div class="dropdown option">
        <a class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-cog" aria-hidden="true"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a href="#!" class="dropdown-item"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
            <a href="#!" class="dropdown-item"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
        </div>
    </div>
</template>

<style scoped>
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
        width: 160px;
        display: block;
        text-align: right;
        font-size: 20px;
    }

</style>

<script>
    export default {
        data() {
            return  {
                isOption: false
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            showOption: function(){
                if(this.isOption == false){
                    this.isOption = true;
                }else{
                    this.isOption = false;
                }

            },
            hideOption: function(){
                this.isOption = false;
                this.$el.blur();
            }
        }
    }
</script>
