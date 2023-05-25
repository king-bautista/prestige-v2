<template>
  <div>
    <div>
      <div class="btn-group w-100 mb-2">
        <a class="btn btn-info active" href="javascript:void(0)" data-filter="all"> All items </a>
        <a class="btn btn-info" href="javascript:void(0)" data-filter="1"> Category 1 (WHITE) </a>
        <a class="btn btn-info" href="javascript:void(0)" data-filter="2"> Category 2 (BLACK) </a>
        <a class="btn btn-info" href="javascript:void(0)" data-filter="3"> Category 3 (COLORED) </a>
        <a class="btn btn-info" href="javascript:void(0)" data-filter="4"> Category 4 (COLORED, BLACK) </a>
      </div>
      <div class="mb-2">
        <a class="btn btn-secondary" href="javascript:void(0)" data-shuffle> Shuffle items </a>
        <div class="float-right">
          <select class="custom-select" style="width: auto;" data-sortOrder>
            <option value="index"> Sort by Position </option>
            <option value="sortData"> Sort by Custom Data </option>
          </select>
          <div class="btn-group">
            <a class="btn btn-default" href="javascript:void(0)" data-sortAsc> Ascending </a>
            <a class="btn btn-default" href="javascript:void(0)" data-sortDesc> Descending </a>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="filter-container p-0 row">
      
        <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample" v-for="(business, index) in businessesNew">
          <a href="https://via.placeholder.com/1200/FFFFFF.png?text=1" data-toggle="lightbox"
            data-title="sample 1 - white">
            <img src="https://via.placeholder.com/300/FFFFFF?text=1" class="img-fluid mb-2" alt="white sample" />
          </a>
        </div>
        
      </div>

      <!-- <div class="filter-container p-0 row">
        <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
          <a href="https://via.placeholder.com/1200/FFFFFF.png?text=1" data-toggle="lightbox"
            data-title="sample 1 - white">
            <img src="https://via.placeholder.com/300/FFFFFF?text=1" class="img-fluid mb-2" alt="white sample" />
          </a>
        </div>
        <div class="filtr-item col-sm-2" data-category="2, 4" data-sort="black sample">
          <a href="https://via.placeholder.com/1200/000000.png?text=2" data-toggle="lightbox"
            data-title="sample 2 - black">
            <img src="https://via.placeholder.com/300/000000?text=2" class="img-fluid mb-2" alt="black sample" />
          </a>
        </div>
        <div class="filtr-item col-sm-2" data-category="3, 4" data-sort="red sample">
          <a href="https://via.placeholder.com/1200/FF0000/FFFFFF.png?text=3" data-toggle="lightbox"
            data-title="sample 3 - red">
            <img src="https://via.placeholder.com/300/FF0000/FFFFFF?text=3" class="img-fluid mb-2" alt="red sample" />
          </a>
        </div>
        <div class="filtr-item col-sm-2" data-category="3, 4" data-sort="red sample">
          <a href="https://via.placeholder.com/1200/FF0000/FFFFFF.png?text=4" data-toggle="lightbox"
            data-title="sample 4 - red">
            <img src="https://via.placeholder.com/300/FF0000/FFFFFF?text=4" class="img-fluid mb-2" alt="red sample" />
          </a>
        </div>
        <div class="filtr-item col-sm-2" data-category="2, 4" data-sort="black sample">
          <a href="https://via.placeholder.com/1200/000000.png?text=5" data-toggle="lightbox"
            data-title="sample 5 - black">
            <img src="https://via.placeholder.com/300/000000?text=5" class="img-fluid mb-2" alt="black sample" />
          </a>
        </div>
        <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
          <a href="https://via.placeholder.com/1200/FFFFFF.png?text=6" data-toggle="lightbox"
            data-title="sample 6 - white">
            <img src="https://via.placeholder.com/300/FFFFFF?text=6" class="img-fluid mb-2" alt="white sample" />
          </a>
        </div>
        <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
          <a href="https://via.placeholder.com/1200/FFFFFF.png?text=7" data-toggle="lightbox"
            data-title="sample 7 - white">
            <img src="https://via.placeholder.com/300/FFFFFF?text=7" class="img-fluid mb-2" alt="white sample" />
          </a>
        </div>
        <div class="filtr-item col-sm-2" data-category="2, 4" data-sort="black sample">
          <a href="https://via.placeholder.com/1200/000000.png?text=8" data-toggle="lightbox"
            data-title="sample 8 - black">
            <img src="https://via.placeholder.com/300/000000?text=8" class="img-fluid mb-2" alt="black sample" />
          </a>
        </div>
        <div class="filtr-item col-sm-2" data-category="3, 4" data-sort="red sample">
          <a href="https://via.placeholder.com/1200/FF0000/FFFFFF.png?text=9" data-toggle="lightbox"
            data-title="sample 9 - red">
            <img src="https://via.placeholder.com/300/FF0000/FFFFFF?text=9" class="img-fluid mb-2" alt="red sample" />
          </a>
        </div>
        <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
          <a href="https://via.placeholder.com/1200/FFFFFF.png?text=10" data-toggle="lightbox"
            data-title="sample 10 - white">
            <img src="https://via.placeholder.com/300/FFFFFF?text=10" class="img-fluid mb-2" alt="white sample" />
          </a>
        </div>
        <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
          <a href="https://via.placeholder.com/1200/FFFFFF.png?text=11" data-toggle="lightbox"
            data-title="sample 11 - white">
            <img src="https://via.placeholder.com/300/FFFFFF?text=11" class="img-fluid mb-2" alt="white sample" />
          </a>
        </div>
        <div class="filtr-item col-sm-2" data-category="2, 4" data-sort="black sample">
          <a href="https://via.placeholder.com/1200/000000.png?text=12" data-toggle="lightbox"
            data-title="sample 12 - black">
            <img src="https://via.placeholder.com/300/000000?text=12" class="img-fluid mb-2" alt="black sample" />
          </a>
        </div>
      </div> -->
    </div>

  </div>
