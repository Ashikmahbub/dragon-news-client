import React from "react";
import Carousel from "react-bootstrap/Carousel";
import img1 from '../../../assests/brand/img-1.jpg'
import img2 from '../../../assests/brand/img-2.jpg'
const BrandCarousal = () => {
  return (
    <div>
      <Carousel>
        <Carousel.Item>
          <img
            className="d-block w-100"
            src={img1}
            alt="First slide"
          />
          <Carousel.Caption>
            <h3>Snake</h3>
            
          </Carousel.Caption>
        </Carousel.Item>
        <Carousel.Item>
          <img
            className="d-block w-100"
            src={img2}
            alt="Second slide"
          />

          <Carousel.Caption>
            <h3>Neon Pothos</h3>
             
          </Carousel.Caption>
        </Carousel.Item>
         
      </Carousel>
    </div>
  );
};

export default BrandCarousal;
