if ($.fn.DataTable) {
  $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
    return {
      iStart: oSettings._iDisplayStart,
      iEnd: oSettings.fnDisplayEnd(),
      iLength: oSettings._iDisplayLength,
      iTotal: oSettings.fnRecordsTotal(),
      iFilteredTotal: oSettings.fnRecordsDisplay(),
      iPage: Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
      iTotalPages: Math.ceil(
        oSettings.fnRecordsDisplay() / oSettings._iDisplayLength
      ),
    };
  };
}

function intiate_datatables(option) {
  if (!option.groupColumn) {
    option.groupColumn = false;
  }

  if (!option.options) {
    option.options = {};
  }

  if (!option.isoffline) {
    option.isoffline = false;
  }

  if (!option.ordering) {
    option.ordering = false;
  }

  if (!option.pageLength) {
    option.pageLength = 10;
  }
  if (!option.stateSave) {
    option.stateSave = false;
  }
  
  if (option.search_features == false) {
    option.search_features = false;
  } else {
    option.search_features =  true;
  }
  
  if (option.paging_features == false) {
    option.paging_features = false;
  } else {
    option.paging_features =  true;
  }
  
  if (option.length_features == false) {
    option.length_features = false;
  } else {
    option.length_features =  true;
  }

  if (!option.successCallback) {
    option.successCallback = function () { };
  }

  let aOption = {
    // scrollX:!0,
    pageLength: option.pageLength,
    ordering: option.ordering,
    stateSave: option.stateSave,
    searching: option.search_features,
    paging: option.paging_features,
    info: option.length_features,
    language: {
      url: uri_dasar + "assets/global/lang.json",
    },
    columns: option.columns,
    aaSorting: [
      [1, "asc"]
    ],
    initComplete: function (setting, json) {
      //Mengaktifkan Kembali Tooltip
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          this.addEventListener('hide.bs.tooltip', function () {
              new bootstrap.Tooltip(tooltipTriggerEl)
          })
          return new bootstrap.Tooltip(tooltipTriggerEl)
      });
    },
    columnDefs: [
      {
        visible: false,
        targets: option.groupColumn,
      },
    ],
    rowCallback: function (row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      var index = page * length + (iDisplayIndex + 1);
      $("td:eq(0)", row).html(index);
    },
    drawCallback: function () {
      feather.replace();
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          this.addEventListener('hide.bs.tooltip', function () {
              new bootstrap.Tooltip(tooltipTriggerEl)
          })
          return new bootstrap.Tooltip(tooltipTriggerEl)
      });
      
      $(".dataTables_paginate > .pagination").addClass(
        "pagination-rounded custom-pagination pagination-filled pagination-wrap justify-content-center"
      );

      $('div.dataTables_length select').width('80px').select2({ minimumResultsForSearch: -1 });


      // if (option.groupColumn) {
      //   var api = this.api();
      //   var rows = api
      //     .rows({
      //       page: "current",
      //     })
      //     .nodes();
      //   var last = null;

      //   api
      //     .column(option.groupColumn, {
      //       page: "current",
      //     })
      //     .data()
      //     .each(function (group, i) {
      //       if (last !== group) {
      //         $(rows)
      //           .eq(i)
      //           .before(
      //             '<tr class="table-active"><td colspan="' +
      //             option.colspan +
      //             '">' +
      //             group +
      //             "</td></tr>"
      //           );

      //         last = group;
      //       }
      //     });
      // }
    },
  };

  let data_server_side = {
    processing: true,
    serverSide: true,
    ajax: {
      url: option.url,
      type: "POST",
      data: option.data,
      beforeSend: function () {
        if (option.btnfilter) {
          option.btnfilter.hide();
        }
        if (option.spinner) {
          option.spinner.show();
        }
      },
      dataSrc: function (json) {
        if (option.btnfilter) {
          option.btnfilter.show();
        }
        if (option.spinner) {
          option.spinner.hide();
        }

        if (json.error !== undefined) {
          location.reload();
        } else {
          option.successCallback(json);
          return json.data;
        }
      }
    }
  }

  let offline_data = {
    ajax: {
      url: option.sourcedData,
      cache: true
    }
  }

  let allRules;
  if (option.isoffline === false) {
    allRules = Object.assign(aOption, option.options, data_server_side);
  } else {
    allRules = Object.assign(aOption, option.options, offline_data);
  }


  return option.table.DataTable(allRules);
}

