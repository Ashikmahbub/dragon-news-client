import React, { useContext, useState } from 'react';
import Button from 'react-bootstrap/Button';
import Form from 'react-bootstrap/Form';
import { AuthContext } from '../AuthProvider/AuthProvider';
import { Link } from 'react-router-dom';

const Register = () => {
    const [accepted,setAccepted] =useState(false);
    const [error,setError] = useState('');
    const {createUser,updateUserProfile} = useContext(AuthContext);
    const handleProfile =(name)=>{
        const profile ={
            displayName:name,
            
        }
        updateUserProfile(profile)
        .then(()=>{

        })
        .catch(error => console.error(error))

    }  
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
            handleProfile(name);
        })
        .catch(e =>{
            setError(e)
            console.error(e)})
    }
    const handleTerms =(event) =>{
        setAccepted(event.target.checked);

    

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
                    <Form.Check onClick={handleTerms} type="checkbox" label= {<>Accept <Link to="/terms">Terms and conditions</Link></>} />
                </Form.Group>
               

                <Button variant="primary" type="submit" disabled={!accepted}>
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