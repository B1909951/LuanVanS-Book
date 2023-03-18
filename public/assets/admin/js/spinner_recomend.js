// Lấy đường dẫn và element của link
var link = document.querySelector('#toolbar a');
var spinner = document.querySelector('#spinner-recommend');

// Callback function khi yêu cầu AJAX đã hoàn thành
function ajaxComplete() {
    console.log('Yêu cầu AJAX đã hoàn thành');

    // Ẩn quá trình loading animation bên phía người dùng
    spinner.classList.remove('show');
}

// Khi link được nhấn, sử dụng AJAX và hiển thị spinner
link.addEventListener('click', function(e) {
    e.preventDefault();

    // Hiển thị quá trình loading animation bên phía người dùng
    spinner.classList.add('show');

    var request = new XMLHttpRequest();

    // Xử lý khi yêu cầu hoàn thành
    request.onload = function(event) {
        ajaxComplete();
    };

    // Gửi yêu cầu đến máy chủ với phương thức GET và các tham số cần thiết
    request.open('GET', link.href);
    request.send();
});