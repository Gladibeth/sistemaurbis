'use strict';

jQuery(document).ready(function ($) {
  setInterval(function () {
    if (window.jQuery && window.dzsap_init_allGalleries) {
      window.dzsap_init_allGalleries(window.jQuery)
    }
    if (window.jQuery && window.dzsap_init_allPlayers) {
      window.dzsap_init_allPlayers(window.jQuery)
    }
  }, 2000)
})