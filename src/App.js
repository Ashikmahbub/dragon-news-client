 
import { RouterProvider } from 'react-router-dom';
import './App.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import { routes } from './Routes/Routes';

function App() {
  return (
    <div>
      <RouterProvider router ={routes}></RouterProvider>
       
    </div>
  );
}

export default App;
