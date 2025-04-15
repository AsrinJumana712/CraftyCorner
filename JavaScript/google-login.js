import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-auth.js";

// Firebase config
const firebaseConfig = {
  apiKey: "AIzaSyBsOo6v3qLuat0ViESLnVetwUaRYG81IV4",
  authDomain: "craftycorner-5d4c5.firebaseapp.com",
  projectId: "craftycorner-5d4c5",
  storageBucket: "craftycorner-5d4c5.appspot.com",
  messagingSenderId: "386849443100",
  appId: "1:386849443100:web:c1d54af4981a898de3fed9",
  measurementId: "G-BTH469J9JK",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const provider = new GoogleAuthProvider();

document.addEventListener("DOMContentLoaded", () => {
  const googleLogin = document.getElementById("google-login-btn");

  if (googleLogin) {
    googleLogin.addEventListener("click", async () => {
      try {
        const result = await signInWithPopup(auth, provider);
        const user = result.user;

        console.log("Google user signed in:", user);

        const response = await fetch("google_login.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            email: user.email,
            username: user.displayName || "Guest User",
            photoURL: user.photoURL || "",
          }),
        });

        const data = await response.json();
        console.log("Response from server:", data);

        if (data.status === "success") {
          window.location.href = data.redirect;
        } else {
          alert(data.message || "Login failed. Please try again.");
        }
      } catch (error) {
        console.error("Google login error:", error);
        alert("Google Sign-In failed. Please try again later.");
      }
    });
  }
});
