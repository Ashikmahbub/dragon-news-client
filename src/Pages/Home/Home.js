import React from 'react';
import { useLoaderData } from 'react-router-dom';
import NewsSummaryCard from '../Shared/NewsSummaryCard/NewsSummaryCard';

const Home = () => {
    const allNews = useLoaderData();
    return (
        <div>
            <h2> 
                Dragon news :{allNews.length}

                
                {
                        allNews.map(news=> <NewsSummaryCard
                        
                        key={news._id}
                        news={news}
                        
                        
                        >
                            
                        </NewsSummaryCard>)

                        
                }
            

            </h2>
        </div>
    );
};

export default Home;