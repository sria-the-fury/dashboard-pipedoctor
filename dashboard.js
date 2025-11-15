document.addEventListener("DOMContentLoaded", () => {
  const expandTriggers = document.querySelectorAll(".expand-arrow");
  const reviewCards = document.querySelectorAll(".review-card");
  const expandedClass = "expanded";

  const selectedClassForButton = "selected-button";

  const writeButton = document.getElementById("write-news");
  const publishedButton = document.getElementById("all-published-news");
  const publishedNewsArea = document.querySelector(".published-news-area");
  const newsFormArea = document.querySelector(".news-form-write-area");
  writeButton.addEventListener("click", () => {
    publishedButton.classList.remove(selectedClassForButton);
    writeButton.classList.add(selectedClassForButton);
    publishedNewsArea.style.display = "none";
    newsFormArea.style.display = "block";
  });

  publishedButton.addEventListener("click", () => {
    writeButton.classList.remove(selectedClassForButton);
    publishedButton.classList.add(selectedClassForButton);
    newsFormArea.style.display = "none";
    publishedNewsArea.style.display = "block";
  });

  const closeAllCards = () => {
    reviewCards.forEach((card) => {
      const arrowUp = card.querySelector(".arrow-up");
      const arrowDown = card.querySelector(".arrow-down");
      card.classList.remove(expandedClass);

      if (arrowUp && arrowDown) {
        arrowUp.style.display = "none";
        arrowDown.style.display = "block";
      }
    });
  };

  // Expand/collapse review cards

  expandTriggers.forEach((trigger) => {
    trigger.addEventListener("click", function () {
      const parentCard = this.closest(".review-card");

      if (!parentCard) return;

      const isCurrentlyExpanded = parentCard.classList.contains(expandedClass);

      closeAllCards();

      if (!isCurrentlyExpanded) {
        parentCard.classList.add(expandedClass);

        const arrowUp = parentCard.querySelector(".arrow-up");
        const arrowDown = parentCard.querySelector(".arrow-down");

        if (arrowUp && arrowDown) {
          arrowUp.style.display = "block";
          arrowDown.style.display = "none";
        }
      }
    });
  });

  //  review Manipulatation
  document
    .getElementById("review-list")
    .addEventListener("click", function (e) {
      if (
        e.target.classList.contains("delete-button") ||
        e.target.closest(".delete-button")
      ) {
        const reviewCard = e.target.closest(".review-card");
        const deleteId = reviewCard.dataset.id;

        deleteReview(deleteId, reviewCard);
      }

      if (
        e.target.classList.contains("approved-button") ||
        e.target.closest(".approved-button")
      ) {
        const reviewCard = e.target.closest(".review-card");
        const reviewId = reviewCard.dataset.id;

        checkedReviews(reviewId, reviewCard);
      }
    });

  document
    .getElementById("all-news-cards")
    .addEventListener("click", function (e) {
      if (
        e.target.classList.contains("news-delete-btn") ||
        e.target.closest(".news-delete-btn")
      ) {
        const newsCard = e.target.closest(".news-card");
        const cardId = newsCard.dataset.id;

        deleteNews(cardId, newsCard);
      }
    });

  // functions
  async function deleteReview(id, reviewCard) {
    try {
      const formData = new FormData();
      formData.append("id", id);

      const response = await fetch("delete_review.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        reviewCard.remove();
        window.location.reload();
      }
    } catch (error) {
      console.error("Error:", error);
    }
  }

  async function checkedReviews(reviewId, reviewCard) {
    try {
      const id = reviewId;
      const formData = new FormData();
      formData.append("id", id);
      formData.append("approved", 1);

      const response = await fetch("approve_review.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        reviewCard.remove();
      } else {
        console.log("Error updating status: ", result.message);
      }
    } catch (error) {
      console.error("Error:", error);
    }
  }

  const newsForm = document.getElementById("news-form");
  newsForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const newsFormData = new FormData(newsForm);
    console.log("newFormData => ", newsFormData);

    fetch("add_news.php", {
      method: "POST",
      body: newsFormData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })

      .then((data) => {
        newsForm.reset();
        window.location.reload();
      })
      .catch((error) => {
        console.error("Fetch error:", error);
      });
  });

  async function deleteNews(newsId, newsCard) {
    const id = newsId;
    const card = newsCard;
    try {
      const formData = new FormData();
      formData.append("id", id);

      const response = await fetch("delete_news.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        card.remove();
        window.location.reload();
      }
    } catch (error) {
      console.error("Error:", error);
    }
  }
});
