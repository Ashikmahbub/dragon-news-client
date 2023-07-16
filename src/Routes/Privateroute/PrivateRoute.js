import React, { useContext } from 'react';
import { AuthContext } from '../../AuthProvider/AuthProvider';
import { Navigate, useLocation } from 'react-router-dom';
import Spinner from 'react-bootstrap/Spinner';

//only allow authencitated user can 
// redirect user to 

const PrivateRoute = ({ children }) => {
    const location = useLocation();
    const { user, loading } = useContext(AuthContext);
    if (loading) {
        return <>

            <Spinner animation="border" variant="info" />
        </>

    }
    if (!user) {

        return <Navigate to='/login' state={{ from: location }} replace></Navigate>

    }

    return children;
};

export default PrivateRoute;