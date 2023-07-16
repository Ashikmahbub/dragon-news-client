import React, { useEffect, useState } from 'react';
import { createContext } from 'react';
import {createUserWithEmailAndPassword, getAuth, onAuthStateChanged, signInWithEmailAndPassword, signInWithPopup, signOut, updateProfile} from 'firebase/auth';
import app from '../Firebase/Firebase';
export const AuthContext = createContext();
const auth = getAuth(app);


const AuthProvider = ({children}) => {
     const [user,setUser] = useState(null);
     const [loading,setLoding] = useState(true);

     


    // google sign in authentication process 
    const providerLogin =(provider) =>{
        return signInWithPopup(auth,provider);
    }
    const logOut =() =>{
        setLoding(true);
        return signOut(auth);
    }
    const createUser  =  (email,password) =>{
        setLoding(true)
        return createUserWithEmailAndPassword(auth,email,password);
    }
    const signIn = (email,password) =>{
        setLoding(true)
        return signInWithEmailAndPassword(auth,email,password);
    }
    const updateUserProfile =(profile)=>{
        return updateProfile(auth.currentUser,profile);

    }

    // user status observer 
    useEffect( () => {

        const unsubscribe = onAuthStateChanged(auth,(currentUser)=>{
           
            setUser(currentUser);
            setLoding(false);
        })
        return () => {
            unsubscribe();
        }
        
    },[])

    // data tranmission to other components 
    const authInfo = {
        user,loading,providerLogin,logOut,createUser,signIn,updateUserProfile
    };
    return (
        <AuthContext.Provider value={authInfo}>
            {children}

        </AuthContext.Provider>
    );
};

export default AuthProvider;