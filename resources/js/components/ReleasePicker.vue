<template>
    <form v-on:submit.prevent="onSubmit">
        <div>
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Package</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control is-expanded">
                            <div class="select is-fullwidth" :class="{ 'is-loading': loadingPackage }">
                                <select id="package" name="package_id" v-model="selectedPackage">
                                    <option v-for="packageItem in packages" :value="packageItem.id">
                                        {{ packageItem.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">Release</label>
                </div>
                <div class="field-body">
                    <div class="field">
                        <div class="control is-expanded">
                            <div class="select is-fullwidth" :class="{ 'is-loading': loadingRelease }">
                                <select name="release_id" v-model="selectedRelease">
                                    <option v-for="release in releases" :value="release.id">
                                        {{ release.version }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label">
                    &nbsp;
                </div>
                <div class="field-body">
                    <div class="control">
                        <button class="button is-primary" type="submit">Bundle</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import axios from 'axios';
    export default {
        data() {
            return {
                packages: [],
                releases: [],
                loadingPackage: true,
                loadingRelease: true,
                selectedPackage: '',
                selectedRelease: ''
            }
        },

        mounted() {
            axios.get('/api/packages')
                .then((response) => {
                    this.packages = response.data.data.sort(function compare(a, b) {
                        return a.name.localeCompare(b.name);
                    });
                    this.selectedPackage = this.packages[0].id;
                    this.loadingPackage = false;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        watch: {
            selectedPackage(packageId) {
                this.releases = [];
                this.loadingRelease = true;
                axios.get('/api/packages/' + packageId + '?include=releases')
                    .then((response) => {
                        this.releases = response.data.data.releases.data;
                        this.selectedRelease = this.releases[0].id;
                        this.loadingRelease = false;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },
        methods: {
            onSubmit: function (event) {
                event.preventDefault();

                var bodyFormData = new FormData();
                bodyFormData.set('build_id', this.build_id);
                bodyFormData.set('package_id', this.selectedPackage);
                bodyFormData.set('release_id', this.selectedRelease);

                axios.post('/bundles', bodyFormData, {
                        config: { headers: {'Content-Type': 'multipart/form-data' }}
                })
                        .then((response) => {
                            if (response.data.status === "success" && typeof response.data.redirect !== 'undefined') {
                                window.location.href = response.data.redirect;
                            }
                        })
                        .catch((error) => {
                            swal("Error", error.response.data.message, "error");
                        });
            }
        },

        props: ['build_id']

    }
</script>
