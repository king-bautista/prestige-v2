<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card card-default">
                    <div class="card-header">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="#upload" data-toggle="tab">Upload Files</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#library" data-toggle="tab">Media Library</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="upload">
                                <div class="card">
                                    <div class="card-body">
                                        <gallery-uploader></gallery-uploader>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="library">
                                <div class="card">
                                    <div class="card-body">
                                        <gallery-library></gallery-library>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import GalleryUploader from './GalleryUploader';
import GalleryLibrary from './GalleryLibrary';
export default {
    mounted() {
        console.log('Component mounted.')
    },
    components: {
        GalleryUploader,
        GalleryLibrary,

    }
}
</script>

