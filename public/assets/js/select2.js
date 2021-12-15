const defaultSelect2Options = {
    width: '100%',
    minimumResultsForSearch: 15
};

const select2OptionsAllowClear = {
    allowClear: true,
    ...defaultSelect2Options
}

$(function() {
  'use strict'

  if ($(".js-example-basic-single").length) {
    $(".js-example-basic-single").select2();
  }
  if ($(".js-example-basic-multiple").length) {
    $(".js-example-basic-multiple").select2();
  }

  if ($('select.auto-select2').length) {
    $('select.auto-select2').select2(defaultSelect2Options);
  }

  if ($('select.auto-select2-allow-clear').length) {
    $('select.auto-select2-allow-clear').select2(select2OptionsAllowClear);
  }
});