</template>

<script>
$(function () {
  $(document).on('click', '[data-toggle="lightbox"]', function (event) {
    event.preventDefault();
    $(this).ekkoLightbox({
      alwaysShowClose: true
    });
  });

  $('.filter-container').filterizr({ gutterPixels: 3 });
  $('.btn[data-filter]').on('click', function () {
    $('.btn[data-filter]').removeClass('active');
    $(this).addClass('active');
  });
})

export default {
  name: "Gallery_Library",
  data() {
    return {
      library: {
        id: '',
        file_path: '',
        file_type: '',
        file_size: '',
        dimension: '',
        width: '',
        height: '',
        //active: false,           
      },
      images: [],
      //add_record: true,
      //edit_record: false,
      // dataFields: {
      // 	name: "Building Name", 
      // 	descriptions: "Descriptions", 
      // 	active: {
      // 		name: "Status", 
      // 		type:"Boolean", 
      // 		status: { 
      // 			0: '<span class="badge badge-danger">Deactivated</span>', 
      // 			1: '<span class="badge badge-info">Active</span>'
      // 		}
      // 	},
      //       updated_at: "Last Updated"
      // },
      // primaryKey: "id",
      // dataUrl: "/admin/site/building/list",
      // actionButtons: {
      // 	edit: {
      // 		title: 'Edit this Building',
      // 		name: 'Edit',
      // 		apiUrl: '',
      // 		routeName: 'building.edit',
      // 		button: '<i class="fas fa-edit"></i> Edit',
      // 		method: 'edit'
      // 	},
      // 	delete: {
      // 		title: 'Delete this Building',
      // 		name: 'Delete',
      // 		apiUrl: '/admin/site/buildings/delete',
      // 		routeName: '',
      // 		button: '<i class="fas fa-trash-alt"></i> Delete',
      // 		method: 'delete'
      // 	},
      // },
      // otherButtons: {
      // 	addNew: {
      // 		title: 'New Building',
      // 		v_on: 'AddNewBuilding',
      // 		icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Building',
      // 		class: 'btn btn-primary btn-sm',
      // 		method: 'add'
      // 	},
      // }
    };
  },

  created() {
    this.getImages();
  },

  methods: {
    getImages: function () {
      axios.get('/admin/gallery/get-all')
        .then(response => this.images = response.data.data);
    },


  },

  components: {
  //  Table
  }
};
</script>

