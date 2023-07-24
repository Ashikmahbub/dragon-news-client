import React, { useContext, useState } from 'react';
import Button from 'react-bootstrap/Button';
import Form from 'react-bootstrap/Form';
import { AuthContext } from '../AuthProvider/AuthProvider';
import { useLocation, useNavigate } from 'react-router-dom';
import { toast } from 'react-hot-toast';


const Login = () => { 
    const location = useLocation();
    const from = location.state?.from?.pathname || '/';
    const [error,setError] = useState('');
    const [user,setUser] = useState('');
    const navigate = useNavigate();
    const {signIn} = useContext(AuthContext);
    const  handleSubmit = event =>{
        event.preventDefault();
        const form = event.target;
         
        const email = form.email.value;
        const password = form.password.value;
        signIn(email,password)
        .then (result =>{
            const user = result.user;
            setUser(user);
            form.reset();
            setError('');
            if (user.emailVerified){
                navigate(from,{replace:true});
            }
            else{
                toast.error('Your Email Is not Verfied. Please verify Email');
            }
           
            })
           
        .catch(error =>{
            console.error(error)
        setError(error.message);

        } )
        

    }

    return (
        <div>
            <Form onSubmit={handleSubmit}>
                <Form.Group className="mb-3" controlId="formBasicEmail">
                    <Form.Label>Email address</Form.Label>
                    <Form.Control name='email' type="email" placeholder="Enter email" />

                </Form.Group>

                <Form.Group className="mb-3" controlId="formBasicPassword">
                    <Form.Label>Password</Form.Label>
                    <Form.Control name='password' type="password" placeholder="Password" />
                </Form.Group>
                <Form.Group className="mb-3" controlId="formBasicCheckbox">
                    <Form.Check type="checkbox" label="Check me out" />
                </Form.Group>
               

                <Button variant="primary" type="submit">
                    Log In
                </Button>
                <Form.Text className="text-danger">
                     {error}
                </Form.Text>
            </Form>

        </div>
    );
};

export default Login;