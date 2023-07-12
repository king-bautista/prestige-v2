<template>
  <div>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <Table :dataFields="dataFields" :dataUrl="dataUrl" :actionButtons="actionButtons" :primaryKey="primaryKey"
                  v-on:editButton="editGallery" ref="dataTable">
                </Table>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modal Add New / Edit User -->
    <div class="modal fade" id="gallery-form" data-backdrop="static" tabindex="-1" aria-labelledby="gallery-form"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
              Gallery</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card-body">
              <div class="row">
                <div class="col-md-7">
                  <div class="text-center">
                    <span v-if="gallery.file_type == 'image'">
                      <img :src="gallery.image_video_url"
                        style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;" />
                    </span>
                    <span v-else-if="gallery.file_type == 'video'">
                      <video style="border-radius: 20px; margin: 0px; height: 100%; width: 100%;" controls>
                        <source :src="gallery.image_video_url" type="video/mp4">
                      </video>
                    </span>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group row mb-0">
                    <label for="firstName" class="col-sm-4">Title</label>
                    <div class="col-sm-8">
                      {{ gallery.title }}
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="caption" class="col-sm-4 col-form-label">Caption <span class="font-italic text-danger">
                        *</span></label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" v-model="gallery.caption" placeholder="">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="Description" class="col-sm-4 col-form-label">Description <span
                        class="font-italic text-danger"> *</span></label>
                    <div class="col-sm-8">
                      <textarea class="form-control" rows="5" v-model="gallery.description"
                        placeholder="Answer"></textarea>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary pull-right" @click="updateGallery">Save
              Changes</button>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
  </div>
  <!-- End Modal Add New User -->
</div></template>
<script>
import Table from '../Helpers/TableGallery';
// Import this component
export default {
  name: "Gallery",
  data() {
    return {
      helper: new Helpers(),
      gallery: {
        id: '',
        title: '',
        caption: '',
        description: '',
        thumbnail: '',
        image_video_url: '',
        file_type: '',
        file_size: '',
        dimension: '',
        width: '',
        height: '',
      },
      dataFields: {
        title: "Title",
        caption: "Caption",
        description: "Description",
        thumbnail: "Thumbnail",
        image_video_url: "Image Video Url",
        file_type: "File Type",
        file_size: "File Size",
        dimension: "Dimension",
        width: "With",
        height: "Height",
        updated_at: "Last Updated"
      },
      primaryKey: "id",
      dataUrl: "/admin/gallery/list",
      actionButtons: {
        edit: {
          title: 'Edit this Photo',
          name: 'Edit',
          apiUrl: '',
          routeName: 'gallery.edit',
          button: '<i class="fas fa-edit"></i> Edit',
          method: 'edit'
        },
      },
    };
  },

  methods: {
    editGallery: function (id) {
      axios.get('/admin/gallery/' + id)
        .then(response => {
          var gallery = response.data.data;
          this.gallery.id = gallery.id;
          this.gallery.title = gallery.title;
          this.gallery.caption = gallery.caption;
          this.gallery.description = gallery.description;
          this.gallery.image_video_url = window.location.origin + '/' + gallery.image_video_url;
          this.gallery.file_type = gallery.file_type;
          $('#gallery-form').modal('show');
        });
    },

    updateGallery: function () {
      let formData = new FormData();
      formData.append("id", this.gallery.id);
      formData.append("title", this.gallery.title);
      formData.append("caption", this.gallery.caption);
      formData.append("description", this.gallery.description);
      axios.post('/admin/gallery/update', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
      })
        .then(response => {
          toastr.success(response.data.message);
          this.$refs.dataTable.fetchData();
          $('#gallery-form').modal('hide');
        })
    },
  },

  components: {
    Table,
  }
};
</script> 