function intiate_ajax_select(option) {
  let _onSuccess;
  if (option.onSuccess == undefined) {
    _onSuccess = () => { };
  } else {
    _onSuccess = option.onSuccess;
  }

  data = get_data_by_id({
    ...option,
    onSuccess: (data) => {
      s_select = "";
      s_select += "<option value=''>Pilih Data</option>";

      $.each(data.data, function (index, item) {
        s_select += "<option value='" + item.id + "' " + item.selected + ">" + item.name + "</option>";
      });

      option.container.html(s_select);
      option.container.val(option.container.val()).trigger("change");

      _onSuccess();
    }
  });
}

function initate_ajax_autocomplete(option) {
  if (option.type == undefined) {
    option.type = "POST";
  }

  option.container.autoComplete({
    resolver: 'custom',
    formatResult: function (item) {
      return {
        value: item.id,
        text: item.text,
        html: [
          item.text
        ]
      };
    },
    events: {
      search: function (qry, callback) {
        if (autocomplete_ajax && autocomplete_ajax.readyState != 4) {
          autocomplete_ajax.abort();
        }

        autocomplete_ajax = $.ajax({
          url: option.url,
          type: option.type,
          data: { ...option.data, 'qry': qry },
          dataType: "json",

        }).done(function (res) {
          callback(res.data);
          on_process = false;
        });
      }
    }
  });
}

function bx_alert(msg, redirect, title) {
  if (title === undefined) {
    title =
      '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Peringatan ';
  }
  bootbox.alert({
    title: title,
    message: msg,
    closeButton: false,
    centerVertical: true,
    buttons: {
      ok: {
        label: "Baiklah",
        className: "btn-info",
      },
    },
    callback: function (result) {
      if (redirect != undefined) {
        location.replace(redirect);
      }
    },
  });
}

function get_data_by_id(option) {

  var response = false;
  if (option.type == undefined) {
    option.type = "POST";
  }

  if (option.async == undefined) {
    option.async = false;
  }

  $.ajax({
    url: option.url,
    type: option.type,
    async: option.async,
    data: option.data,
    dataType: "json",
    beforeSend: function () {
      if (option.button) {
        option.button.attr('disabled', true);
      }

      if (option.spinner) {
        option.spinner.show();
      }
    },
    success: function (data) {
      if (data.status) {
        response = data;
      } else {
        bootbox.alert({
          title: "Error",
          centerVertical: true,
          message: data.message,
          callback: function (result) {
            response = false;
          },
        });
      }

      if (option.onSuccess !== undefined) {
        option.onSuccess(data);
      }

      if (option.button) {
        option.button.attr('disabled', false);
      }
      if (option.spinner) {
        option.spinner.hide();
      }

    },
    error: function (xhr, ajaxOptions, thrownError) {
      bootbox.alert({
        title: "Error",
        centerVertical: true,
        message:
          "There is an unknown error or your connection is lost with the server. Please check the server response first.",
      });

      if (option.button) {
        option.button.attr('disabled', false);
      }
      if (option.spinner) {
        option.spinner.hide();
      }
    },
  });

  return response;
}

function ajax_load_html(option) {
  $.ajax({
    type: option.type,
    url: option.url,
    data: option.data,
    dataType: "json",
    error: function () {
      bx_alert(
        "Error: Gagal menghubungkan ke server cobalah mengulang halaman ini kembali",
        location.href
      );
    },
    success: function (res) {
      if (res.status == false) {
        if (res.confirmation !== undefined && res.confirmation == true) {
          if (res.data_confirmation !== undefined) {
            data_confirmation = res.data_confirmation;
          } else {
            data_confirmation = {};
          }
          option_confirm = {
            title: "Konfirmasi",
            message: res.message,
            data: data_confirmation,
            url: option.url,
          };
          btn_confirm_action(option_confirm);
        } else {
          bx_alert(res.message, location.href);
        }
      } else {
        option.divider.html(res.data);
        init_select2();
      }
    }
  });
}

