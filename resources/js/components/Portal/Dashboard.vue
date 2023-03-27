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
<div class="card">
    <div class="card-body">
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" data-bs-toggle="tab" href="#online" role="tab" aria-selected="true">
            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
            <span class="d-none d-sm-block">Online</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#banner" role="tab" aria-selected="false" tabindex="-1">
            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
            <span class="d-none d-sm-block">Banners</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#fullscreen" role="tab" aria-selected="false" tabindex="-1">
            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
            <span class="d-none d-sm-block">Fullscreens</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#pop-up" role="tab" aria-selected="false" tabindex="-1">
            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
            <span class="d-none d-sm-block">Pop-Ups</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#event" role="tab" aria-selected="false" tabindex="-1">
            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
            <span class="d-none d-sm-block">Events</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" data-bs-toggle="tab" href="#promo" role="tab" aria-selected="false" tabindex="-1">
            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
            <span class="d-none d-sm-block">Promos</span>
          </a>
        </li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div class="tab-pane p-3 active show" id="online" role="tabpanel">
          <p class="mb-0">
            Online
          </p>
        </div>
        <div class="tab-pane p-3" id="banner" role="tabpanel">
          <p class="mb-0">
            Banner
          </p>
        </div>
        <div class="tab-pane p-3" id="fullscreen" role="tabpanel">
          <p class="mb-0">
            Fullscreens
          </p>
        </div>
        <div class="tab-pane p-3" id="pop-up" role="tabpanel">
          <p class="mb-0">
            Pop-Ups
          </p>
        </div>
        <div class="tab-pane p-3" id="event" role="tabpanel">
          <p class="mb-0">
            Events
          </p>
        </div>
        <div class="tab-pane p-3" id="promo" role="tabpanel">
          <p class="mb-0">
            Promos
          </p>
        </div>
        
      
    </div>

  </div>
</div>
</template>