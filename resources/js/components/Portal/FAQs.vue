<template>
  <div>
    <div class="card">
      <div class="card-header">
        <h4><i class="nav-icon fa fa-question-circle"></i>&nbsp;&nbsp;FAQ's</h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div v-for="(faq, index) in faqs" class="accordion" id="accordionExample">
            <div>
              <div class="accordion-item">
                <h2 class="accordion-header" :id="'heading' + index">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    :data-bs-target="'#collapse' + index" aria-expanded="false" :aria-controls="'collapse' + index">
                    {{ faq.question }}
                  </button>
                </h2>
                <div :id="'collapse' + index" class="accordion-collapse collapse" :aria-labelledby="'heading' + index"
                  data-bs-parent="#accordionExample" style="">
                  <div class="accordion-body text-muted">
                    {{ faq.answer }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "FAQs",
  data() {
    return {
      filter: {
        id: '',
      },
      faqs: [],
    }
  },

  created() {
    this.getFAQs();
  },

  methods: {
    getFAQs: function () {
      axios.get('/portal/faqs/get-all')
        .then(response => this.faqs = response.data.data);
    },
  },


};
</script> 
