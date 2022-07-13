import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getMessaging } from "firebase/messaging";

// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyAaTwu6zwYHSdiEDf281o0J0NJyOtZ6tD4",
    authDomain: "isnaad-1c0d8.firebaseapp.com",
    projectId: "isnaad-1c0d8",
    storageBucket: "isnaad-1c0d8.appspot.com",
    messagingSenderId: "925242928712",
    appId: "1:925242928712:web:5db7890cd411e618a8b27d",
    measurementId: "G-450ZSGS5ZP"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const messaging = getMessaging(app);
getToken(messaging, { vapidKey: 'BIZQfRtPwGq9qsjbPnMv5VYefoPg4DzzR2dTwmSlGMvggH1tNj2jSCyXiv-Eo0PKd3Hv9Kz8qifIV8f2yMSiCdY' }).then((currentToken) => {
    if (currentToken) {
        console.log(currentToken)
    } else {
        // Show permission request UI
        console.log('No registration token available. Request permission to generate one.');
        // ...
    }
}).catch((err) => {
    console.log('An error occurred while retrieving token. ', err);
    // ...
});
