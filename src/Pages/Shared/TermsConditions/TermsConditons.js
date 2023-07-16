import React from 'react';
import { Link } from 'react-router-dom';

const TermsConditons = () => {
    return (
        <div>
            <h3>
                Here is terms and contidition

            </h3>
            <p>
                Go back to Registration : <Link to='/register'>Register </Link>
            </p>

        </div>
    );
};

export default TermsConditons;