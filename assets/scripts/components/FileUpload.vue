<template>
    <div id="csv-upload">
        <label for="csvFileInput" class="card-title h5">Upload your .csv file now</label>
        <input type="file" class="form-control mb-3" name="csvUpload" :disabled="isProcessing" @change="fileUploaded()" accept="text/csv" ref="fileUpload" id="csvFileInput">
        <a href="#" class="btn btn-primary" :class="{'disabled' : !hasFile }" :disabled="!hasFile" @click="processNames">Process Now</a>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                file: null,
                isProcessing: false,
            }
        },
        computed: {
            hasFile() {
                return this.file != null;
            }
        },
        methods: {
            /**
             * Trigger when a file is uploaded
             *
             * @param e
             */
            fileUploaded(e) {
                this.file = this.$refs.fileUpload.files[0];
            },
            /**
             * When the process button is pressed, we axios it to he backend.
             *
             */
            processNames() {

                let formData = new FormData();
                formData.append('file', this.file);

                //Only proceed if we're not currently processing a file
                if(this.isProcessing) {
                    return;
                }

                //Set the processing flag to true
                this.isProcessing = true;

                //Use axios to post to post the csv file
                this.axios.post('process.php',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function(data){
                    this.isProcessing = false;
                    console.log(data.data);
                })
                    .catch(function(){
                        this.isProcessing = false;
                        console.log('FAILURE!!');
                    });
            }
        }
    }
</script>