function btn_confirm_action(option) {
  response = true;
  bootbox.confirm({
    title: option.title,
    message: option.message,
    centerVertical: true,
    buttons: {
      cancel: {
        label: "Cancel",
        className: "btn-danger",
      },
      confirm: {
        label: "Yes",
        className: "btn-info",
      },
    },
    callback: function (result) {
      if (result) {
        if (option.type == undefined) {
          type = "POST";
        } else {
          type = option.type;
        }
        $.ajax({
          url: option.url,
          type: type,
          data: option.data,
          dataType: "json",
          success: function (data) {
            bootbox.alert({
              title: "Notifikasi",
              centerVertical: true,
              message: data.message,
              callback: function (result) {
                if (data.error !== undefined) {
                  location.reload();
                } else {
                  if (option.table) {
                    option.table.ajax.reload();
                  } else {
                    if (option.redirect != undefined) {
                      location.replace(option.redirect);
                    } else {
                      location.reload();
                    }
                  }
                  if (option.onSuccess !== undefined) {
                    option.onSuccess(data);
                  }

                  if (data.status !== undefined && data.status == true) {
                    response = true;
                  } else {
                    response = false;
                  }
                }
              },
            });
          },
          
          error: function (xhr, ajaxOptions, thrownError) {
            bootbox.alert({
              title: "Error",
              centerVertical: true,
              message:
                "There is an unknown error or your connection is lost with the server. Please check the server response first.",
            });

            response = false;
          },
        });
      }
    },
  });

  return response;
}

// for sidebar menu horizontal item
$("ul.nav-navhorizontal a")
  .filter(function () {
    var tesulr = $(this).attr("href");
    return tesulr == window.location;
  })
  .closest(".nav-item")
  .addClass("active");

// for sidebar menu entirely but not cover treeview
$("ul.nav-sidebar a")
  .filter(function () {
    var tesulr = $(this).attr("href");
    return tesulr == window.location;
  })
  .closest(".nav-item")
  .addClass("active");

// for treeview
$("li.treeview-menu a")
  .filter(function () {
    var tesulr = $(this).attr("href");
    return tesulr == window.location;
  })
  .closest(".treeview-menu")
  .addClass("active");

$("ul.treeview-menu-collapse a")
  .filter(function () {
    var tesulr = $(this).attr("href");
    return tesulr == window.location;
  })
  .closest(".treeview-menu-collapse")
  .addClass("collapse show");

function bx_alert_success(msg) {
  bootbox.dialog({
    message: '<i class="ion ion-md-checkmark-circle text-success"></i> ' + msg,
    closeButton: false,
    centerVertical: true,
    buttons: {
      add: {
        label: '<i class="icon-pen-plus mr-1"></i> Tambah data',
        className: "btn-info",
        callback: function (result) {
          if (result) {
            location.reload();
          }
        },
      },
      main: {
        label: '<i class="fa fa-chevron-left"></i> Kembali',
        className: "bg-success-400",
        callback: function (result) {
          if (result) {
            history.go(-1);
          }
        },
      },
    },
  });
}

function bx_alert_successUpadate(msg) {
  bootbox.dialog({
    message: '<i class="ion ion-md-checkmark-circle text-success"></i> ' + msg,
    closeButton: false,
    centerVertical: true,
    buttons: {
      main: {
        label: '<i class="fa fa-chevron-left"></i> Kembali',
        className: "bg-success-400",
        callback: function (result) {
          if (result) {
            history.go(-1);
          }
        },
      },
    },
  });
}

function bx_alert_ok(msg, redirect) {
  bxdialog = bootbox.dialog({
    message: '<i class="ion ion-md-checkmark-circle text-success"></i> ' + msg,
    closeButton: true,
    centerVertical: true,
    onEscape: function (result) {
      if (redirect != undefined) {
        location.replace(redirect);
      }
    },
  });

  setTimeout(function () {
    bxdialog.modal("hide");
    if (redirect != undefined) {
      location.replace(redirect);
    }
  }, 2500);
}

function check_valid_number(number) {
  if (Number.isNaN(number)) {
    return 0;
  } else {
    return number;
  }
}