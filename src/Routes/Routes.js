import { createBrowserRouter } from "react-router-dom";
import Main from "../Layout/Main";
import Home from "../Pages/Home/Home";
import Category from "../Pages/Category/Category";
import News from "../Pages/News/News";
import Login from "../Login/Login";
import Register from "../Register/Register";
import PrivateRoute from "./Privateroute/PrivateRoute";
import TermsConditons from "../Pages/Shared/TermsConditions/TermsConditons";

export const routes = createBrowserRouter([
    {
        path:'/',
        element: <Main></Main>,
        children:[
            {
                path :'/',
                element: <Home></Home>,
                loader : ()=>fetch('http://localhost:5000/news')
            },
            {
                path : '/category/:id',
                element: <Category></Category>,
                loader: ({params}) => fetch(`http://localhost:5000/category/${params.id}`)
            },
            {
                path: '/news/:id',
                element:  <PrivateRoute><News></News></PrivateRoute>,
                loader: ({params}) => fetch(`http://localhost:5000/news/${params.id}`)
               
            },

            {
                path : '/login',
                element: <Login/>
            },
            {
                path : '/register',
                element: <Register></Register>

            },
            {
                path : '/terms',
                element: <TermsConditons></TermsConditons>

            }
            

           

        ]
    }
]);