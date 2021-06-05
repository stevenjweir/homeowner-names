<template>
    <div id="csv-upload">
        <div class="mb-3">
            <label for="csvFileInput" class="card-title h5">Upload your .csv file now</label>
            <input type="file" class="form-control mb-3" name="csvUpload" :disabled="isProcessing" @change="fileUploaded()" accept="text/csv" ref="fileUpload" id="csvFileInput">
            <button type="button" class="btn btn-primary" :class="{'disabled' : !hasFile }" :disabled="!hasFile" @click="processNames">
                {{ isProcessing ? 'Processing...' : 'Process now' }}
            </button>
            <button type="button" class="btn btn-secondary" :class="{'disabled' : !hasFile }" :disabled="!hasFile" @click="reset">Reset</button>
        </div>
        <div v-if="results" class="border-primary p-3">
            <h2 class="mb-3">Results:</h2>
            <hr>
            <div v-for="(property, i) in results" class="text-start">
                <h5 class="mb-2">Property {{ i + 1 }}</h5>
                <p v-for="(owner, o) in property" class="mb-1">
                    <i>Owner {{ property.length > 1 ? (o + 1) : '' }}:</i> {{ processOwnerName(owner) }}
                </p>
                <hr>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            results: null,
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
        processOwnerName(owner) {

            let name = '';

            if (owner.title) {
                name = name + owner.title;
            }

            if (owner.first_name) {
                name = name + ' ' + owner.first_name;
            }
            else if (owner.initial) {
                name = name + ' ' + owner.initial;
            }

            if (owner.last_name) {
                name = name + ' ' + owner.last_name;
            }

            return name;

        },
        /**
         * Trigger when a file is uploaded
         */
        fileUploaded() {
            this.file = this.$refs.fileUpload.files[0];
        },
        /**
         * When the process button is pressed, we axios it to he backend.
         */
        processNames() {
            let self = this;
            let formData = new FormData();
            formData.append('file', this.file);

            //Only proceed if we're not currently processing a file
            if (self.isProcessing) {
                return;
            }

            //Set the processing flag to true
            self.isProcessing = true;

            //Use axios to post to post the csv file
            this.axios.post('api.php',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(function (response) {
                self.isProcessing = false;
                if(response.data) {
                    self.results = response.data;
                }
            })
                .catch(function (exception) {
                    self.isProcessing = false;
                    console.log('An error occurred.', exception);
                });
        },
        /**
         * Resets the component by clearing result data and file input
         */
        reset() {
            this.$refs.fileUpload.value = '';
            this.file = null;
            this.results = null;
            this.isProcessing = false;
        }
    }
}
</script>