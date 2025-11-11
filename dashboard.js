document.addEventListener("DOMContentLoaded", () => {
  const expandTriggers = document.querySelectorAll(".expand-arrow");
  const reviewCards = document.querySelectorAll(".review-card");
  const expandedClass = "expanded";

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

      if (
        e.target.classList.contains("archive-button") ||
        e.target.closest(".archive-button")
      ) {
        const reviewCard = e.target.closest(".review-card");
        const reviewId = reviewCard.dataset.id;
        markReviewAsArchived(reviewId, reviewCard);
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
      formData.append("archived", 0);

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

  async function markReviewAsArchived(reviewId, reviewCard) {
    try {
      const id = reviewId;
      const formData = new FormData();
      formData.append("id", id);
      formData.append("archived", 1);
      formData.append("approved", 0);

      const response = await fetch("review_archive.php", {
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
});
