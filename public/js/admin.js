$(document).on('click', '.delete-btn, .btn-destroy', function(e) {
  e.preventDefault();
  var href = $(this).attr('data-href');
  swal({
    title: "Bạn chắc chắn?",
    text: "Bạn chắc chắn muốn xóa bản ghi này?",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Xóa",
    cancelButtonText: "Không",
    closeOnConfirm: false
  },
  function(data){
    if (data) {
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "DELETE",
        url: href,
        success: function(data) {
          if (data.code) {
            location.reload();
          } else {
            alert('Có lỗi xảy ra');
          }
        }
      });
    }
  });
});

// SORT BUTTON
$('#sort-btn').click(function (e) {
    var queryParameters = {}, queryString = location.search.substring(1),
        re = /([^&=]+)=([^&]*)/g, m;
    while (m = re.exec(queryString)) {
        queryParameters[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
    }
    queryParameters['type_sort'] = queryParameters['type_sort'] == 'asc' ? 'desc' : 'asc';
    location.search = $.param(queryParameters);
});
