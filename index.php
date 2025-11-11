<!DOCTYPE html>
<html lang="en">

<head>
  <title>Dashboard-PipeDoctor</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Quicksand:wght@300..700&display=swap"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="index.css" />
</head>

<body>
  <!-- login page -->
  <div id="login-container">
    <div class="login-area">
      <div class="login-form-area">
        <div class="login-card backdrop-blur-m">
          <div class="logo">
            <i class="fa-solid fa-faucet-drip"></i>
            <span>Doctor</span>
          </div>

          <h3>Dashboard Login</h3>
          <form id="login-form">
            <div class="form-group">
              <label for="email">Registered Email:</label>
              <input type="email" id="email" name="email" required />
            </div>
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" id="password" name="password" required />
            </div>

            <div class="button-and-text">
              <button type="submit">Login</button>
              <span class="forgot-password" id="forget-password-btn">Forget Password?</span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- after login -->
  <div class="dashboard-area" id="dashboard-container">
    <div class="dashboard">
      <div class="top-bar">
        <div class="logo-dashboard">
          <i class="fa-solid fa-faucet-drip"></i>
          <span>Doct 'r</span>
        </div>
        <div class="name-and-email backdrop-blur-m">
          <div class="owner-name" id="owner-name">Pipe Doctor's Admin</div>
        </div>

        <button id="logout-button" class="logout-btn">Logout</button>
      </div>
    </div>
    <div class="summary">
      <div class="card-review-message backdrop-blur-m">
        <div class="card-icon">
          <i class="fa-solid fa-star"></i>
        </div>
        <div class="card-info">
          <?php
          include 'connection.php';
          $review_count_query = "SELECT COUNT(*) AS total_reviews, AVG(clientRating) AS average_rating FROM reviews";

          $review_count_result = $connection_sql->query($review_count_query);

          $total_reviews = 0;
          $average_rating = 0.0;

          if ($review_count_result && $review_count_result->num_rows > 0) {
            $row = $review_count_result->fetch_assoc();
            $total_reviews = (int)$row['total_reviews'];
            $average_rating = (float)($row['average_rating'] ?? 0.0);
            echo '<div class="card-value" id="total-reviews">Total Reviews: ' . $total_reviews . '</div>
                  <div class="average-rating">Rating: ' . number_format($average_rating, 1) . '</div>';
          }
          mysqli_close($connection_sql);
          ?>

        </div>
      </div>
    </div>

    <div class="review-and-message-data">
      <div class="review-data">
        <div class="filter-controls">
          <form action="" method="GET">
            <select name="filter" id="message-filter" onchange="this.form.submit()">
              <option value="unapprove"
                <?php if (isset($_GET['filter']) && $_GET['filter'] == 'unapprove') echo 'selected'; ?>>
                Recent Unapprove Reviews
              </option>

              <option value="approved"
                <?php if (isset($_GET['filter']) && $_GET['filter'] == 'approved') echo 'selected'; ?>>
                Approved Reviews
              </option>

              <option value="all-reviews"
                <?php if (isset($_GET['filter']) && $_GET['filter'] == 'all-reviews') echo 'selected'; ?>>
                All Reviews
              </option>

              <option value="archived"
                <?php if (isset($_GET['filter']) && $_GET['filter'] == 'archived') echo 'selected'; ?>>
                Archived Reviews
              </option>

            </select>
          </form>
        </div>
        <div class="review-list backdrop-blur-m" id="review-list">

          <?php
          include 'connection.php';
          $filter = $_GET['filter'] ?? 'unapprove';

          $review_query = "SELECT * FROM reviews WHERE approved = 0 AND archived = 0";
          if ($filter == 'all-reviews') {
            $review_query = "SELECT * FROM reviews";
          } elseif ($filter == 'approved') {
            $review_query = "SELECT * FROM reviews WHERE approved = 1";
          } elseif ($filter == 'archived') {
            $review_query = "SELECT * FROM reviews WHERE archived = 1";
          }
          $review_query .= " ORDER BY created_at DESC";
          $review_result = $connection_sql->query($review_query);
          if ($review_result->num_rows > 0) {
            while ($row = $review_result->fetch_assoc()) {
              $name = htmlspecialchars($row['clientName']);
              $address = htmlspecialchars($row['clientLocation']);
              $rating = (int)$row['clientRating'];
              $content = htmlspecialchars($row['testimony']);
              $isApproved = (int)$row['approved'];
              $isArchived = (int)$row['archived'];
              $created_at = date('D, d M, H:i', strtotime($row['created_at']));

              $reviewApprovedIcon = '';
              $reviewArrpovedButton = '';
              $reviewArchiveIcon = '';
              $reviewArchiveButton = '';

              if ($isApproved == 1) {
                $reviewApprovedIcon = '<i class="fa-solid fa-circle-check"></i>';
              }
              if ($isArchived == 1) {
                $reviewArchiveIcon = '<i class="fa-solid fa-box-archive"></i>';
              }
              if ($isApproved == 0) {
                $reviewApprovedButton = '<div class="approved-button action-btn">
                                <i class="fa-solid fa-circle-check"></i> <span>APPROVE</span>
                              </div>';
              }

              if ($isArchived == 0) {
                $reviewArchiveButton = '<div class="archive-button action-btn">
                              <i class="fa-solid fa-box-archive"></i> <span>ARCHIVE</span>
                              </div>';
              }


              echo '<div class="review-card" data-id="' . $row["id"] . '">
                          <div class="name-address-rating-wrap">
                            <div class="name-address">
                              <div class="reviewer-name">' . $name . '</div>
                              <div class="reviewer-address"><i class="fa-solid fa-location-dot"></i> ' . $address . '</div>
                            </div>

                            <div class="review-status">
                            ' . $reviewApprovedIcon . '
                            ' . $reviewArchiveIcon . '
                            
                            </div>

                            <div class="rating-time">
                            <div class="reviewer-rating">';
              for ($i = 0; $i < intval($row["clientRating"]); $i++) {
                echo '<i class="fas fa-star"></i>';
              }
              for ($i = intval($row["clientRating"]); $i < 5; $i++) {
                echo '<i class="fa-regular fa-star"></i>';
              }
              echo '    </div>
                            <div class="time-and-arrow">
                              <div class="review-time">' . $created_at . '</div>
                              <div class="expand-arrow">
                                <div class="arrow-down">
                                  <i class="fa-solid fa-chevron-down"></i>
                                </div>
                                <div class="arrow-up">
                                  <i class="fa-solid fa-chevron-up"></i>
                                </div>
                              </div>
                            </div>
                            </div>
                            
                          </div>
                          <div class="review-content-action">
                            <div class="icon-content-data">
                            <div class="icon-stack">
  
                              <i class="fa-solid fa-quote-left icon-shadow"></i>
  
                                <i class="fa-solid fa-quote-left icon-main"></i>

                            </div>
                            <div class="content-data">
                              ' . $content . '
                            </div>
                            </div>
                            
                            <div class="review-action">
                              ' . $reviewApprovedButton . '
                              ' . $reviewArchiveButton . '
                              <div class="delete-button action-btn" id="delete-button-' . $row["id"] . '">
                                <i class="fa-regular fa-circle-xmark"></i> <span>DELETE</span>
                              </div>
                            </div>
                          </div>
                        </div>';
            }
          } else {
            echo "<p style='text-align: center;'>No review found.</p>";
          }
          mysqli_close($connection_sql);
          ?>

        </div>
      </div>
    </div>

    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-auth-compat.js"></script>

    <script src="app.js"></script>
    <script src="dashboard.js"></script>
</body>

</html>