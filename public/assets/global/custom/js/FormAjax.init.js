function processInputMask(form) {
  let formData = new FormData(form);
  let datamask = $("#" + form.id + ' [data-mask]');
  datamask.each(function (idx) {
    formData.append(this.name, $(this).inputmask('unmaskedvalue'));
  });

  return formData;
}

function btn_save_form(option) {
  response = true;
  if (option.async == undefined) {
    option.async = true;
  }

  if (option.beforeSend == undefined) {
    option.beforeSend = () => { };
  }

  if (option.afterSend == undefined) {
    option.afterSend = () => { };
  }

  if (option.onSuccess == undefined) {
    option.onSuccess = (data) => { };
  }

  if (option.dontRedirect == undefined) {
    option.dontRedirect = false;
  }

  $.ajax({
    url: option.url,
    type: "POST",
    async: option.async,
    data: option.data,
    dataType: "json",
    beforeSend: function () {
      if (option.submit_btn) {
        option.submit_btn.attr('disabled', true);
      }
      if (option.spinner) {
        option.spinner.show();
      }
      option.beforeSend();
    },
    success: function (data) {
      let response = 200;
      if(data.status == false){
        response = 404;
      }

      option.afterSend();
      bootbox.alert({
        title: "Notifikasi",
        message: data.message,
        response: response,
        msgLabel: data.msgLabel,
        centerVertical: true,
        callback: function (result) {
          if (data.error !== undefined) {
            if (data.redirect_link !== undefined) {
              location.replace(data.redirect_link);
            } else {
              location.reload();
            }

          } else {
            if (data.status !== undefined && data.status == true) {
              if ($.fn.modal !== undefined && option.modal) {
                option.modal.modal('hide');
              }

              if (option.table) {
                option.table.ajax.reload();
              } else {

                option.onSuccess(data);
                if (data.redirect_link != undefined) {
                  location.replace(data.redirect_link);
                } else if (option.redirect != undefined) {
                  location.replace(option.redirect);
                } else {
                  if (option.dontRedirect === false) {
                    location.reload();
                  }
                }
              }
            }
          }

          if (option.submit_btn) {
            option.submit_btn.attr('disabled', false);
          }
          if (option.spinner) {
            option.spinner.hide();
          }

          if (window.grecaptcha !== undefined) {
            var c = $('.g-recaptcha').length;
            for (var i = 0; i < c; i++) {
              grecaptcha.reset(i);
            }
          }


        },
      });

      response = data.status;
    },
    error: function (xhr, ajaxOptions, thrownError) {
      option.afterSend();
      let msg = '';
      if (xhr.status == 403) {
        msg = "It seems that this form has expired, please refresh again to process again.";
      } else {
        msg = "There is an unknown error or your connection is lost with the server. Please check the server response first.";
      }

      bootbox.alert({
        title: "Error",
        centerVertical: true,
        message: msg,
        response: 404,
      });

      if (option.submit_btn) {
        option.submit_btn.attr('disabled', false);
      }
      if (option.spinner) {
        option.spinner.hide();
      }

      response = false;

      if (window.grecaptcha !== undefined) {
        var c = $('.g-recaptcha').length;
        for (var i = 0; i < c; i++) {
          grecaptcha.reset(i);
        }
      }


    },
  });

  return response;
}

function btn_save_form_with_file(option) {
  response = true;
  if (option.async == undefined) {
    option.async = true;
  }

  if (option.beforeSend == undefined) {
    option.beforeSend = () => { };
  }

  if (option.afterSend == undefined) {
    option.afterSend = () => { };
  }

  if (option.dontRedirect == undefined) {
    option.dontRedirect = false;
  }

  $.ajax({
    url: option.url,
    type: "POST",
    async: option.async,
    processData: false,
    contentType: false,
    data: option.data,
    dataType: "json",
    beforeSend: function () {
      option.beforeSend();
      if (option.submit_btn) {
        option.submit_btn.attr('disabled', true);
      }
      if (option.spinner) {
        option.spinner.show();
      }
    },
    success: function (data) {
      option.afterSend();

      bootbox.alert({
        title: "Notifikasi",
        message: data.message,
        centerVertical: true,
        callback: function (result) {
          if (data.error !== undefined && data.error == true) {
            if (data.redirect_link !== undefined) {
              location.replace(data.redirect_link);
            } else {
              location.reload();
            }
          } else {
            if (data.status !== undefined && data.status == true) {
              if ($.fn.modal !== undefined && option.modal) {
                option.modal.modal('hide');
              }

              if (option.table) {
                option.table.ajax.reload();
              } else {
                if (data.redirect_link != undefined) {
                  location.replace(data.redirect_link);
                } else if (option.redirect != undefined) {
                  location.replace(option.redirect);
                } else {
                  if (option.dontRedirect === false) {
                    location.reload();
                  }
                }
              }
            }
          }


          if (option.submit_btn) {
            option.submit_btn.attr('disabled', false);
          }
          if (option.spinner) {
            option.spinner.hide();
          }

          if (window.grecaptcha !== undefined) {
            var c = $('.g-recaptcha').length;
            for (var i = 0; i < c; i++) {
              grecaptcha.reset(i);
            }
          }
        },
      });

      response = data.status;
    },
    error: function (xhr, ajaxOptions, thrownError) {
      option.afterSend();
      let msg = '';
      if (xhr.status == 403) {
        msg = "It seems that this form has expired, please refresh again to process again.";
      } else {
        msg = "There is an unknown error or your connection is lost with the server. Please check the server response first.";
      }

      bootbox.alert({
        title: "Error",
        centerVertical: true,
        message: msg,
      });

      if (option.submit_btn) {
        option.submit_btn.attr('disabled', false);
      }
      if (option.spinner) {
        option.spinner.hide();
      }

      response = false;

      if (window.grecaptcha !== undefined) {
        var c = $('.g-recaptcha').length;
        for (var i = 0; i < c; i++) {
          grecaptcha.reset(i);
        }
      }


    },
  });

  return response;
}