 


// Initialize Firebase Authentication and get a reference to the service

// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getAuth } from "firebase/auth";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyALGJMzACQIiEGYPSJ3kS_VNO3wNt9Evac",
  authDomain: "dragon-news-15a8e.firebaseapp.com",
  projectId: "dragon-news-15a8e",
  storageBucket: "dragon-news-15a8e.appspot.com",
  messagingSenderId: "1084393713093",
  appId: "1:1084393713093:web:64b1adaa5ab8e8ab65efac",
  measurementId: "G-2GX2ES5QG3"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const auth = getAuth(app);
export default app;
