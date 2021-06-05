<template>
    <div id="csv-upload">
        <label for="csvFileInput" class="card-title h5">Upload your .csv file now</label>
        <input type="file" class="form-control mb-3" name="csvUpload" :disabled="isProcessing" @change="fileUploaded($event)" accept="text/csv" ref="fileUpload" id="csvFileInput">
        <a href="#" class="btn btn-primary" :class="{'disabled' : !hasFile }" :disabled="!hasFile">Process Now</a>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                isProcessing: false,
                hasFile: false
            }
        },
        methods: {
            fileUploaded(e) {

            }
        }
    }
</script>