import firebase from "firebase";


// firebase init - add your own config here
const firebaseConfig = {
  apiKey: "AIzaSyCvLMQD753oXePVCwR4hIa44J1pFLcJFBk",
  authDomain: "m-market-d7068.firebaseapp.com",
  databaseURL: "https://m-market-d7068-default-rtdb.firebaseio.com",
  projectId: "m-market-d7068",
  storageBucket: "m-market-d7068.appspot.com",
  messagingSenderId: "52247310647",
  appId: "1:52247310647:web:d232e91437dec2a3eeeab2",
  measurementId: "G-XBV2EM8NQS"
}
firebase.initializeApp(firebaseConfig)


export default firebase;
