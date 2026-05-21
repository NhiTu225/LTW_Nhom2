<!doctype html>
<html lang="vi">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - BookStore Admin</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <aside class="sidebar">
      <div class="brand">
        <div class="brand-icon">📚</div><b>BookStore Admin</b>
      </div>
      <nav>
        <a class="nav-item active" href="/">
          <span>📊</span><span>Dashboard</span>
        </a>
        <a class="nav-item " href="/view/books.php">
          <span>📚</span><span>Quản lý sách</span>
        </a>
        <a class="nav-item " href="/view/categories.php">
          <span>📁</span><span>Danh mục</span>
        </a>
      </nav>
      <div class="version"><b>Phiên bản</b><small>Admin v1.0</small></div>
    </aside>
    <header class="topbar">
      <div class="search">🔎<input placeholder="Tìm kiếm sách, đơn hàng, khách hàng..."></div>
      <div class="account"><button class="bell">🔔<span></span></button>
        <div class="admin">
          <p>Admin User</p><small>admin@bookstore.com</small>
        </div>
        <div class="avatar">👤</div>
      </div>
    </header>
    <main class="main">
      <section class="page-title">
        <h1>Dashboard</h1>
        <p>Tổng quan hoạt động kinh doanh</p>
      </section>
      <div class="grid cards">
        <div class="stat">
          <div>
            <p>Tổng doanh thu</p> 
            <h2><?= htmlspecialchars($user['name']) ?> ₫</h2><span class="trend">↗ +12.5% <small>so với tháng trước</small></span>
          </div>
          <div class="stat-icon">💰</div>
        </div>
        <div class="stat">
          <div>
            <p>Số đơn hàng</p>
            <h2><?= htmlspecialchars($don_hang['don_hang']) ?></h2><span class="trend">↗ +8.2% <small>so với tháng trước</small></span>
          </div>
          <div class="stat-icon">📦</div>
        </div>
        <div class="stat">
          <div>
            <p>Số sách đang bán</p>
            <h2><?= htmlspecialchars($sach['sach']) ?></h2><span class="trend">↗ +15 <small>so với tháng trước</small></span>
          </div>
          <div class="stat-icon">📚</div>
        </div>
        <div class="stat">
          <div>
            <p>Số khách hàng</p>
            <h2><?= htmlspecialchars($khach_hang['khach_hang']) ?></h2><span class="trend">↗ +23.1% <small>so với tháng trước</small></span>
          </div>
          <div class="stat-icon">👥</div>
        </div>
      </div>
      <div class="grid two">
        <div class="panel">
          <h2>Doanh thu theo tháng</h2>
          <div class="chart-bars">
            <div class="bar" style="height:48%"><span><?= htmlspecialchars($doanh_thu['T1']) ?></span><small>T1</small></div>
            <div class="bar" style="height:58%"><span><?= htmlspecialchars($doanh_thu['T2']) ?></span><small>T2</small></div>
            <div class="bar" style="height:52%"><span><?= htmlspecialchars($doanh_thu['T3']) ?></span><small>T3</small></div>
            <div class="bar" style="height:67%"><span><?= htmlspecialchars($doanh_thu['T4']) ?></span><small>T4</small></div>
            <div class="bar" style="height:76%"><span><?= htmlspecialchars($doanh_thu['T5']) ?></span><small>T5</small></div>
            <div class="bar" style="height:82%"><span><?= htmlspecialchars($doanh_thu['T6']) ?></span><small>T6</small></div>
            <div class="bar" style="height:72%"><span><?= htmlspecialchars($doanh_thu['T7']) ?></span><small>T7</small></div>
            <div class="bar" style="height:78%"><span><?= htmlspecialchars($doanh_thu['T8']) ?></span><small>T8</small></div>
            <div class="bar" style="height:87%"><span><?= htmlspecialchars($doanh_thu['T9']) ?></span><small>T9</small></div>
            <div class="bar" style="height:93%"><span><?= htmlspecialchars($doanh_thu['T10']) ?></span><small>T10</small></div>
            <div class="bar" style="height:85%"><span><?= htmlspecialchars($doanh_thu['T11']) ?></span><small>T11</small></div>
            <div class="bar" style="height:100%"><span><?= htmlspecialchars($doanh_thu['T12']) ?></span><small>T12</small></div>
          </div>
        </div>
        <div class="panel">
          <h2>Trạng thái đơn hàng</h2>
          <div class="donut">
            <div><b><?= htmlspecialchars($don_hang['don_hang']) ?></b><small>đơn hàng</small></div>
          </div>
          <ul class="legend">
            <li><span class="c-yellow"></span>Chờ xác nhận: 45</li>
            <li><span class="c-blue"></span>Đang xử lý: 82</li>
            <li><span class="c-purple"></span>Đang giao: 128</li>
            <li><span class="c-green"></span>Đã hoàn thành: 956</li>
            <li><span class="c-red"></span>Đã hủy: 37</li>
          </ul>
        </div>
      </div>
      <div class="grid two">
        <div class="panel no-pad">
          <div class="panel-head">
            <h2>Đơn hàng mới nhất</h2>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Mã đơn</th>
                  <th>Khách hàng</th>
                  <th>Tổng tiền</th>
                  <th>Trạng thái</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>DH001</td>
                  <td>Nguyễn Văn A</td>
                  <td>450.000 ₫</td>
                  <td><span class="badge completed">Đã hoàn thành</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="panel no-pad">
          <div class="panel-head">
            <h2>Top sách bán chạy</h2>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Tên sách</th>
                  <th>Tác giả</th>
                  <th>Đã bán</th>
                  <th>Doanh thu</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Đắc Nhân Tâm</td>
                  <td>Dale Carnegie</td>
                  <td>342</td>
                  <td>61.560.000 ₫</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </body>
</html>a
