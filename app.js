document.addEventListener("DOMContentLoaded", function () {
  const firebaseConfig = {
    apiKey: "AIzaSyASe8RMzNaNc5unnkJXvGYHZOx0iOmYZt8",
    authDomain: "pipe-doctor-25.firebaseapp.com",
    projectId: "pipe-doctor-25",
    storageBucket: "pipe-doctor-25.firebasestorage.app",
    messagingSenderId: "872266294619",
    appId: "1:872266294619:web:e4d7ceca2d5d0618c5667e",
    measurementId: "G-PPGB40N6XD",
  };

  firebase.initializeApp(firebaseConfig);
  const auth = firebase.auth();

  const defineLoginMethodForOwner = "password";

  const loginDiv = document.getElementById("login-container");
  const dashboardDiv = document.getElementById("dashboard-container");

  const loginForm = document.getElementById("login-form");
  loginForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const email = loginForm["email"].value;
    const password = loginForm["password"].value;

    auth
      .signInWithEmailAndPassword(email, password)
      .then((userCredential) => {
        const user = userCredential.user;
        checkUserPasswordStatus(user);
      })
      .catch((error) => {
        console.error("Login failed:", error.message);
        console.log(`${getErrorMessage(error.code)}`);
      });
  });

  auth.onAuthStateChanged((user) => {
    if (user) {
      checkUserPasswordStatus(user);
    } else {
      dashboardDiv.style.display = "none";
      loginDiv.style.display = "block";
    }
  });

  const logoutButton = document.getElementById("logout-button");
  logoutButton.addEventListener("click", () => {
    auth
      .signOut()
      .then(() => {
        loginDiv.style.display = "block";
        dashboardDiv.style.display = "none";
      })
      .catch((error) => {
        const errorCode = error.code;
        console.log(`${getErrorMessage(errorCode)}`);
      });
  });

  const forgetPasswordButton = document.getElementById("forget-password-btn");
  forgetPasswordButton.addEventListener("click", () => {
    const getEmailFromInput = document.getElementById("email");
    const emailText = getEmailFromInput.value;
    if (isValidEmail(emailText)) {
      const originalText = forgetPasswordButton.textContent;
      forgetPasswordButton.textContent = "Check your email inbox";
      auth
        .sendPasswordResetEmail(emailText)
        .then(() => {
          if (forgetPasswordButton.classList.contains("is-disabled")) {
            return;
          }
          forgetPasswordButton.classList.add("is-disabled");

          setTimeout(() => {
            forgetPasswordButton.textContent = originalText;
            forgetPasswordButton.classList.remove("is-disabled");
          }, 10000);
        })
        .catch((e) => {
          setTimeout(() => {
            forgetPasswordButton.textContent = originalText;
            forgetPasswordButton.classList.remove("is-disabled");
          }, 10000);
          const errorCode = e.code;

          console.log(`${getErrorMessage(errorCode)}`);
        });
    } else {
      console.log(
        "Please enter a valid registered email address in the email input field."
      );
    }
  });

  async function checkUserPasswordStatus(user) {
    const email = user.email;
    if (!email) {
      return;
    }
    try {
      const signInMethods = await auth.fetchSignInMethodsForEmail(email);

      if (signInMethods.includes("password")) {
        //here open
        loginDiv.style.display = "none";
        dashboardDiv.style.display = "block";
        const userDisplayName = document.getElementById("owner-name");
        if (email) {
          userDisplayName.style.fontWeight = "bold";
          userDisplayName.textContent = email;
        } else {
          userDisplayName.textContent = "Set Your name";
        }
      } else {
        loginDiv.style.display = "block";
        dashboardDiv.style.display = "none";

        console.log("User does not have a password. Access denied.");

        auth.signOut();
      }
    } catch (error) {
      console.error("Error fetching sign-in methods:", error);
      auth.signOut();
    }
  }

  function isValidEmail(email) {
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return emailRegex.test(email);
  }

  function getErrorMessage(errorCode) {
    switch (errorCode) {
      case "auth/user-not-found":
      case "auth/wrong-password":
        return "Incorrect email or password.";
      case "auth/email-already-in-use":
        return "This email address is already in use by another account.";
      case "auth/invalid-login-credentials":
        return "Incorrect email or password.";
      case "auth/invalid-email":
        return "The email address provided is not valid.";
      case "auth/too-many-requests":
        return "Your account has been temporarily disabled due to too many failed login attempts. Please try again later.";
      default:
        return "An unexpected error occurred. Please check your connection and try again.";
    }
  }
});
