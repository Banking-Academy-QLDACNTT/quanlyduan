$(document).ready(function() {
    // Sự kiện khi select box chọn tỉnh/thành phố thay đổi
$('#province').change(function() {
  // Lấy giá trị của tỉnh/thành phố được chọn
  var province_id = $(this).val();

  // Gửi yêu cầu AJAX
  $.ajax({
      url: '/get-districts',
      method: 'POST',
      dataType: 'json',
      data: {
          id: province_id
      },
      success: function(data) {
          // Xóa tất cả các option hiện tại trong select box quận/huyện
          $('#district').empty();

          // Thêm option mặc định
          $('#district').append($('<option>', {
              value: '',
              text: 'Chọn một Quận/Huyện'
          }));

          // Thêm các option mới từ dữ liệu nhận được
          $.each(data, function(index, district) {
              $('#district').append($('<option>', {
                  value: district.id,
                  text: district.name
              }));
          });
      },
      error: function(xhr, status, error) {
          console.error('Error:', error);
      }
  });
});
    
    // Listen for changes in the "district" select box
    $('#district').on('change', function() {
      var district_id = $(this).val();
      // console.log(district_id);
      if (district_id) {
        // If a district is selected, fetch the awards for that district using AJAX
        $.ajax({
          url: "/get-wards",
          method: 'GET',
          dataType: "json",
          data: {
           id: district_id
          },
          success: function(data) {
            // console.log(data);
            // Clear the current options in the "wards" select box
            $('#wards').empty();
            // Add the new options for the awards for the selected district
            $.each(data, function(i, ward) {
              $('#wards').append($('<option>', {
                value: ward.id,
                text: ward.name
              }));
            });
          }, 
          error: function(xhr, textStatus, errorThrown) {
            console.log('Error: ' + errorThrown);
          }
        });
      } else {
        // If no district is selected, clear the options in the "award" select box
        $('#wards').empty();
      }
    });
  });
  