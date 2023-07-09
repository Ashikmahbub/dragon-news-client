import React from 'react';
import { createContext } from 'react';
export const AuthContext = createContext();


const AuthProvider = ({children}) => {
    const user  = {displayName: 'Bastasha ALi'};
    const authInfo = {user}
    return (
        <AuthContext.Provider value={authInfo}>

        </AuthContext.Provider>
    );
};

export default AuthProvider;