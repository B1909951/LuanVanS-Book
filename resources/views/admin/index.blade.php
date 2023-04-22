@extends('admin.layouts.main')
@section('title', 'Admin | Dashboard')
@section('content')

    <!-- Sale & Revenue Start -->
    <div class="container-fluid pt-4 px-4">
        <h4 class="mb-4">Thông tin chung</h4>

        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-users fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Tổng Admin</p>
                        <h6 class="mb-0">{{$all_admin->count()}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-book me-2 fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Tổng sản phẩm</p>
                        <h6 class="mb-0">{{$all_product->count()}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-shopping-bag fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Tổng đơn hàng</p>
                        <h6 class="mb-0">{{$all_order->count()}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-user fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Tổng khách hàng</p>
                        <h6 class="mb-0">{{$all_customer->count()}}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4 px-4">
        <h4 class="mb-4">Thông tin tháng này</h4>

        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-clock fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Đơn đang đợi duyệt</p>
                        <h6 class="mb-0">{{$waiting_order->count()}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-truck me-2 fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Đơn đang vận chuyển</p>
                        <h6 class="mb-0">{{$shipping_order->count()}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-check-circle fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Đơn giao thành công</p>
                        <h6 class="mb-0">{{$completed_order->count()}}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-user fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Khách hàng đăng kí tháng này</p>
                        <h6 class="mb-0">{{$new_customer->count()}}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container-fluid pt-4 px-4 d-none">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Worldwide Sales</h6>
                        <a href="">Show All</a>
                    </div>
                    <canvas id="worldwide-sales"></canvas>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Salse & Revenue</h6>
                        <a href="">Show All</a>
                    </div>
                    <canvas id="salse-revenue"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4 px-4 d-none">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Worldwide Sales</h6>
                        <a href="">Show All</a>
                    </div>
                    <canvas id="worldwide-sales"></canvas>
                </div>
            </div>
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h6 class="mb-0">Salse & Revenue</h6>
                        <a href="">Show All</a>
                    </div>
                    <canvas id="salse-revenue"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-12 col-xl-6 d-none">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Single Line Chart</h6>
                    <canvas id="line-chart"></canvas>
                </div>
            </div>
            <form method="get" action="{{URL::to('/admin#chart')}}" id="chart">
                <label for="year">Chọn năm:</label>
                <select name="year" id="year">
                    <?php 
                        $currentYear = date('Y'); // lấy giá trị năm hiện tại
                        for ($i = $currentYear ; $i >= $currentYear-5; $i--) { // lặp từ năm đầu tiên trong danh sách đến năm hiện tại
                            $selected = ($i == $year) ? 'selected' : ''; // kiểm tra xem năm hiện tại có phải là năm đã chọn hay không
                            echo '<option value="'. $i .'" '. $selected .'>'. $i .'</option>'; // tạo tùy chọn cho danh sách
                        }
                    ?>
                </select>
                <button type="submit">Xác nhận</button>
            </form>
            <div class="col-sm-12 col-xl-12" >
                <div class="bg-light rounded h-100 p-4">
                    <h4 class="mb-4">Biểu đồ thống kê doanh thu năm {{$year}}</h4>
                    <canvas id="bar-chart"></canvas>
                </div>
            </div>  
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <script src="{{asset('/assets/admin/lib/chart/chart.min.js')}}"></script>
    <script>
        // Single Bar Chart
        var data = <?=json_encode($totalByMonth);?>;
        var ctx4 = $("#bar-chart").get(0).getContext("2d");
        console.log(ctx4);
        var myChart4 = new Chart(ctx4, {
            type: "bar",
            data: {
                labels: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7","Tháng 8","Tháng 9","Tháng 10","Tháng 11", "Tháng 12"],
                datasets: [{
                    label: "VNĐ",
                    backgroundColor: [
                        "rgba(0, 156, 255, .7)",
                    ],
                    data: Object.values(data)
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>



<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
@endsection
