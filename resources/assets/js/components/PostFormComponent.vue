<template>
    <form-wizard
        finish-button-text="Share"
        title="Share your travel expirience"
        subtitle=""
        @on-complete="onComplete"
    >
        <tab-content title="Photos">
            <div class="form-group row">
                <!-- <div class="col-md-12">
                    <input type="file" class="form-control" id="photos" name="photos[]" multiple required @change="previewImage" accept="image/*">
                </div>
                <div v-for="(image,index) in imageData" :key='index' class="image-preview" v-if="imageData.length > 0">
                    <img class="preview" :src="image">
                </div> -->
                <div v-if="attach">
                    <div class="row" id="images">
                        <div class="col-4" v-for="(image,index) in images" :key="index"><img :src="image" class="img-thumbnail img-fluid"/></div>
                    </div>
                </div>
                <div v-bind:class="{ hidden: images.length > 0 }">
                    <input type="file" @change="onFileChange" class="btn btn-default" id="photos" name="photos[]" multiple required>
                </div>
            </div>
        </tab-content>
        <tab-content title="Caption">
            <div class="form-group row">
                <div class="col-md-12">
                    <textarea id="caption" type="text" class="form-control" v-model="caption" name="caption"  required>

                    </textarea>
                </div>
            </div>
        </tab-content>
        <tab-content title="Location">
            <div class="form-group row mb-0">
                <div class="col-md-12">
                    <!-- <input type="text" id="location" name="location" v-model="location" v-on:focus="getCoordinate"/>
                    <input type="hidden" name="coordinates" v-model="coordinates"/> -->
                    <vue-google-autocomplete
                        ref="location"
                        id="location"
                        name="location"
                        classname="form-control"
                        placeholder="Please type your address"
                        v-on:placechanged="getAddressData"
                        country="ph"
                    >
                    </vue-google-autocomplete>
                    <input type="hidden" name="latitude" v-model="latitude" ref="latitude"/>
                    <input type="hidden" name="longitude" v-model="longitude" ref="longitude"/>
                    <input type="hidden" name="city" v-model="city" ref="city"/>
                    <input type="hidden" name="country" v-model="country" ref="country"/>
                    <input type="hidden" name="location_data" v-model="location_data" ref="location_data"/>
                </div>
            </div>
        </tab-content>
    </form-wizard>
</template>

<script>
    import VueFormWizard from 'vue-form-wizard';
    import 'vue-form-wizard/dist/vue-form-wizard.min.css';
    import VueGoogleAutocomplete from 'vue-google-autocomplete';
    Vue.use(VueFormWizard)
        export default {
            components: { VueGoogleAutocomplete },
            data: function() {
                return  {
                    photos:'',
                    caption:'',
                    location_data:'',
                    coordinates: '',
                    country: '',
                    city: '',
                    latitude: '',
                    longitude: '',
                    locality: '',
                    imageData: [],
                    images: [],
                    attach: false,
                    selectedFile: ''
                }
            },
            methods: {
                onComplete: function(){
                    document.getElementById("form-post").submit();
                    //alert('Yay. Done!');
                },
                getAddressData: function (addressData, placeResultData, id) {
                    this.location_data = addressData;
                    this.country = addressData.country;
                    this.city = addressData.city;
                    this.locality = addressData.locality;
                    this.latitude = addressData.latitude;
                    this.longitude = addressData.longitude;
                    this.location = this.$refs.location.value;
                    //this.coordinates = "{'latitude':" + this.latitude + ", 'longitude':" + this.longitude+"}";
                    //this.$refs.coordinates.value =  this.coordinates;
                    this.$refs.country.value = this.country;
                    this.$refs.city.value = this.city;
                    this.$refs.latitude.value = this.latitude;
                    this.$refs.longitude.value = this.longitude;
                },
                previewImage: function(event) {
                    // Reference to the DOM input element
                    var input = event.target;
                    // Ensure that you have a file before attempting to read it
                    if (input.files) {
                        // create a new FileReader to read this image and convert to base64 format
                        var reader = new FileReader();
                        // Define a callback function to run, when FileReader finishes its job
                        reader.onload = (e) => {
                            // Note: arrow function used here, so that "this.imageData" refers to the imageData of Vue component
                            // Read image as base64 and set to imageData
                            this.imageData.push = e.target.result;
                        }
                        // Start the reader job - read file as a data url (base64 format)
                        // this.imageData.forEach(image => {
                        //     reader.readAsDataURL(input.files[0]);
                        // });

                    }
                },
                 onFileChange(e){
                    var files = e.target.files;
                    var vm = this;   // HERE
                    if(files){
                        var files_count = files.length;
                        for (let i=0; i<files_count; i++){
                            var reader = new FileReader();
                            reader.onload = function(e){
                                vm.images.push(e.target.result);
                                vm.selectedFile = e.target.result;    // HERE
                            }
                            vm.attach = true;
                            reader.readAsDataURL(files[i]);
                        }
                    }

                }
            }
    }
</script>
<style scoped>
    .pac-container{
        z-index: 9999;
    }
    img.preview{
        width: 100%;
    }
    .hidden {
        visibility: hidden;
    }
    #images {
        max-height: 300px;
        overflow: scroll;
    }
</style>
