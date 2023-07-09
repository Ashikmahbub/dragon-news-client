import React from "react";
import Card from "react-bootstrap/Card";
import { Link } from "react-router-dom";
import Image from "react-bootstrap/Image";
import { FaEye, FaRegBookmark, FaShareAlt, FaStar } from "react-icons/fa";

const NewsSummaryCard = ({ news }) => {
  const { _id, title, author, details, image_url,rating,total_view} = news;
  return (
    <div>
      <Card className="mb-5">
        <Card.Header
          as="h5"
          className="d-flex justify-content-between align-items-center"
        >
          <div>
            <Image src={author.img} roundedCircle style={{ height: "60px" }} />
            <p><small>{author.name}</small></p>
          </div>
           
          <div>
            <div>
              <FaRegBookmark></FaRegBookmark>
            </div>
            <div>
              <FaShareAlt></FaShareAlt>
            </div>
          </div>
        </Card.Header>
        <Card.Body>
          <Card.Title>{title}</Card.Title>
          <Card.Img variant="top" src={image_url} />
          <Card.Text>
            {details.length > 250 ? (
              <p>
                {details.slice(0, 200) + "...."}{" "}
                <Link to={`/news/${_id}`}> Read More</Link>
              </p>
            ) : (
              <p>{details}</p>
            )}
          </Card.Text>
        </Card.Body>
        <Card.Footer className="d-flex justify-content-between">
            <div>
                <FaStar className="text-warning me-2"></FaStar>
                <span>{rating?.number}</span>
            </div>
            <div>
                <span>{total_view}<FaEye className="me-2"></FaEye></span>
            </div>
        </Card.Footer>
      </Card>
    </div>
  );
};

export default NewsSummaryCard;
