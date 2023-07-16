import React, { useContext, useState } from 'react';
import Button from 'react-bootstrap/Button';
import Form from 'react-bootstrap/Form';
import { AuthContext } from '../AuthProvider/AuthProvider';

const Register = () => {
    const [error,setError] = useState('');
    const {createUser} = useContext(AuthContext);
    const handleSubmit  = event =>{
        event.preventDefault();
        const form = event.target;
        const name = form.name.value;
        const email = form.email.value;
        const password = form.password.value;
        createUser(email,password)
        .then (result =>{
            const user = result.user;
            setError('')
            console.log(user);
            form.reset();
        })
        .catch(e =>{
            setError(e)
            console.error(e)})
    }
    return (
        <div>
             <Form onSubmit={handleSubmit}>
                <Form.Group className="mb-3" controlId="formBasicName">
                    <Form.Label>Your Name</Form.Label>
                    <Form.Control name='name' type="text" placeholder="Your Name" />

                </Form.Group>

                
                <Form.Group className="mb-3" controlId="formBasicEmail">
                    <Form.Label>Email address</Form.Label>
                    <Form.Control name='email' type="email" placeholder="Email" />
                </Form.Group>
                
                
                <Form.Group className="mb-3" controlId="formBasicPassword">
                    <Form.Label>Password</Form.Label>
                    <Form.Control name='password' type="password" placeholder="Password" />
                </Form.Group>
                <Form.Group className="mb-3" controlId="formBasicCheckbox">
                    <Form.Check type="checkbox" label="Check me out" />
                </Form.Group>
               

                <Button variant="primary" type="submit">
                   Sign Up
                </Button>
                <Form.Text className="danger">
                     {error}
                </Form.Text>
            </Form>
            
        </div>
    );
};

export default Register;