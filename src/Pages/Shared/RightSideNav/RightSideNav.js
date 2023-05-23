import React from "react";
import Button from "react-bootstrap/Button";
import ButtonGroup from "react-bootstrap/ButtonGroup";
import { FaGoogle, FaGithub, FaFacebook, FaWhatsapp, FaTwitch, FaTiktok } from "react-icons/fa";
import ListGroup from "react-bootstrap/ListGroup";
import BrandCarousal from "../BrandCarousal/BrandCarousal";

const RightSideNav = () => {
  return (
    <div>
      <h2>Right side</h2>
      <ButtonGroup vertical>
        <Button className="mb-2" variant="outline-primary">
          {" "}
          <FaGoogle></FaGoogle> Login with Google
        </Button>
        <Button variant="outline-dark">
          <FaGithub></FaGithub> Login with Github
        </Button>
      </ButtonGroup>
      <div className="mt-4">
      <h5>Find Us On </h5>
        <ListGroup variant="flush">
          <ListGroup.Item className="mb-3"><FaFacebook></FaFacebook> Facebook</ListGroup.Item>
          <ListGroup.Item className="mb-3"><FaWhatsapp/>WhatsApp</ListGroup.Item>
          <ListGroup.Item className="mb-3"><FaTwitch/>Twitter </ListGroup.Item>
          <ListGroup.Item className="mb-3"><FaTiktok></FaTiktok>TikTok</ListGroup.Item>
        </ListGroup>
      </div>
      <div>
        <BrandCarousal></BrandCarousal>
      </div>
       


      
    </div>

  ); 
};

export default RightSideNav;
