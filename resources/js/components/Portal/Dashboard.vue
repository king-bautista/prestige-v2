<!--
Say Hello World with Vue!
-->

<script>
export default {
  data() {
    return {
      message: 'Dashboard'
    }
  },
  methods: {
    reverseMessage() {
      this.message = this.message.split('').reverse().join('')
    },
    notify() {
      alert('navigation was prevented.')
    }
  }
}
</script>

<template>
 <div class ="card">
    <div class ="card-body">
    <div class="row">
      <div class="col-12">
      <h4>
      <i class="fas fa-globe"></i> Company Name:
      <small>Date: 2/10/2014</small>
      </h4>
      </div>
    </div>  
	  <div class="row">
      <div class="col-sm-4 ">
                  From
                  <address>
                    <strong>Admin, Inc.</strong><br>
                    795 Folsom Ave, Suite 600<br>
                    San Francisco, CA 94107<br>
                    Phone: (804) 123-5432<br>
                    Email: info@almasaeedstudio.com
                  </address>
      </div>
      <div class="col-sm-4">
        To
        <address>
        <strong>John Doe</strong><br>
        795 Folsom Ave, Suite 600<br>
        San Francisco, CA 94107<br>
        Phone: (555) 539-1037<br>
        Email: john.doe@example.com
        </address>
      </div>
               
      <div class="col-sm-4">
        <b>Invoice #007612</b><br>
        <br>
        <b>Order ID:</b> 4F3S8J<br>
        <b>Payment Due:</b> 2/22/2014<br>
        <b>Account:</b> 968-34567
      </div>
    </div>
     <div class="row">
      <div class="col-6 table-responsive card">
        <div class = "card-body">  
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>Juan I One</td>
                      <td>Description Description</td>
                      <td>03-04-23</td>
                    </tr>
                    <tr>
                      <td>Pedro P. Peter</td>
                      <td>Description Description</td>
                      <td>03-04-23</td>
                    </tr>
                    <tr>
                      <td>James s. Tiago</td>
                      <td>Description Description</td>
                      <td>03-04-23</td>
                    </tr>
                    </tbody>
                  </table>
                  </div>
      </div>
      <div class="col-6 card">
        <div class="card">
                                    <div class="card-body">
        
                                        <h4 class="card-title">Range validation</h4>
                                        <p class="card-title-desc">Parsley is a javascript form validation
                                            library. It helps you provide your users with feedback on their form
                                            submission before sending it to your server.</p>
        
                                        <form action="#" class="custom-validation" novalidate="">
        
                                            <div class="mb-3">
                                                <label class="form-label">Min Length</label>
                                                <div>
                                                    <input type="text" class="form-control" required="" data-parsley-minlength="6" placeholder="Min 6 chars.">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Max Length</label>
                                                <div>
                                                    <input type="text" class="form-control" required="" data-parsley-maxlength="6" placeholder="Max 6 chars.">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Range Length</label>
                                                <div>
                                                    <input type="text" class="form-control" required="" data-parsley-length="[5,10]" placeholder="Text between 5 - 10 chars length">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Min Value</label>
                                                <div>
                                                    <input type="text" class="form-control" required="" data-parsley-min="6" placeholder="Min value is 6">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Max Value</label>
                                                <div>
                                                    <input type="text" class="form-control" required="" data-parsley-max="6" placeholder="Max value is 6">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Range Value</label>
                                                <div>
                                                    <input class="form-control" required="" type="text" min="6" max="100" placeholder="Number between 6 - 100">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Regular Exp</label>
                                                <div>
                                                    <input type="text" class="form-control" required="" data-parsley-pattern="#[A-Fa-f0-9]{6}" placeholder="Hex. Color">
                                                </div>
                                            </div>
        
                                            <div class="mb-0">
                                                <div>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                                        Submit
                                                    </button>
                                                    <button type="reset" class="btn btn-secondary waves-effect">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- end form -->
                                    </div>
                                </div>
      </div>
      </div>  
    </div>
 </div>
 
</template>