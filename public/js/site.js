document.addEventListener("DOMContentLoaded", function () {

const toastElList = document.querySelectorAll(".toast");
const toastList = [...toastElList].map(
  (toastEl) => new bootstrap.Toast(toastEl, { delay: 7000 })
);
toastList.forEach((toast) => toast.show());

  const alert = document.querySelector(".alert");
  if (alert) {
    setTimeout(() => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 3000); // 3000ms = 3 detik
  }
 
  const formWrapper = document.getElementById("comment-form-wrapper");
  const parentInput = document.getElementById("parent_id");
  const formTitle = document.getElementById("comment-form-title");
  const cancelBtn = document.getElementById("cancel-reply");
  const defaultPosition = formWrapper.parentNode; // default di bawah artikel

  document.querySelectorAll(".comment-reply").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const commentItem = this.closest(".single_comment_area");
      const commentAuthor = commentItem.querySelector(".post-author").innerText;
      const commentId = commentItem.getAttribute("data-id");

      parentInput.value = commentId;
      formTitle.innerText = "Balas komentar dari " + commentAuthor;

      commentItem.appendChild(formWrapper);
      cancelBtn.classList.remove("d-none");
    });
  });

  cancelBtn.addEventListener("click", function () {
    parentInput.value = 0;
    formTitle.innerText = "Tinggalkan balasan";
    defaultPosition.appendChild(formWrapper);
    cancelBtn.classList.add("d-none");
  });

  const nav = document.querySelector(".classy-navbar");
  const navHeight = nav.offsetHeight;
  const stickyOffset = 100; // Jarak gulir sebelum menu menjadi "sticky"

  window.addEventListener("scroll", function () {
    if (window.pageYOffset > stickyOffset) {
      nav.classList.add("sticky-nav", "scrolled");
      document.body.style.paddingTop = navHeight + "px"; // Mencegah halaman "melompat"
    } else {
      nav.classList.remove("sticky-nav", "scrolled");
      document.body.style.paddingTop = 0;
    }
  });
});
